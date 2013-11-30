<?php
include_once("config.php");
require_once("hyperlight/hyperlight.php");
class FFinit {
	var $Types = array();
	var $Ignore = array();
	var $sortBy;
	var $sortOr;

	function FFinit () {
		global 	$Sort, $SortOrder, $showContent_findFile, $viewFile_highlight, $ignore, $imgType, $embedType, $textType, $scriptType, $otherType;

		if ( $viewFile_highlight ) setcookie('highlight',true); else setcookie('highlight',false);
		foreach ($ignore as $k => $v) {$this->Ignore[$k]='./'.$v;}
		
		if(!$showContent_findFile)
		foreach (array("index.php","findFile","findFile.php") as $v) 
		$this->Ignore[]='./'.$v;

		$this->sortBy = ($Sort=='name')?SORT_STRING:SORT_NUMERIC;
		$this->sortOr = ($SortOrder=='ascending')?SORT_ASC:SORT_DESC;

		if (!empty($imgType))	$this->Types = array_merge($this->Types, $imgType);
		if (!empty($embedType))	$this->Types = array_merge($this->Types, $embedType);
		if (!empty($textType))	$this->Types = array_merge($this->Types, $textType);
		if (!empty($scriptType))$this->Types = array_merge($this->Types, $scriptType);
		if (!empty($otherType))	$this->Types = array_merge($this->Types, $otherType);
	}

	function trimArrayReplace($el){
		$el			= trim($el);
		$search		= array("\\"  ,"");
		$replace	= array("\\\\","");
		return str_replace($search, $replace, $el);
	}

	function start () {
		$activeSearch = split("¤¤¤",$_POST['dir']);
		if ( isset($_POST['dir']) && $activeSearch[0]=='findFile' ) { 
			$itemSearch = array_map(array($this, 'trimArrayReplace'), split(",",$activeSearch[1]) );
			header("Content-type: application/json");
			echo json_encode( $this->getSearch($itemSearch) );
		} elseif ( isset($_POST['dir']) && $activeSearch!='findFile' ) {
			header("Content-type: application/json");
			echo json_encode(  $this->getArray( $_POST['dir'] )  );
		} elseif ( isset($_GET['dir']) && $_COOKIE['highlight']==1   ) {
			echo '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">'.
			'<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>'.
			substr($_GET['dir'],(strrpos($_GET['dir'], '/'))+1)  .
			'</title><style>html,pre,body{margin:0;padding:0;position:absolute;top:0;left:0;width:100%;height:100%;overflow:scroll;font-size:16px;}</style>'.
			'<link rel="stylesheet" type="text/css" href="hyperlight/colors/zenburn.css"/></head><body>';
			$fname = '../'.$_GET['dir'];
			hyperlight_file($fname,'iphp');
			echo '</body></html>';
		} else {
			header("Content-type: application/json");
			echo json_encode(  array(array('not found',null,'S',0,0))  );
		}
	}

	function getExt ($file) {
		return strtolower(substr(strrchr($file, '.'), 1));
	}

	function FSConvert ($file) {
		$Unit = array("Bytes","KB","MB","GB","TB","PB","ZB","YB");
		$bytes = filesize($file);
		foreach(array_reverse($Unit, true) as $key => $val)
		if (pow(1024,$key)<=$bytes)
		return strval( round(($bytes/pow(1024, $key)), 1) )." ".$val;
	}

	function sortArray ($folders, $files) {
		foreach ($folders as $key => $row) {
			$namesTempfolders[$key]  = strtolower($row[0]);//name
			$timesTempfolders[$key] = $row[3];//filetime
		}
		foreach ($files as $key => $row) {
			$namesTempfiles[$key]  = strtolower($row[0]);//name
			$timesTempfiles[$key] = $row[3];//filetime
		}
		@array_multisort(($this->sortBy==SORT_STRING)?$namesTempfolders:$timesTempfolders, $this->sortOr, $this->sortBy, ($this->sortBy==SORT_NUMERIC)?$namesTempfolders:$timesTempfolders, $this->sortOr, $this->sortBy, $folders);
		@array_multisort(($this->sortBy==SORT_STRING)?$namesTempfiles  :$timesTempfiles,   $this->sortOr, $this->sortBy, ($this->sortBy==SORT_NUMERIC)?$namesTempfiles  :$timesTempfiles,   $this->sortOr, $this->sortBy, $files);
		$folders = array_merge($folders, $files);
		return $folders;
	}

	function RecursiveDirectory ($needle,$dir, $i) {
		$files 	 = array();
		$folders = array();
		$open = opendir($dir);
		
		while ( $i>0 && ($file = readdir($open))!==false ) {
			if ( substr($file,0,1) != "." ){
				$path = $dir."/".$file;
				$filePath = $dir."/".str_replace(array("+",""),array('%20',""),urlencode($file));
				if ( !in_array($path,$this->Ignore) )
				if(!is_dir($path) && in_array($this->getExt($file),$this->Types)){
					if (preg_match("/".join("|",$needle)."/",$file)) {
						array_push($files  , array($file,$filePath,'F',filectime($path),$this->FSConvert($path)) );
					}
				} elseif(is_dir($path)) {
					if (preg_match("/".join("|",$needle)."/",$file)) {
						array_push($folders, array($file,$path,'D',filectime($path),0) );
					}
					list($rFolder,$rFiles) = $this->RecursiveDirectory($needle, $path, ($i-1) );
					$folders = array_merge($folders, $rFolder);
					$files 	 = array_merge($files,   $rFiles);
				}
			}
		}
		return array($folders,$files);
	}

	function getSearch ($needle=array(),$dir='.') {
		global $subdirLevel;
		$files   = array();
		$folders = array();
		
		list($rFolder,$rFiles) = $this->RecursiveDirectory($needle, $dir, $subdirLevel);
		$folders = array_merge($folders, $rFolder);
		$files 	 = array_merge($files,   $rFiles);
		$folders = $this->sortArray($folders, $files);
		array_unshift($folders, array(' » Search ',null,'S',0,0) );
		return $folders;
	}

	function getArray ($dir='.') {
		global $backwardsFolders;
		$files = array();
		$folders = array();
		$open = opendir($dir);

		while (($file = readdir($open))!==false){
			if ( substr($file,0,1) != "." || ($backwardsFolders && $file == "..") ){
				$path = $dir."/".$file;
				$filePath = $dir."/".str_replace(array("+",""),array('%20',""),urlencode($file));
				if ( !in_array($path,$this->Ignore) )
				if(!is_dir($path) && in_array($this->getExt($file),$this->Types)){
					array_push($files  , array($file,$filePath,'F',filectime($path),$this->FSConvert($path)) );
				} elseif(is_dir($path)) {
					array_push($folders, array(($file=='..')?'<< backward Folder':$file,$path,'D',filectime($path),0) );
				}
			}
		}
		return $this->sortArray($folders, $files);
	}



}

// Start Class
(new FFinit())->start();




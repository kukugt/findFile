<?php
// Sort Order
$Sort		= "name";		// name or date
$SortOrder	= "ascending";	// ascending or descending

$subdirLevel			= 5;		// Set level of subfolders when searching
$showContent_findFile	= false; 	// Enabling this option displays the files of FindFile
$backwardsFolders 		= false; 	// WARNING: Enabling this option can give access to whole HD 
$viewFile_highlight 	= false; 	// Enabling this option displays the source code of any document
//Files and Folders
$ignore = array(
	"example/Test/otherExample.txt"
);

//file extensions
$imgType	= array("gif","jpg","bmp","png");
$embedType	= array("mp3","mpg","avi");
$textType	= array("html","htm","txt","css");
$scriptType	= array("php","asp","js");
$otherType	= array("md");
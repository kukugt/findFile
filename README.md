findFile
==================================================

FindFile is a search engine for files and folders to use with your Localhost index.php.  
The idea is to navigate through columns and search, also view the selected files.


Browser Support
--------------------------------------
findFile was not tested in all browsers and may have some issues.

<table>
  <tr>
    <th>Browser</th>
    <th>Version</th>
    <th>Platform</th>
    <th>Tested</th>
  </tr>
  <tr>
    <td>Chrome</td>
    <td>31+</td>
    <td>MAC / PC</td>
    <td>True</td>
  </tr>
  <tr>
    <td>Safari</td>
    <td>7+</td>
    <td>MAC / PC</td>
    <td>True</td>
  </tr>
  <tr>
    <td>Firefox</td>
    <td>25+</td>
    <td>MAC </td>
    <td>False</td>
  </tr>
  <tr>
    <td>Opera</td>
    <td>18+</td>
    <td>MAC </td>
    <td>True</td>
  </tr>
  <tr>
    <td>Internet Explorer</td>
    <td>10</td>
    <td>PC</td>
    <td>False</td>
  </tr>
</table>


Libraries
--------------------------------------
[Mootools](https://github.com/mootools)  
[Hyperlight](https://code.google.com/p/hyperlight/)  


Structure
--------------------------------------
```
|index.php
[findFile]
+---[css]
|	+---|findFile.css
|
+---[js]
|	+---|findFile.js
|		|mootools.js
|		|More.js
|		|Nested.js
|
+---[img]
|	+---|apple-touch-icon.png
|		|ios-startup-320x460.png
|		|arrow.png
|		|favicon.png
|
+---[hyperlight]
	+---|hyperlight.php
		|preg_helper.php
		[colors]
		+---|vibrant-ink.css
		|	|zenburn.css
		|
		[languages]
		+---|cpp.php
			|csharp.php
			|css.php
			|iphp.php
			|php.php
			|python.php
			|vb.php
			|xml.php
			|filetypes
```



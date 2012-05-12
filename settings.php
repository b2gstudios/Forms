<?php
//Database settings
$username="root"; //database username
$password="root"; //database password
$hostname="localhost"; //host ip address. Usually "localhost"
$databasename="forms"; //Name of the Database you've created

//Admin login settings
define ("admin_username","admin");
define ("admin_password","admin");

define ("script_folder","dev/forms/"); //The folder you have uploaded the gallery files

////////////////////////////////////////////////////////////////
///////////Edit below this line only for customization//////////
////////////////////////////////////////////////////////////////

//If you are not sure, please do not change these settings.
$URL = $_SERVER['HTTP_HOST'];
$ROOT = $_SERVER['DOCUMENT_ROOT'];

define ("site_address","http://".$URL."/"); //Example: http://www.google.com/
define ("site_root","".$ROOT."/"); //Example: /home/mywebsite/public_html/

//Define the folders
//If you are not sure, please do not change these settings.
define ("javascript_folder",script_folder."js/"); //The folder containing the javascript files: PrettyPhoto, ajaxroutine.js, interface.js, jquery-1.3.2.js
define ("css_folder",script_folder."css/"); //The folder containing the css files: style.css

define ("includes_folder",script_folder."includes/"); //The folder containing the include files: class.upload.php, functions.php
define ("admin_folder",script_folder."admin/"); //The folder containing the admin files: galleries.php, images.php, image.php, includes.php, index.php

define ("ajaxcalls_folder",script_folder."admin/ajaxcalls/"); //The folder containing the ajax call files: loading.php, order-galleries.php, order-images.php

define ("static_images_folder",script_folder."images/"); ////The folder containing the statix images: delete.gif, edit.gif, loading.gif, tick.gif

?>
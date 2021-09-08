<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once ("articlemodule/LibraryHeader.php");
require_once ("articlemodule/searchArticles.php");
$article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
$objArticles = new searchArticles();
$objArticles->savetxtFile();
header("Location: ./bib_files/johs".$article_id.".txt");
?>
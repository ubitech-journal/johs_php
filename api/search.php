<?php 
require_once ("articlemodule/LibraryHeader.php");
require_once ("articlemodule/searchArticles.php");
$objArticles = new searchArticles();
$org_id = 187;
$input = file_get_contents('php://input');
$data = json_decode($input);
 $objArticles->searchArticlesAll($org_id, $data);
?>
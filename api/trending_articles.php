<?php
require_once ("articlemodule/LibraryHeader.php");
require_once ("articlemodule/searchArticles.php");
$objArticles = new searchArticles();
$org_id = 187;
if(isset($_REQUEST['getTrending_articles'])){
	$objArticles->getTrending_articles($org_id);
}

if(isset($_REQUEST['getTrending_articlesApi'])){
	$objArticles->getTrending_articlesApi($org_id);
}
?>
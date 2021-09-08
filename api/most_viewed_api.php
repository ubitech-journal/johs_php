<?php
require_once ("articlemodule/LibraryHeader.php");
require_once ("articlemodule/searchArticles.php");
$objArticles = new searchArticles();
$org_id = 187;
if(isset($_REQUEST['getMostViewArticleApi'])){
	$objArticles->getMostViewArticleApi($org_id);
}

if(isset($_REQUEST['getMostViewedArticleApi'])){
	$objArticles->getMostViewedArticleApi($org_id);
}
?>
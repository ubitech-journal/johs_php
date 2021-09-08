<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$org_id = 187;
	$objArticles = new searchArticles();
	$objArticles->getNews($org_id); 
?>
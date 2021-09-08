<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$objArticles = new searchArticles();
	$org_id = 187;
	$objArticles->privacy_polices(5593, $org_id); 
?>
	
<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$objArticles = new searchArticles();
	$org_id = 187;
	$objArticles->trem_services(5594, $org_id); 
?>
	
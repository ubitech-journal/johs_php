<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$objArticles = new searchArticles();
	$org_id = 187;
	$objArticles->getEditorContents(5575, $org_id); 
?>
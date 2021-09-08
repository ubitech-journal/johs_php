<?php
	
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$objArticles = new searchArticles();
	$org_id = 187;
	if(isset($_REQUEST['showArchives'])) {
	$objArticles->showArchives($org_id);
	}

	if(isset($_REQUEST['showArchivesYear'])) {
	$objArticles->showArchivesYear($org_id);
	}
?>
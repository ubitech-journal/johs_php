<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$objArticles = new searchArticles();
	$org_id = 187;
	if(isset($_REQUEST['getEditorsChoice'])){
		$objArticles->getEditorsChoice($org_id);
	}
?>
<?php
require_once ("articlemodule/LibraryHeader.php");
require_once ("articlemodule/searchArticles.php");
	$org_id = 187;
	$objArticles = new searchArticles();
	if(isset($_REQUEST['adbannerid'])){

		$objArticles->bannerid($org_id);
	}

?>
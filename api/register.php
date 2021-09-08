<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	include("phpmailer/class.phpmailer.php");

		$objArticles = new searchArticles();
		$org_id = 187;
		$input = file_get_contents('php://input');
		$data = json_decode($input);
		$objArticles->register($org_id, $data);

	?>
<?php
require_once ("articlemodule/LibraryHeader.php");
require_once ("articlemodule/searchArticles.php");
$objArticles = new searchArticles();
if(isset($_REQUEST['newsAnnouncements'])){
	$objArticles->newsAnnouncements();
}

if(isset($_REQUEST['journamMatrix'])){

	$JournamMatrix = array(["name"=>"Acceptance rate","value"=>"31%"],
		["name"=>"Submission to final decision","value"=>"67 days"],
		["name"=>"Acceptance to publication","value"=>"30 days"],
		["name"=>"CiteScore","value"=>"3.600"],
		["name"=>"Impact Factor","value"=>"2.276"]);

	print_r(json_encode(array("journamMatrix"=>$JournamMatrix)));
}
?>
<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$objArticles = new searchArticles();
	$org_id = 187;
	if(isset($_REQUEST['getCurrentIssueTitle'])){
		$objArticles->getCurrentIssueTitle($org_id);
	}
	if(isset($_REQUEST['currentIssueArticle'])){
		$objArticles->currentIssueArticle($org_id);
	}

	if(isset($_REQUEST['currentIssueStatus'])){
		$objArticles->currentIssueStatus($org_id);
	}

	if(isset($_REQUEST['currentIssueArticleSubject'])){
		$objArticles->currentIssueArticleSubject($org_id);
	}
	if(isset($_REQUEST['currentIssueArticleDetails'])){
		$objArticles->currentIssueArticleDetails($org_id);
	}
?>
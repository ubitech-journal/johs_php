<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	
	$objArticles = new searchArticles();
	$org_id = 187;
	if(isset($_REQUEST['arcIssueArticle'])){
	$objArticles->arcIssueArticle($org_id);
	}

	if(isset($_REQUEST['arcIssueArticleSubject'])){
	$objArticles->arcIssueArticleSubject($org_id);
	}

	if(isset($_REQUEST['arcIssueArticleDetails'])){
	$objArticles->arcIssueArticleDetails($org_id);
	}

	if(isset($_REQUEST['pastIssueStatus'])){
	$objArticles->pastIssueStatus($org_id);
	}
?>

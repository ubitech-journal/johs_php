<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	$org_id = 187;
	$objArticles = new searchArticles();
	
	if(isset($_REQUEST['abstractHeader'])){
		$objArticles->abstractHeader();
	}

	if(isset($_REQUEST['getAbstractDatavalue'])){
		$objArticles->getAbstractDatavalue();
	}

	if(isset($_REQUEST['getArticleMetrics'])){
		$objArticles->getArticleMetrics();
	}

	if(isset($_REQUEST['getTotalMetrics'])){
		$objArticles->getTotalMetrics();
	}

	if(isset($_REQUEST['updateCountView'])){
		$objArticles->updateCountView($org_id);
	}

	if(isset($_REQUEST['updateCountDownload'])){
		$objArticles->updateCountDownload($org_id);
	}

	if(isset($_REQUEST['similarArticle'])){
		$objArticles->similarArticle();
	}

	if(isset($_REQUEST['relatedSearch'])){
		$objArticles->relatedSearch();
	}

	if(isset($_REQUEST['abstractMeta'])){
		$objArticles->abstractMeta();
	}

?>

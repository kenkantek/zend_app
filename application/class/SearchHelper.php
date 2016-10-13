<?php

class SearchHelper
{
	var $contentTable;
	var $otherTable;
	var $newsTable;
	var $dbReader;
	var $lang;
	var $owner;
	var $ownerTable;

	function  __construct($contentTable, $otherTable, $newsTable, $lang='')
	{
		$this->contentTable = $contentTable;
		$this->otherTable	= $otherTable;
		$this->newsTable	= $newsTable;
		$this->dbReader		= Zend_Registry::get('dbReader');
		$this->lang			= $lang;
	}

	function doSearch($text)
	{
		//remember check filter, apply to content page
		$text = $this->dbReader->quote('%'.$text.'%');
		$query = array();


		$query[] = "(SELECT title{$this->lang} AS title,
							content{$this->lang} AS content,
							page_unique_title AS id,
							'content_page' AS object_type,
							'link' AS link
					FROM $this->contentTable
					WHERE title LIKE $text OR content LIKE $text
					ORDER BY title
					LIMIT 10)";


		//for news
		$query[] = "(SELECT title{$this->lang} AS title,
							content{$this->lang} AS content,
							id AS id,
							'news_page' AS object_type,
							'link' AS link
					FROM {$this->newsTable}
					WHERE title LIKE $text OR content LIKE $text
					ORDER BY title
					LIMIT 10)";

		//for other pages
		$query[] = "(SELECT title{$this->lang} AS title,
							content{$this->lang} AS content,
							id AS id,
							'other_page' AS object_type,
							link AS link
					FROM {$this->otherTable}
					WHERE title LIKE $text OR content LIKE $text
					ORDER BY title
					LIMIT 10)";

		$query = implode(" UNION ", $query);

		return $this->dbReader->fetchAll($query);
	}
}
<?php
/*
 *
 * class helper for bulding menu
 *
 */

class FrontMenuHelper
{
	var $table;

	function __construct($table, $lang='')
	{
		$this->table = $table;
		$this->lang = $lang;
	}

	/**
	 * get main page
	 * @return <array>
	 */
	public function getMainMenu()
	{

		$dbReader	= Zend_Registry::get('dbReader');
		$query		= "	SELECT	id,
								title{$this->lang} AS title,
								content{$this->lang} AS content,
								page_unique_title
						FROM ".$this->table."
						WHERE parent_id = 0
						ORDER BY order_num, id ";

		return		$dbReader->fetchAll($query);
	}

	/**
	 * get subpage
	 * @return <array>
	 */
	public function getSubPage($parentId)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$query		= "	SELECT	id,
								title{$this->lang} AS title,
								content{$this->lang} AS content,
								page_unique_title
						FROM ".$this->table."
						WHERE parent_id = $parentId
						ORDER BY order_num, id ";

		return		$dbReader->fetchAll($query);
	}

	public function getSubPageByParentUniqueTitle($parentUniqueTitle)
	{
		$dbReader	= Zend_Registry::get('dbReader');

		$query		= "	SELECT	id,
								title{$this->lang} AS title,
								content{$this->lang} AS content,
								page_unique_title
						FROM {$this->table}
						WHERE parent_id IN
							(
								SELECT id
								FROM {$this->table}
								WHERE page_unique_title = {$dbReader->quote($parentUniqueTitle)}
							)
						ORDER BY order_num, id ";

		return		$dbReader->fetchAll($query);
	}

	public function getPageById($rowId)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$query		= "	SELECT	id,
								title{$this->lang} AS title,
								content{$this->lang} AS content,
								page_unique_title
						FROM ".$this->table."
						WHERE id = $rowId ";

		return		$dbReader->fetchRow($query);
	}

	public function getPageByTitle($unique_title)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$query		= "	SELECT	id,
								title{$this->lang} AS title,
								content{$this->lang} AS content,
								page_unique_title,
								parent_id
						FROM ".$this->table."
						WHERE page_unique_title = {$dbReader->quote($unique_title)}";

		return		$dbReader->fetchRow($query);
	}

	public function getMediaLink($uniqueTitle)
	{
		$link = array(
			'image-gallery'	=> FLICKR_URL,
			'video-gallery'	=> YOUTUBE_URL,
			'facebook'		=> FACEBOOK_URL,
			'twitter'		=> TWITTER_URL
		);

		return $link[$uniqueTitle];
	}
}

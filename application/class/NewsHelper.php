<?php
/*
 *
 * class helper for operate with news
 *
 */

class NewsHelper
{
	var $table;
	var $lang;
	const DESCRIPTION_COUNT = 90;
	public $paging;

	function __construct($table, $lang='')
	{
		$this->table = $table;
		$this->lang = $lang;
	}

	function getLatestNews($limit = 4)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$query		= "	SELECT	id,
								date AS date_order,
								DATE_FORMAT(date,'%D %M %Y') AS date,
								title{$this->lang} AS title,
								content{$this->lang} AS content
						FROM ".$this->table."
						ORDER BY date_order DESC , id DESC
						LIMIT $limit ";

		return		$dbReader->fetchAll($query);
	}

	function buildDescription($content, $length = NewsHelper::DESCRIPTION_COUNT)
	{
		$temp = substr($content, 0, $length);
		$temp = $this->strip_html_tags($temp);
		$temp = explode(" ", $temp);
		unset($temp[count($temp)-1]);

		return implode(" ", $temp)."...";
	}

	/**
 * Remove HTML tags, including invisible text such as style and
 * script code, and embedded objects.  Add line breaks around
 * block-level tags to prevent word joining after tag removal.
 */
	function strip_html_tags($text)
	{
		$text = preg_replace(
			array(
			  // Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
			  // Add line breaks before and after blocks
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text );
		return strip_tags($text);
	}

	function buildDescriptionHome($content, $length = 450)
	{
		$temp = substr($content, 0, $length);
		$temp = $this->strip_html_tags_home($temp);
		$temp = explode(" ", $temp);
		unset($temp[count($temp)-1]);

		return implode(" ", $temp)."...";
	}

	function strip_html_tags_home($text)
	{
		$text = preg_replace(
			array(
			  // Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
			  // Add line breaks before and after blocks
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				" ", " ", " ", " ", " ", " ",
				" ", " ",
			),
			$text );
		return $text;
	}

	function getList($page = 1)
	{
		$dbReader = Zend_Registry::get('dbReader');
		$current_page = $page;

    	$query = "SELECT COUNT(1) as total FROM {$this->table}";

    	$row = $dbReader->fetchRow($query);
    	$total_row = $row['total'];

    	if ($total_row != 0)
    	{
    	    $total_page = ceil($total_row/FRONT_END_NEWS_ROW_PER_PAGE);
    		if ($current_page > $total_page)
    		{
    			$current_page = $total_page;
    		}
	        $offset = ( $current_page - 1 ) * FRONT_END_NEWS_ROW_PER_PAGE;
            $limit = FRONT_END_NEWS_ROW_PER_PAGE;

            $query		= "	SELECT	id,
								date AS date_order,
								DATE_FORMAT(date,'%D %M %Y') AS date,
								title{$this->lang} AS title,
								content{$this->lang} AS content
							FROM ".$this->table."
							ORDER BY date_order DESC , id DESC
							LIMIT $offset,$limit";

            $results = $dbReader->fetchAll($query);
            $this->paging = PagingHelper::getPagingFront($total_page, $current_page);

			return $results;
    	}
	}

	function getContentById($id)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$id			= $dbReader->quote($id);
		$query		= "	SELECT	id,
								DATE_FORMAT(date,'%D %M %Y') AS date,
								title{$this->lang} AS title,
								content{$this->lang} AS content
						FROM ".$this->table."
						WHERE id = $id";

		return		$dbReader->fetchRow($query);
	}

	function getRelatedNews($id, $limit = 5)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$query		= "	SELECT	id,
								date AS date_order,
								DATE_FORMAT(date,'%D %M %Y') AS date,
								title{$this->lang} AS title,
								content{$this->lang} AS content
						FROM ".$this->table."
						WHERE id > $id
						ORDER BY date_order DESC , id DESC
						LIMIT $limit ";

		return		$dbReader->fetchAll($query);
	}

}


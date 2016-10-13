<?php
class OtherPageHelper
{
	var $table;
	var $dbReader;

	function __construct($table, $lang='')
	{
		$this->table = $table;
		$this->dbReader	= Zend_Registry::get('dbReader');
		$this->lang = $lang;
	}

	function getDetail($id)
	{
		$dbReader	= Zend_Registry::get('dbReader');
		$id			= $dbReader->quote($id);
		$query		= "	SELECT	id,
								title{$this->lang} AS title,
								content{$this->lang} AS content,
								link
						FROM ".$this->table."
						WHERE id = $id ";

		return		$dbReader->fetchRow($query);
	}

	function buildDescription($content, $length = 120)
	{
		$temp = mb_substr($content, 0, $length,'UTF-8');
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

	//------------OTHER PAGE----------------------
	function searchOtherPage()
    {
        $query      = "SELECT id,
							title,
							type,
							title_fr,
							link,
							page_unique_title,
							DATE_FORMAT(date_created,'%d-%m-%Y') as date_created,
							DATE_FORMAT(date_modified,'%d-%m-%Y') as date_modified
                        FROM {$this->table}
						ORDER BY id
						";

        return  $this->dbReader->fetchAll($query);
    }

	function getDetailOtherPage($id)
    {
        $id = $this->dbReader->quote($id);
        $query = "SELECT {$this->table}.*
                    FROM {$this->table}
                    WHERE id = $id ";

        return $this->dbReader->fetchRow($query);
    }

	function getDetailByUniqueTitle($title)
    {
        $title = $this->dbReader->quote($title);
        $query = "SELECT {$this->table}.*
                    FROM {$this->table}
                    WHERE page_unique_title = $title ";

        return $this->dbReader->fetchRow($query);
    }

	function updateOtherPage($id, $data)
    {
        //no need use transaction
		try
        {
            $this->dbReader->update($this->table, $data, "id = $id");
			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not update page. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function isPageTitleUnique($page_unique_title, $current_unique_title = '')
    {
		$page_unique_title  = $this->dbReader->quote($page_unique_title);
		$row = $this->dbReader->fetchRow("SELECT page_unique_title FROM {$this->table} WHERE page_unique_title = $page_unique_title");

		if($row['page_unique_title'] == '' || ($current_unique_title != '' && $row['page_unique_title'] == $current_unique_title)) return true;

		return false;
    }

    function buildUniqueTitle($title)
    {
        $title  = trim(strtolower($title));
		$title	= str_replace(array('?','.',',',':','"','\\','/'), '', $title);
		$title	= str_replace(array('-'), ' ', $title);
        $temp   = explode(" ",$title);
        foreach($temp as $key => $item)
        {
            if(empty($item)) unset($temp[$key]);
        }

        return implode("-", $temp);
    }

    function trimTitle($title)
    {
        $title  = trim($title);
        $temp   = explode(" ",$title);
        foreach($temp as $key => $item)
        {
            if(empty($item)) unset($temp[$key]);
        }

        return implode(" ", $temp);
    }

	function add($data)
    {
        try
        {
            $this->dbReader->insert($this->table, $data);
            return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not add other page. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function delete($id)
    {
        $id = $this->dbReader->quote($id);
        try
        {
            //remove this, don't have child
            $this->dbReader->delete($this->table, "id = $id");

            return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not delete other page. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

}
<?php
/*
 *
 * class helper for operate with news for back-end
 *
 */

class BackEndNewsHelper
{
	var $table;
	const DESCRIPTION_COUNT = 120;
	public $paging;
	var $dbReader;

	function __construct($table)
	{
		$this->table = $table;
		$this->dbReader = Zend_Registry::get('dbReader');
	}

	function getLatestNews($limit = 2)
	{
		$query		= "	SELECT	id,
								date as date_order,
								DATE_FORMAT(date,'%b %D, %Y') as date,
								title_".$this->lang." as title,
								content_".$this->lang." as content
						FROM ".$this->table."
						ORDER BY date_order DESC , id DESC
						LIMIT $limit ";

		return		$this->dbReader->fetchAll($query);
	}

	function buildDescription($content)
	{
		$temp = substr($content, 0, NewsHelper::DESCRIPTION_COUNT);
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

	function getList($page = 1, $condition = null)
	{
		try
        {
			if($condition != null)
			{
				$where = array();
				foreach($condition as $item)
				{
					$quotedText = $this->dbReader->quote($item['value']);
					$where[] = "{$this->table}.{$item['field']} {$item['operator']} $quotedText";
				}
				$condition  = implode(" AND ", $where);
			}

			$current_page = $page;

			if($condition != '') $query = "SELECT COUNT(1) as total FROM ".$this->table." WHERE $condition";
			else				 $query = "SELECT COUNT(1) as total FROM ".$this->table;
			$row = $this->dbReader->fetchRow($query);
			$total_row = $row['total'];

			if ($total_row != 0)
			{
				$total_page = ceil($total_row/ADMIN_ROW_PER_PAGE);
				if ($current_page > $total_page)
				{
					$current_page = $total_page;
				}
				$offset = ( $current_page - 1 ) * ADMIN_ROW_PER_PAGE;
				$limit = ADMIN_ROW_PER_PAGE;

				if($condition == null)
				{
					$query		= "	SELECT	id,
										date as date_order,
										DATE_FORMAT(date,'%d-%m-%Y') as date,
										DATE_FORMAT(date_created,'%d-%m-%Y') as date_created,
										DATE_FORMAT(date_modified,'%d-%m-%Y') as date_modified,
										title,
										title_fr
									FROM ".$this->table."
									ORDER BY date_order DESC , id DESC
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT	id,
										date as date_order,
										DATE_FORMAT(date,'%d-%m-%Y') as date,
										DATE_FORMAT(date_created,'%d-%m-%Y') as date_created,
										DATE_FORMAT(date_modified,'%d-%m-%Y') as date_modified,
										title,
										title_fr
									FROM ".$this->table."
									WHERE $condition
									ORDER BY date_order DESC , id DESC
									LIMIT $offset,$limit";
				}

				$results = $this->dbReader->fetchAll($query);
				$this->paging = PagingHelper::getPagingBackEnd($total_page, $current_page);

				return $results;
			}
		}
		catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not get new lists. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
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
            return $insert = $this->dbReader->insert($this->table, $data);
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not add news. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function update($id, $data)
    {
        try
        {
            $this->dbReader->update($this->table, $data, "id = $id");
			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not update news. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function delete($id)
    {
        $id = $this->dbReader->quote($id);

        try
        {
            return $this->dbReader->delete($this->table, "id = $id");
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not delete news. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function getDetail($id)
	{
		$id			= $this->dbReader->quote($id);
		$query		= "	SELECT	*,
								DATE_FORMAT(date,'%d-%m-%Y') as date_formatted
						FROM ".$this->table."
						WHERE id = $id";

		return		$this->dbReader->fetchRow($query);
	}

}


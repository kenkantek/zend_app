<?php
/*
 *
 * class helper for operate with slideshow
 */

class BannerHelper
{
	var $table;
	public $paging;
	var $dbReader;
	var $folderPath;
	var $folderUrl;

	function __construct($table, $folder_path, $folder_url)
	{
		$this->table		= $table;
		$this->dbReader		= Zend_Registry::get('dbReader');
		$this->folderPath	= $folder_path;
		$this->folderUrl	= $folder_url;
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
					$query		= "	SELECT *
									FROM ".$this->table."
									ORDER BY order_num
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT *
									FROM ".$this->table."
									WHERE $condition
									ORDER BY order_num
									LIMIT $offset,$limit";
				}

				$results = $this->dbReader->fetchAll($query);
				$this->paging = PagingHelper::getPagingBackEnd($total_page, $current_page);

				return $results;
			}
		}
		catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot get banner list. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
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
            Zend_Registry::get('logger')->emerg('Cannot add banner. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function update($id, $data, $replaceFile)
    {
        try
        {
            //get detail
			$row = $this->getDetail($id);

			//if replace, remove file
			if($replaceFile)
			{
				//remove file
				@unlink($this->folderPath.$row['image']);
			}
			else //else use current
			{
				$data['image'] = $row['image'];
			}

			$this->dbReader->update($this->table, $data, "id = $id");
			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot update banner. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function delete($id)
    {
        //get detail
		$row = $this->getDetail($id);

        try
        {
            $delete = $this->dbReader->delete($this->table, "id = $id");
			if($delete)
			{
				//remove file
				@unlink($this->folderPath.$row['image']);
				return true;
			}

			return false;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot delete banner. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function reOrder($request)
    {
		$id     = $request->getParam('id');
		$type   = $request->getParam('type');

		$results = $this->dbReader->fetchAll("SELECT id, order_num
                                                FROM {$this->table}
                                                ORDER BY order_num ");
		$count = count($results);

		//only 1
		if ($count == 1)
		{
            return true;
		}

		$is_changed = true;
		for($i = 0 ; $i < $count ; $i++)
		{
			if($results[$i]['id'] == $id)
			{
				if($type == 'up')
				{
					//mininum
					if ($i == 0)
					{
						$is_changed = false;
						break;
					}

					$temp = $results[$i]['id'];
					$results[$i]['id'] = $results[$i - 1]['id'];
					$results[$i - 1]['id'] = $temp;
				}
				else
				{
					//maxixmum
					if ($i == ($count - 1))
					{
						$is_changed = false;
						break;
					}

					$temp = $results[$i]['id'];
					$results[$i]['id'] = $results[$i + 1]['id'];
					$results[$i + 1]['id'] = $temp;

				}
				break;
			}
		}

		if(!$is_changed)
		{
            return true;
		}

		$i = 0;
		try
		{
			$this->dbReader->beginTransaction();
			foreach ($results as $row)
			{
				$data = array('order_num' => $i);
				$this->dbReader->update($this->table, $data, "id = {$row['id']}");
				$i++;
			}
			$this->dbReader->commit();
			return true;
		}
		catch (Exception $e)
		{
			$this->dbReader->rollback();
            Zend_Registry::get('logger')->emerg('Can not reorder banner record. Data '.print_r($results,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());

		}
	}

	function getDetail($id)
	{
		$id			= $this->dbReader->quote($id);
		$query		= "	SELECT	*
						FROM ".$this->table."
						WHERE id = $id";

		return		$this->dbReader->fetchRow($query);
	}

	function getImageUrl($image)
	{
		return $this->folderUrl.$image;
	}

	function doUploadImage($fileObject)
	{
		return FileHelper::checkFileForFlashUpload($fileObject, $this->folderPath);
	}

	function getSlide()
	{
		$query = "SELECT *
				FROM {$this->table}
				ORDER BY order_num ";
		$result = $this->dbReader->fetchAll($query);

		//build gallery
		$temp	= array();
		$pages	= array();

		$i = 1;
		foreach($result as $row)
		{
			if($row['url'] != '')
			{
				$temp[] = "<div><a href='{$row['url']}'><img src='{$this->getImageUrl($row['image'])}' border='0' alt='{$row['url']}' width='512' height='343' /></a></div>";
			}
			else
			{
				$temp[] = "<div><a href='#'><img src='{$this->getImageUrl($row['image'])}' border='0' alt='{$row['url']}' width='512' height='343' /></a></div>";
			}

			$pages[] = "<li><a href='#' rel='$i'>$i</a></li>";
			$i++;
		}

		return array(
			'slides'	=> implode('', $temp),
			'pages'		=> implode('', $pages)
		);

	}

}


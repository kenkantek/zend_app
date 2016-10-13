<?php
/*
 *
 * class helper for operate with Gallery
 */

class GalleryHelper
{
	var $albumTable;
	var $imageTable;
	public $paging;
	var $dbReader;
	var $folderPath;
	var $folderUrl;

	function __construct($albumTable, $imageTable, $folder_path, $folder_url, $lang='')
	{
		$this->albumTable	= $albumTable;
		$this->imageTable	= $imageTable;
		$this->dbReader		= Zend_Registry::get('dbReader');
		$this->folderPath	= $folder_path;
		$this->folderUrl	= $folder_url;
		$this->lang			= $lang;
	}

	function getListAlbum($page = 1, $condition = null)
	{
		try
        {
			if($condition != null)
			{
				$where = array();
				foreach($condition as $item)
				{
					$quotedText = $this->dbReader->quote($item['value']);
					$where[] = "{$this->albumTable}.{$item['field']} {$item['operator']} $quotedText";
				}
				$condition  = implode(" AND ", $where);
			}

			$current_page = $page;

			if($condition != '') $query = "SELECT COUNT(1) as total FROM ".$this->albumTable." WHERE $condition";
			else				 $query = "SELECT COUNT(1) as total FROM ".$this->albumTable;
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
									FROM ".$this->albumTable."
									ORDER BY order_num
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT *
									FROM ".$this->albumTable."
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
            Zend_Registry::get('logger')->emerg('Cannot get album list. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
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

    function addAlbum($data)
    {
        try
        {
            return $insert = $this->dbReader->insert($this->albumTable, $data);
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot add album. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function updateAlbum($id, $data, $replaceFile)
    {
        try
        {
            //get detail
			$row = $this->getDetailAlbum($id);

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

			$this->dbReader->update($this->albumTable, $data, "id = $id");
			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot update album. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function deleteAlbum($id)
    {
        //get detail
		$row = $this->getDetailAlbum($id);

        try
        {
            $delete = $this->dbReader->delete($this->albumTable, "id = $id");
			if($delete)
			{
				//remove file
				@unlink($this->folderPath.$row['image']);

				$childImage = $this->getImageListofAlbum($id);
				foreach($childImage as $child)
				{
					$this->dbReader->delete($this->imageTable, "id = {$child['id']}");

					//remove file
					@unlink($this->folderPath.$child['image']);
				}

				return true;
			}

			return false;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot delete album. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function reOrderAlbum($request)
    {
		$id     = $request->getParam('id');
		$type   = $request->getParam('type');

		$results = $this->dbReader->fetchAll("SELECT id, order_num
                                                FROM {$this->albumTable}
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
				$this->dbReader->update($this->albumTable, $data, "id = {$row['id']}");
				$i++;
			}
			$this->dbReader->commit();
			return true;
		}
		catch (Exception $e)
		{
			$this->dbReader->rollback();
            Zend_Registry::get('logger')->emerg('Can not reorder album record. Data '.print_r($results,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());

		}
	}

	function getDetailAlbum($id)
	{
		$id			= $this->dbReader->quote($id);
		$query		= "	SELECT	*
						FROM ".$this->albumTable."
						WHERE id = $id";

		return		$this->dbReader->fetchRow($query);
	}


	function getImageListofAlbum($id_album)
	{
		$query = "SELECT * FROM ".$this->imageTable." WHERE id_album = $id_album";

		return $this->dbReader->fetchAll($query);
	}

	function getImageUrl($image)
	{
		return $this->folderUrl.$image;
	}

	function doUploadImage($fileObject)
	{
		return FileHelper::checkFileForFlashUpload($fileObject, $this->folderPath);
	}

	function doUploadImageAlbumCover($fileObject)
	{
		return FileHelper::checkFileForFlashUpload($fileObject, $this->folderPath);
	}

	function getStatusText($status)
	{
		$text = array(
			'0'	=> 'Disabled',
			'1'	=> 'Active'
		);

		return $text[$status];
	}

	function getComboStatus($select = null, $name = 'status')
	{
		$text = array(
			'0'	=> 'Disabled',
			'1'	=> 'Active'
		);

		$out = array("<select id='$name' name='$name'>");

		foreach($text as $value => $text)
		{
			if($value == $select)
			{
				$out[] = "<option value='$value' selected='selected'>$text</option>";
			}
			else
			{
				$out[] = "<option value='$value' >$text</option>";
			}
		}

		$out[] = '</select>';

		return implode('', $out);
	}

	function getComboAlbum($select = null, $name = 'albumSearch', $useRoot = true)
	{
		$query	= "SELECT * FROM $this->albumTable ORDER BY name";
		$result = $this->dbReader->fetchAll($query);

		$out = array();

		$out[] = "<select id='$name' name='$name'>";

		if($useRoot)
		{
			$out[] = "<option value=''>--</option>";
		}

		foreach($result as $row)
		{
			$value	= $row['id'];
			$text	= $row['name'];

			if($value == $select)
			{
				$out[] = "<option value='$value' selected='selected'>$text</option>";
			}
			else
			{
				$out[] = "<option value='$value' >$text</option>";
			}
		}

		$out[] = '</select>';

		return implode('', $out);
	}

	function getListImage($page = 1, $condition = null)
	{
		try
        {
			if($condition != null)
			{
				$where = array();
				foreach($condition as $item)
				{
					$quotedText = $this->dbReader->quote($item['value']);
					$where[] = "{$this->imageTable}.{$item['field']} {$item['operator']} $quotedText";
				}
				$condition  = implode(" AND ", $where);
			}

			$current_page = $page;

			if($condition != '') $query = "SELECT COUNT(1) AS total FROM ".$this->imageTable." WHERE $condition";
			else				 $query = "SELECT COUNT(1) AS total FROM ".$this->imageTable;
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
					$query		= "	SELECT {$this->imageTable}.*, {$this->albumTable}.name AS albumName
									FROM $this->imageTable
										INNER JOIN $this->albumTable
											ON {$this->albumTable}.id = {$this->imageTable}.id_album
									ORDER BY {$this->imageTable}.order_num, {$this->imageTable}.date_created
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT {$this->imageTable}.*, {$this->albumTable}.name AS albumName
									FROM $this->imageTable
										INNER JOIN $this->albumTable
											ON {$this->albumTable}.id = {$this->imageTable}.id_album
									WHERE $condition
									ORDER BY {$this->imageTable}.order_num, {$this->imageTable}.date_created
									LIMIT $offset,$limit";
				}

				$results = $this->dbReader->fetchAll($query);
				$this->paging = PagingHelper::getPagingBackEnd($total_page, $current_page);

				return $results;
			}
		}
		catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot get image list. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
	}

	function addImage($data)
    {
        try
        {
            return $insert = $this->dbReader->insert($this->imageTable, $data);
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot add image. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function updateImage($id, $data, $replaceFile)
    {
        try
        {
            //get detail
			$row = $this->getDetailImage($id);

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

			$this->dbReader->update($this->imageTable, $data, "id = $id");
			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot update image. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function deleteImage($id)
    {
        //get detail
		$row = $this->getDetailImage($id);

        try
        {
            $delete = $this->dbReader->delete($this->imageTable, "id = $id");
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
            Zend_Registry::get('logger')->emerg('Cannot delete image. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function getDetailImage($id)
	{
		$id			= $this->dbReader->quote($id);
		$query		= "	SELECT	*
						FROM ".$this->imageTable."
						WHERE id = $id";

		return		$this->dbReader->fetchRow($query);
	}

	function reOrderImage($request)
    {
		$id			= $request->getParam('id');
		$type		= $request->getParam('type');
		$idAlbum	= $request->getParam('idAlbum');

		$results = $this->dbReader->fetchAll("SELECT id, order_num
                                                FROM {$this->imageTable}
												WHERE id_album = '$idAlbum'
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
				$this->dbReader->update($this->imageTable, $data, "id = {$row['id']}");
				$i++;
			}
			$this->dbReader->commit();
			return true;
		}
		catch (Exception $e)
		{
			$this->dbReader->rollback();
            Zend_Registry::get('logger')->emerg('Can not reorder image record. Data '.print_r($results,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());

		}
	}

	//--------------- front--------------
	function getListAllbumFrontEnd()
	{
		$query	= "SELECT * FROM $this->albumTable WHERE status = 1 ORDER BY order_num ";
		$results = $this->dbReader->fetchAll($query);

		return $results;
	}

	function getListImageFrontEnd($idAlbum)
	{
		$query	= "SELECT * FROM $this->imageTable
					WHERE id_album = ".$this->dbReader->quote($idAlbum)."
					ORDER BY order_num,date_created ";
		$results = $this->dbReader->fetchAll($query);

		return $results;
	}
}


<?php
class ContentPageHelper
{
    var $table;
    var $dbReader;

	function __construct($table)
	{
		$this->table            = $table;
        $this->dbReader			= Zend_Registry::get('dbReader');
	}

    function getComboParent($haveRoot, $style, $combo_id = 'parent_id', $selelected = null)
    {
        $query      = "(SELECT * FROM {$this->table}
                        WHERE parent_id = 0
                        ORDER BY order_num)";

        $parents = $this->dbReader->fetchAll($query);

        $temp   = array("<select id='$combo_id' name='$combo_id' style='$style'>");
        if($haveRoot)
        {
            if($selelected == 0)
            {
                $temp[] = "<option selected='selected' value='0'>ROOT</option>";
            }
            else
            {
                $temp[] = "<option value='0'>ROOT</option>";
            }
        }
        foreach($parents as $item)
        {
            if($selelected != null && $item['id'] == $selelected)
            {
                $temp[] = "<option selected='selected' value='{$item['id']}'>{$item['title']}</option>";
            }
            else
            {
                $temp[] = "<option value='{$item['id']}'>{$item['title']}</option>";
            }
        }
        $temp[] = '</select>';

        return implode('', $temp);
    }

    function searchPage($condition)
    {
        $where = array();
        foreach($condition as $item)
        {
            $quotedText = $this->dbReader->quote($item['value']);
            $where[] = "{$this->table}.{$item['field']} {$item['operator']} $quotedText";
        }
        $condition  = implode(" AND ", $where);
        $query      = "SELECT id,
                        title,
						title_fr,
                        page_unique_title,
                        order_num,
                        {$this->table}.parent_id,
                        if(temp.childs <> '', temp.childs, 0) as childs,
                        DATE_FORMAT(date_created,'%d-%m-%Y') as date_created,
                        DATE_FORMAT(date_modified,'%d-%m-%Y') as date_modified
                        FROM {$this->table}
                        LEFT JOIN (
                                SELECT parent_id, count(1) AS childs
                                FROM {$this->table}
                                GROUP BY parent_id
                        ) AS temp ON {$this->table}.id = temp.parent_id
                        WHERE $condition ORDER BY order_num";
                        //AND page_unique_title NOT IN ('latest-news','image-gallery','video-gallery','facebook','twitter','product-registration','home')";

        return  $this->dbReader->fetchAll($query);
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
            Zend_Registry::get('logger')->emerg('Can not add page. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function delete($id)
    {
        $id = $this->dbReader->quote($id);
        try
        {
            //remove this, don't have child
            $this->dbReader->delete($this->table, "id = $id AND parent_id <> 0");

            return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not delete page. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function getComboParentForModify($id, $style, $combo_id = 'parent_id')
    {
        //level 1 page, no move
        $temp   = array("<select id='$combo_id' name='$combo_id' style='$style'>");
        if($id == 0)
        {
            $temp[] = "<option value='0'>ROOT</option>";
        }
        else
        {
            //we need get parent of this id
            $query      = "(SELECT parent_id FROM {$this->table}
                            WHERE id = $id)";
            $row = $this->dbReader->fetchRow($query);
            $parent_id = $row['parent_id'];

            $query      = "(SELECT * FROM {$this->table}
                            WHERE parent_id = $parent_id)";
            $sameLevel  = $this->dbReader->fetchAll($query);

            foreach($sameLevel as $item)
            {
                if($item['id'] == $id)
                {
                    $temp[] = "<option selected='selected' value='{$item['id']}'>{$item['title']}</option>";
                }
                else
                {
                    $temp[] = "<option value='{$item['id']}'>{$item['title']}</option>";
                }
            }
        }

        $temp[] = '</select>';
        return implode('', $temp);
    }

    function getDetail($id)
    {
        $id = $this->dbReader->quote($id);
        $query = "SELECT {$this->table}.*
                    FROM {$this->table}
                    WHERE id = $id ";

        return $this->dbReader->fetchRow($query);
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
            Zend_Registry::get('logger')->emerg('Can not update page. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    function reOrder($request)
    {
		$id     = $request->getParam('id');
		$type   = $request->getParam('type');

		$row = $this->dbReader->fetchRow("SELECT parent_id FROM {$this->table} WHERE id = $id");

		if(!$row)
		{
            return false;
		}

		$results = $this->dbReader->fetchAll("SELECT id, order_num
                                                FROM {$this->table}
                                                WHERE parent_id = ". $row['parent_id'] ."
                                                ORDER BY order_num");
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
            Zend_Registry::get('logger')->emerg('Can not reorder. Data '.print_r($results,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());

		}
	}
}
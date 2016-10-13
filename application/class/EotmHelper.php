<?php
/*
 * 
 * class helper for operate with eotm
 */

class EotmHelper
{
	var $table;
	public $paging;
	var $dbReader;

	function __construct($table)
	{
		$this->table = $table;
		$this->dbReader = Zend_Registry::get('dbReader');
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
					$query		= "	SELECT *,
										DATE_FORMAT(date_created,'%d-%m-%Y') AS date_created,
										DATE_FORMAT(date_modified,'%d-%m-%Y') AS date_modified
									FROM ".$this->table."
									ORDER BY month DESC , year DESC
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT *,
										DATE_FORMAT(date_created,'%d-%m-%Y') AS date_created,
										DATE_FORMAT(date_modified,'%d-%m-%Y') AS date_modified
									FROM ".$this->table."
									WHERE $condition
									ORDER BY month DESC , year DESC
									LIMIT $offset,$limit";
				}

				$results = $this->dbReader->fetchAll($query);
				$this->paging = PagingHelper::getPagingBackEnd($total_page, $current_page);

				return $results;
			}
		}
		catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot get eotm list. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
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
            Zend_Registry::get('logger')->emerg('Cannot add eotm. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
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
				@unlink(EOTM_FILES_FOLDER.$row['image']);
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
            Zend_Registry::get('logger')->emerg('Cannot update events. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
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
				@unlink(EOTM_FILES_FOLDER.$row['image']);
				return true;
			}

			return false;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Cannot delete events. Id '.$id.'. Error trace :'.$e->getMessage());
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

	function getComboYear($style, $combo_id = 'year', $selelected = null)
    {
        $temp   = array("<select id='$combo_id' name='$combo_id' $style>");

        $currentYear = intval(date('Y'));
		$from	= (EOTM_START_YEAR < $currentYear) ? EOTM_START_YEAR : $currentYear;
		$to		= $currentYear + EOTM_PLUST_END;

		for($i = $from ; $i <= $to ; $i++)
		{
			if($selelected == null)
			{
				if($i == $currentYear)
				{
					$temp[] = "<option selected='selected' value='$i'>$i</option>";
				}
				else
				{
					$temp[] = "<option value='$i'>$i</option>";
				}
			}
			else
			{
				if($i == $selelected)
				{
					$temp[] = "<option selected='selected' value='$i'>$i</option>";
				}
				else
				{
					$temp[] = "<option value='$i'>$i</option>";
				}
			}
		}

		$temp[] = '</select>';

        return implode('', $temp);
    }

	function getComboMonth($style, $combo_id = 'month', $selelected = null)
    {
        $temp   = array("<select id='$combo_id' name='$combo_id' $style>");

        $currentMonth = intval(date('n'));
		$from	= 1;
		$to		= 12;

		for($i = $from ; $i <= $to ; $i++)
		{
			if($selelected == null)
			{
				if($i == $currentMonth)
				{
					$temp[] = "<option selected='selected' value='$i'>$i</option>";
				}
				else
				{
					$temp[] = "<option value='$i'>$i</option>";
				}
			}
			else
			{
				if($i == $selelected)
				{
					$temp[] = "<option selected='selected' value='$i'>$i</option>";
				}
				else
				{
					$temp[] = "<option value='$i'>$i</option>";
				}
			}
		}

		$temp[] = '</select>';

        return implode('', $temp);
    }

	function getListOfYear($year)
	{
		$year		= $this->dbReader->quote($year);
		$query		= "	SELECT	*
						FROM ".$this->table."
						WHERE year = $year
						ORDER BY month DESC " ;

		return	$this->dbReader->fetchAll($query);
	}

	function getMonthName($month)
	{
		$monthName = array(
		'1'		=> 'January',
		'2'		=> 'February',
		'3'		=> 'March',
		'4'		=> 'April',
		'5'		=> 'May',
		'6'		=> 'June',
		'7'		=> 'July',
		'8'		=> 'August',
		'9'		=> 'September',
        '10'	=> 'Octorber',
		'11'	=> 'November',
		'12'	=> 'December'
		);

		return $monthName[$month];
	}

	function getNewestRecordOfCurrentYear()
	{
		$year = date('Y');
		$sql	= "SELECT *
					FROM {$this->table}
					WHERE year = '$year'
					ORDER BY month DESC
					LIMIT 1";

		return $this->dbReader->fetchRow($sql);
	}

}


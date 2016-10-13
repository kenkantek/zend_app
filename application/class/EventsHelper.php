<?php
/*
 * 
 * class helper for operate with events
 */

class EventsHelper
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
					$query		= "	SELECT	id,
										start_date AS date_order,
										DATE_FORMAT(start_date, '%d-%m-%Y') AS start_date,
										DATE_FORMAT(end_date, '%d-%m-%Y') AS end_date,
										DATE_FORMAT(date_created,'%d-%m-%Y') AS date_created,
										DATE_FORMAT(date_modified,'%d-%m-%Y') AS date_modified,
										title
									FROM ".$this->table."
									ORDER BY date_order DESC , id DESC
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT	id,
										start_date AS date_order,
										DATE_FORMAT(start_date, '%d-%m-%Y') AS start_date,
										DATE_FORMAT(end_date, '%d-%m-%Y') AS end_date,
										DATE_FORMAT(date_created,'%d-%m-%Y') AS date_created,
										DATE_FORMAT(date_modified,'%d-%m-%Y') AS date_modified,
										title
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
            Zend_Registry::get('logger')->emerg('Cannot get events list. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
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
            Zend_Registry::get('logger')->emerg('Cannot add events. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
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
            Zend_Registry::get('logger')->emerg('Cannot update events. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
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
            Zend_Registry::get('logger')->emerg('Cannot delete events. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function getDetail($id)
	{
		$id			= $this->dbReader->quote($id);
		$query		= "	SELECT	*,
								DATE_FORMAT(start_date,'%d-%m-%Y') as start_date_formatted,
								DATE_FORMAT(end_date,'%d-%m-%Y') as end_date_formatted,
								DATE_FORMAT(start_date,'%d/%m/%Y') as start_date_formatted_us,
								DATE_FORMAT(end_date,'%d/%m/%Y') as end_date_formatted_us
						FROM ".$this->table."
						WHERE id = $id";

		return		$this->dbReader->fetchRow($query);
	}

	//for front-end
	function getCalendar($month, $year)
	{
		$currentMonth	= date('m');
		$currentYear	= date('Y');
		$currentDay		= date('j');
		$isCurrenMonthAndYear = false;
		
		//if -1 , get current month & current year
		if($month == -1 && $year  == -1)
		{
			$month = $currentMonth;
			$year  = $currentYear;
			$isCurrenMonthAndYear	= true;
		}
		else
		{
			if($month == $currentMonth && $year == $currentYear)
				$isCurrenMonthAndYear	= true;
			else
				$isCurrenMonthAndYear	= false;
		}

		//calculate previous & next
		$prev_year = $year;
		$next_year = $year;

		$prev_month = $month-1;
		$next_month = $month+1;

		if($prev_month == 0)
		{
			$prev_month = 12;
			$prev_year	-= 1;
		}
		
		if($next_month == 13)
		{
			$next_month = 1;
			$next_year	+= 1;
		}

		$timestamp	= mktime(0,0,0,$month,1,$year);
		$maxday		= date("t",$timestamp);
		$monthName	= date("F",$timestamp);
		$thismonth	= getdate($timestamp);
		$startday	= $thismonth['wday'];

		//out data
		$out = "
			<table width='100%' border='0' cellspacing='2' cellpadding='0'>
			<tr>
				<td width='15%' align='left'>
					<a href='#' onclick='getCalendar($prev_month, $prev_year)'>
						<img src='".WEBSITE_URL."images/calleft.gif' />
					</a>
				</td>
				<td width='50%' align='center'>
					<div id='cal_title'>$monthName $year</div>
				</td>
				<td width='15%' align='right'>
					<a href='#' onclick='getCalendar($next_month, $next_year)'>
						<img src='".WEBSITE_URL."images/calright.gif' />
					</a>
				</td>
			</td>
			</tr>
			<tr>
			<td colspan='3' width='100%'>
			<div id='cal_dates'>
			<table width='100%' border='0' cellspacing='2' cellpadding='0'>
				<tr>
				<td width='14%' align='center'>
					<div class='dow'>S</div>
				</td>
				<td width='14%' align='center'>
					<div class='dow'>M</div>
				</td>
				<td width='14%' align='center'>
					<div class='dow'>T</div>
				</td>
				<td width='14%' align='center'>
					<div class='dow'>W</div>
				</td>
				<td width='14%' align='center'>
					<div class='dow'>T</div>
				</td>
				<td width='14%' align='center'>
					<div class='dow'>F</div>
				</td>
				<td align='center'>
					<div class='dow'>S</div>
				</td>
				</tr>";
		
			for ($i=0; $i<($maxday+$startday); $i++)
			{
				if(($i % 7) == 0 ) $out .= "<tr>";
				if($i < $startday) $out .= "<td></td>";
				else
				{
					$day = ($i - $startday + 1);
					$id = $this->isEvents($day, $month, $year);
					if($id !== false)
					{
						if($isCurrenMonthAndYear && $day == $currentDay)
						{
							$out .= "<td align='center' width='14%' class='today'>
										<a href='".WEBSITE_URL."events-detail/".$id."'>$day</a>
									</td>";
						}
						else
						{
							$out .= "<td align='center' width='14%' class='eventday'>
										<a href='".WEBSITE_URL."events-detail/".$id."'>$day</a>
									</td>";
						}
					}
					else
					{
						$out .= "<td align='center' width='14%' class='noeventday'>
										$day
									</td>";
					}
				}
				if(($i % 7) == 6 ) $out .= "</tr>";
			}

		$out .= '</table>
				</div>
				</td>
				</tr>
				</table>';

		return $out;
	}

	function isEvents($day, $month, $year)
	{
		$sqlDate = $year.'-'.$month.'-'.$day;
		
		//get closest event in FUTURE
		$sql	= "SELECT id FROM {$this->table}
					WHERE '$sqlDate' BETWEEN start_date AND end_date
					ORDER BY end_date ASC
					LIMIT 1";
		$row = $this->dbReader->fetchRow($sql);
		
		if ($row['id'] != '') return $row['id'];
		return false;
	}

	function getNewestEvents()
	{
		$today = date('Y-m-d');
		$sql = "SELECT id,
					title,
					DATE_FORMAT(start_date,'%d/%m/%Y') as start_date,
					DATE_FORMAT(end_date,'%d/%m/%Y') as end_date
				FROM {$this->table}
				WHERE end_date >= '$today' ";

		return $this->dbReader->fetchAll($sql);
	}

}


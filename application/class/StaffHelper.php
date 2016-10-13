<?php

class StaffHelper
{
	var $table;
	var $dbReader;
	var $sessionNameSpace;
    public $paging;

	function __construct($table, $sessionNameSpace)
	{
		$this->table = $table;
		$this->dbReader = Zend_Registry::get('dbReader');
		$this->sessionNameSpace = $sessionNameSpace;
	}

	function isEmailExsited($email, $currentEmail = '')
    {
		$email  = $this->dbReader->quote($email);
		$row = $this->dbReader->fetchRow("SELECT email FROM {$this->table} WHERE email = $email");

		if($row['email'] == '' || ($currentEmail != '' && $row['email'] == $currentEmail)) return true;

		return false;
    }

	function isUsernameExsited($username, $currentUsername = '')
    {
		$username  = $this->dbReader->quote($username);
		$row = $this->dbReader->fetchRow("SELECT username FROM {$this->table} WHERE username = $username");

		if($row['username'] == '' || ($currentUsername != '' && $row['username'] == $currentUsername)) return true;

		return false;
    }

	function buildPasswordHash($password)
	{
		return md5($password);
	}

	function add($data)
    {
        try
        {
            $insert = $this->dbReader->insert($this->table, $data);
            return $this->dbReader->lastInsertId();
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not add staff record. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function delete($id)
    {
        $id = $this->dbReader->quote($id);

        try
        {
            $this->dbReader->delete($this->table, "id = $id");
            return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not delete staff. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function sendEmail($body, $subject, $emailReceived, $nameReceived)
    {
        try
		{
			$config = array('auth'		=> 'login',
							'username'	=> MAIL_SENDER,
							'password'	=> MAIL_PASSWORD,
							'port'		=> MAIL_PORT);
            if(TLS !== false)
                $config['ssl']  = 'tls';

			$transport = new Zend_Mail_Transport_Smtp(MAIL_SERVER, $config);

			$mail = new Zend_Mail('UTF-8');
			$mail->setBodyHtml($body);
			$mail->setFrom(MAIL_SENDER);

			$mail->addTo($emailReceived, $nameReceived);

			$mail->setSubject($subject ." ". date('m/d/Y H:i'));
			$mail->send($transport);

			return true;
		}
		catch (Exception $e)
		{
			Zend_Registry::get('logger')->emerg('Can not send email to staff. Body '.print_r($body,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
		}
    }

	//check profile is logged
	function isLogined()
	{
		return Zend_Session::namespaceIsset($this->sessionNameSpace);
	}

	//check login
	function checkLogin($username, $password)
	{
		$username	= $this->dbReader->quote($username);
		$password	= $this->dbReader->quote($this->buildPasswordHash($password));
		$query		= "SELECT * FROM {$this->table}
						WHERE username = $username
							AND password = $password ";
		$row		= $this->dbReader->fetchRow($query);

		if(!$row) return 0;

		if($row['status'] == 0) return -1; //inactive by admin

		$this->setInfo($row);

		return true;
	}

	//store array profile to session
	function setInfo($data)
	{
		$staffInfo = new Zend_Session_Namespace($this->sessionNameSpace);
		$staffInfo->data = serialize($data);
	}

	function getInfo()
	{
		$staffInfo = new Zend_Session_Namespace($this->sessionNameSpace);
		return unserialize($staffInfo->data);
	}

	function logout()
	{
		Zend_Session::namespaceUnset($this->sessionNameSpace);
	}

	function getDetail($id)
	{
		return $this->dbReader->fetchRow("SELECT * FROM {$this->table} WHERE id = $id");
	}

	function getDetailByEmail($email)
	{
		$email = $this->dbReader->quote($email);
		return $this->dbReader->fetchRow("SELECT * FROM {$this->table} WHERE email = $email");
	}

	function update($data)
    {
        try
        {
            $info = $this->getInfo();
			$this->dbReader->update($this->table, $data, 'id=' . $info['id']);

            //set session data
			$this->setInfo(array_merge($info, $data));

			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not update staff record. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function generatePassword($length=6,$level=2)
	{
		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));

		$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

		$password  = "";
		$counter   = 0;

		while ($counter < $length)
		{
			$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

			// All character must be different
			if (!strstr($password, $actChar))
			{
				$password .= $actChar;
				$counter++;
			}
		}
		return $password;
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
					$query		= "	SELECT	*
									FROM ".$this->table."
									ORDER BY lastname
									LIMIT $offset,$limit";
				}
				else
				{
					$query		= "	SELECT	*
									FROM ".$this->table."
									WHERE $condition
									ORDER BY lastname
									LIMIT $offset,$limit";
				}

                //store to session
                $this->setQueryCache($query);

				$results = $this->dbReader->fetchAll($query);
				$this->paging = PagingHelper::getPagingBackEnd($total_page, $current_page);

				return $results;
			}
		}
		catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not get staff lists. query '.print_r($query,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
	}

	function getActiveStatus($status)
	{
		return ($status == 0 ? 'No' : 'Yes') ;
	}

	//used for export excel
	function getStateStatusText($status)
	{
        return ($status == 0 ? 'Disabled' : 'Enable') ;

	}

	function getStateStatus($status, $id)
	{
        if($status == 0)
        {
            $link = "<a href='#' title='Click to Enable' class='activeState' rel='$id' >Disabled</a>";
        }
        else
        {
            $link = "<a href='#' title='Click to Disable' class='deactiveState' rel='$id' >Enabled</a>";
        }

        return $link;

	}

    function changeStatus($id, $status)
    {
        $id = $this->dbReader->quote($id);

        try
        {
            $data = array(
                'status' => $status
            );

           $this->dbReader->update($this->table, $data, 'id=' . $id);
            return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not change staff status. Id '.$id.', new status '.$status.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

	function backendUpdate($id, $data)
    {
        try
        {
			$id = $this->dbReader->quote($id);
			$this->dbReader->update($this->table, $data, "id=$id");

			return true;
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not update staff record at backend. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    //set query to session
	function setQueryCache($query)
	{
		$cache = new Zend_Session_Namespace($this->sessionNameSpace.'_query_cache');
		$cache->data = $query;
	}

    //get query from session
	function getQueryCache()
	{
		$cache = new Zend_Session_Namespace($this->sessionNameSpace.'_query_cache');
		return $cache->data;
	}

	function stripHtmltag($str)
	{
		return strip_tags($str);
	}

    function doExport($regionName)
    {
        //get query from session, remove LIMIT
		$query	= $this->getQueryCache();
		$temp	= explode('LIMIT', $query);
		$query	= $temp[0];

		//now query result and export
		$result = $this->dbReader->fetchAll($query);

		require_once 'PHPExcel.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Website")
									 ->setLastModifiedBy("Website");
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();

		//define header array
		$columns = array(
			'A'	=> array(
					'text'	=> 'Prefix',
					'field'	=> 'title'
			),
			'B'	=> array(
					'text'	=> 'First Name',
					'field'	=> 'firstname'
			),
			'C'	=> array(
					'text'	=> 'Last Name',
					'field'	=> 'lastname'
			),
			'D'	=> array(
					'text'	=> 'Email',
					'field'	=> 'email'
			),
			'E'	=> array(
					'text'	=> 'Phone',
					'field'	=> 'phone'
			),

			'F'	=> array(
					'text'	=> 'Status',
					'field'	=> array('function' => 'getStateStatusText',
									'field'		=> 'status')
			),
			'G'	=> array(
					'text'	=> 'Date Created',
					'field'	=> 'date_created'
			),
			'H'	=> array(
					'text'	=> 'Date Modified',
					'field'	=> 'date_modified'
			)
		);

		$borderArray = array('borders' => array(
												'left' => array(
												  'style' => PHPExcel_Style_Border::BORDER_THIN,
												),
												'right' => array(
												  'style' => PHPExcel_Style_Border::BORDER_THIN,
												),
												'top' => array(
												  'style' => PHPExcel_Style_Border::BORDER_THIN,
												),
												'bottom' => array(
												  'style' => PHPExcel_Style_Border::BORDER_THIN
												),
												'inside' => array(
												  'style' => PHPExcel_Style_Border::BORDER_THIN
												)

											)
		);
		$alignArray = array('alignment' => array(
							'horizontal'    => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							'vertical'      => PHPExcel_Style_Alignment::VERTICAL_TOP,
							'wrap'          => true
		));
		$fontArray	= array('font'	=> array(
				'bold'	=> true
		));

		$rowSub = 2;
		$sheet->mergeCells('A'.$rowSub.':P'.($rowSub+3));
		$sheet->setCellValue('A'.$rowSub, "staff Accounts Of ".  strtoupper($regionName));
        $sheet->getStyle('A'.$rowSub)->getFont()->setSize(16);
		$sheet->getStyle('A'.$rowSub)->getFont()->setBold(true);
		$sheet->getStyle('A'.$rowSub)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		$sheet->getStyle('A'.$rowSub)->getAlignment()->setWrapText(true);
		$sheet->getStyle('A'.$rowSub)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );

		$rowSub +=5;
		//write column header
		$sheet->getStyle('A'.$rowSub.':P'.$rowSub)->applyFromArray(array_merge($borderArray,$alignArray,$fontArray));
		$sheet->getStyle('A'.$rowSub.':P'.$rowSub)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		foreach($columns as $column => $arrayItem)
		{
			$sheet->setCellValue($column.$rowSub, $arrayItem['text']);
			$sheet->getColumnDimension($column)->setWidth(25);
		}

		$rowSub++;
		foreach($result as $row)
		{
			$sheet->getStyle('A'.$rowSub.':P'.$rowSub)->getAlignment()->setWrapText(true);
			$sheet->getStyle('A'.$rowSub.':P'.$rowSub)->applyFromArray($borderArray);
			foreach($columns as $column => $arrayItem)
			{
				if(!is_array($arrayItem['field']))
				{
					$sheet->setCellValue($column.$rowSub, $row[$arrayItem['field']]);
				}
				else
				{
					$functionName = $arrayItem['field']['function'];
					$value = $this->$functionName($row[$arrayItem['field']['field']]);
					if($column == 'K' && $row[$arrayItem['field']['field']] == 5)
						$value .= " \n " .$row['field_other'];

					$sheet->setCellValue($column.$rowSub, $value);
				}

				//set valign top
				$sheet->getStyle($column.$rowSub)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			}
			$rowSub++;
		}

		$filename = strtoupper($regionName).'_staff_List_'.date('m_d_Y').'.xls';

		// Redirect output to a client web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;



    }//end doExport
}
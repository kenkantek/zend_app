<?php
/*
 * 
 * class helper for operate with email
 *
 */

class EmailHelper
{
	var $table;
	var $dbReader;

	var $type = array(
		'0'	=> 'To',
		'1'	=> 'Cc',
		'2'	=> 'Bcc'
	);

	function __construct($table)
	{
		$this->table	= $table;
		$this->dbReader	= Zend_Registry::get('dbReader');
	}

	function getTypeList()
	{
		return $this->type;
	}
	
	function getType($id)
	{
		return isset($this->type[$id]) ? $this->type[$id] : 'unknow';
	}

	function search($condition = null)
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
        if($condition != null)
		{
			$query      = "SELECT *
							FROM {$this->table}
							WHERE $condition " ;
		}
		else
		{
			$query      = "SELECT *
							FROM {$this->table} " ;
		}
        return  $this->dbReader->fetchAll($query);
    }

	function isEmailUnique($email, $emailCurrent = '')
    {
        $email  = $this->dbReader->quote($email);
        $row    = $this->dbReader->fetchRow("SELECT email FROM {$this->table} WHERE email = $email");

        if($row['email'] == '' || ($emailCurrent!= '' && $row['email'] == $emailCurrent)) return true;

		return false;
    }

	function add($data)
    {
        try
        {
            return $insert = $this->dbReader->insert($this->table, $data);
        }
        catch (Exception $e)
        {
            Zend_Registry::get('logger')->emerg('Can not add email. Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
			throw new Exception($e->getMessage());
        }
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
            Zend_Registry::get('logger')->emerg('Can not update email. Id = '.$id.' Data '.print_r($data,true).'. Error trace :'.$e->getMessage());
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
            Zend_Registry::get('logger')->emerg('Can not delete email. Id '.$id.'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

//	function sendEmail($body, $subject)
//    {
//        try
//		{
//			//get all email
//			$query = "SELECT *
//						FROM {$this->table} ";
//			$results = $this->dbReader->fetchAll($query);
//			if(empty($results)) return false;
//
//			$config = array('auth'		=> 'login',
//							'username'	=> MAIL_SENDER,
//							'password'	=> MAIL_PASSWORD,
//							'port'		=> MAIL_PORT);
//            if(TLS !== false)
//                $config['ssl']  = 'tls';
//
//			$transport = new Zend_Mail_Transport_Smtp(MAIL_SERVER, $config);
//
//			$mail = new Zend_Mail('UTF-8');
//			$mail->setBodyHtml($body);
//			$mail->setFrom(MAIL_SENDER);
//
//			foreach($results as $item)
//			{
//				$email  = trim($item['email']);
//                $name   = trim($item['name']);
//                if($item['type'] == 0) $mail->addTo($email, $name);
//				elseif($item['type'] == 1) $mail->addCc($email, $name);
//				elseif($item['type'] == 2) $mail->addBcc($email);
//			}
//
//			$mail->setSubject($subject ." ". date('m/d/Y H:i'));
//			$mail->send($transport);
//
//			return true;
//		}
//		catch (Exception $e)
//		{
//			Zend_Registry::get('logger')->emerg('Can not send email. Body '.print_r($data,true).'. Error trace :'.$e->getMessage());
//            throw new Exception($e->getMessage());
//		}
//    }

    function sendEmail($body, $subject)
    {
        try
		{
			//get all email
			$query = "SELECT *
						FROM {$this->table} ";
			$results = $this->dbReader->fetchAll($query);
			if(empty($results)) return false;

			$mail = new Zend_Mail('UTF-8');
			$mail->setBodyHtml($body);
			$mail->setFrom(MAIL_SENDER);

			foreach($results as $item)
			{
				$email  = trim($item['email']);
                $name   = trim($item['name']);
                if($item['type'] == 0) $mail->addTo($email, $name);
				elseif($item['type'] == 1) $mail->addCc($email, $name);
				elseif($item['type'] == 2) $mail->addBcc($email);
			}

			$mail->setSubject($subject ." ". date('d/n/Y H:i'));
			$mail->send();

			return true;
		}
		catch (Exception $e)
		{
			Zend_Registry::get('logger')->emerg('Cannot send email. Body '.print_r($data,true).'. Error trace :'.$e->getMessage());
            throw new Exception($e->getMessage());
		}
    }
}


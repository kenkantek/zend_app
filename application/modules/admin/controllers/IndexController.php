<?php

class Admin_IndexController extends Zend_Controller_Action
{
    function init()
	{
		$this->smarty = Zend_Registry::get('smarty');
		$this->smarty->template_dir = ADMIN_TEMPLATE_DIR;
		$this->smarty->assign('resourceDir', ADMIN_RESOURCE_DIR);
	}

	function indexAction()
	{
		//require_once('recaptchalib.php');
		$errorMessage = '';

		if(isset($this->_request->e))
		{
			switch ($this->_request->e)
			{
				case '1':
					$errorMessage = "Incorrect username or password!";
					break;
				case '2':
					$errorMessage = "Session timeout. Please login again!";
					break;
			}
		}
		
		$this->smarty->assign('errorMessage', $errorMessage);
		//$this->smarty->assign('recaptchaWidget', recaptcha_get_html(RECAPTCHA_PUBLIC_KEY));
		$this->smarty->display('login.html');
	}
}
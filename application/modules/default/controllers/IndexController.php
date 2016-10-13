<?php
class IndexController extends Zend_Controller_Action
{
	function indexAction()
	{
		$this->_redirect(WEBSITE_URL.'en/');
	}
}
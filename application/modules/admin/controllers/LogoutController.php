<?php
/**
*	LogoutController : log out
*/
class Admin_LogoutController extends Zend_Controller_Action
{
    function indexAction ()
    {
		Zend_Session::namespaceUnset('adminInfo');
		$this->_redirect(ADMIN_MODULE_PATH);
    }
}
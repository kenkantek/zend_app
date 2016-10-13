<?php

class AdminUser
{
	const EU_REGION		= 2;
	const US_REGION		= 3;
	const CHINA_REGION	= 4;
	const GLOBAL_REGION	= 5;
	
	public static function isLogged()
	{
		if (!Zend_Session::namespaceIsset('adminInfo')) return false;

		$login_infos = new Zend_Session_Namespace('adminInfo');
		if (!$login_infos->logined) return false;

		return true;
	}

	public static function getInfo()
	{
		return new Zend_Session_Namespace('adminInfo');
	}

	public static function validateRegion($region)
	{
		if(self::getInfo()->region_id != $region) return false;
		return true;
	}
}
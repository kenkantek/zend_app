<?php
/**
*	LangController : for switch language
*/

class LangController extends Zend_Controller_Action
{

    function switchAction()
	{
	
		$lang = $this->_request->lang;
		if (isset($_SERVER['HTTP_REFERER']))
		{
			//we have format :lang/region_name/....
			$previousPath	= $_SERVER['HTTP_REFERER'];
			$subPath		= str_replace(WEBSITE_URL, '', $previousPath);
			$pos			= strpos($subPath, '/');
			$paramPath		= substr($subPath, $pos);

			header("Location: ".WEBSITE_URL.$lang.$paramPath);
			exit();
		}
		else
		{
			header("Location: ".WEBSITE_URL);
			exit();
		}
    }
}

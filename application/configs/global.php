<?php

	//debug ?
	define("DEBUG",true);

    //using cache
    define("USE_CACHE",false);

    //for website url
	define("WEBSITE_URL",'http://'.$_SERVER['SERVER_NAME'].'/ironrid/');//'http://209.59.162.241/~ironridg/');
	define('WEB_ROOT_PATH','D:/wamp/www/rentacoder/ironridgeresources.com.au/public_html/');

	//for Zend and Smarty
	define('APPLICATION_PATH', 'D:/wamp/www/rentacoder/ironridgeresources.com.au/application/');
	define('LIBRARY_PATH', APPLICATION_PATH . '/../library/');
    define('LOG_PATH', APPLICATION_PATH . '/../app_log/');
	define('CLASS_PATH', APPLICATION_PATH . '/class/');
	define('TEMP_PATH', APPLICATION_PATH . '/../temp/');

	define('CONTROLLER_PATH',APPLICATION_PATH . '/controllers');
    define('MODULE_PATH',APPLICATION_PATH . '/modules');
	define('MODEL_PATH',APPLICATION_PATH . '/model/');
	define('LANGUAGE_PATH',APPLICATION_PATH . '/languages/');

	define('TEMPLATE_PATH', APPLICATION_PATH . '/views/templates/');
	define('TEMPLATE_COMPILE_PATH', APPLICATION_PATH . '/views/templates_c/');

	define('CONFIG_PATH', APPLICATION_PATH. '/configs/');
	define('CONFIG_DB_PATH', CONFIG_PATH . '/database.ini');

    define("AJAX_TIMEOUT",50000);

    require_once 'custom.php';
	require_once 'constants.php';

    //define for template path
	define('COMMON_TEMPLATE_DIR','common/');
	define('DEFAULT_TEMPLATE_DIR','default/');

	//for admin
	define('ADMIN_TEMPLATE_DIR',TEMPLATE_PATH.'admin/');
	define("ADMIN_RESOURCE_DIR", WEBSITE_URL.'resource_admin/');
	define('ADMIN_MODULE_PATH',WEBSITE_URL.'admin/');

	define('PAGES_TABLE','pages');
	define('OTHER_PAGES_TABLE','others');
	define('NEWS_TABLE','news');
	define('EMAILS_TABLE','emails');
	define('STAFF_TABLE','staffs');
	define('STAFF_NAMESPACE', 'staff_en');
	define('SLIDE_IMAGES_TABLE','slideshow');
	define('SLIDE_IMAGES_URL',WEBSITE_URL.'slideshow_images/');
	define('SLIDE_IMAGES_FOLDER',WEB_ROOT_PATH.'slideshow_images/');

	define('GALLERY_ALBUM_TABLE','gallery_album');
	define('GALLERY_IMAGES_TABLE','gallery_image');
	define('GALLERY_IMAGES_URL',WEBSITE_URL.'gallery_images/');
	define('GALLERY_IMAGES_FOLDER',WEB_ROOT_PATH.'gallery_images/');


	define('TEMPLATE_DIR',TEMPLATE_PATH.'main/');
	define('RESOURCE_DIR',WEBSITE_URL.'resource/');
	define('USER_FILES', WEB_ROOT_PATH.'userfiles/');

	class EuConfig
	{
		public static $allowedLanguage = array(
			'en'	=> 'English',
			'fr'	=> 'French',
		);
	}
	define('DEFAULT_LANGUAGE','en');

	//this variable will be merge for all region
	$languageFiles	= array_merge(EuConfig::$allowedLanguage);

<?php
ini_set('memory_limit',-1);
set_time_limit(0);
//date_default_timezone_set('Australia/Sydney');

try
{
    require_once '../application/configs/global.php';
    set_include_path(
        LIBRARY_PATH
        . PATH_SEPARATOR .CLASS_PATH
		. PATH_SEPARATOR .MODEL_PATH
        . PATH_SEPARATOR . get_include_path()
    );


    //display all errors if debugging
    if (DEBUG)
        ini_set('display_errors',1);
	else
		ini_set('display_errors',0);

    //FrontController
	require_once 'Zend/Loader/Autoloader.php';
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->setFallbackAutoloader(true);
	$loader->registerNamespace('TKTS_app');

    $frontController = Zend_Controller_Front::getInstance();
    //$frontController->setParam('useDefaultControllerAlways', true);
    $frontController->addModuleDirectory(MODULE_PATH);
    $frontController->throwExceptions(true);
    $frontController->setParam('noViewRenderer', true);
    $frontController->setParam('noErrorHandler', true);

    //set default Content-Type
    $response = new Zend_Controller_Response_Http;
    $response->setHeader('Content-Type', 'text/html; charset=utf-8', true);
    $frontController->setResponse($response);

	require_once 'route.php';

    //Session
    Zend_Session::start();

    //Smarty
    require_once 'Smarty/Smarty.class.php';
    $smarty = new Smarty();
    $smarty->debugging      = false;
	$smarty->left_delimiter =  '<{';
	$smarty->right_delimiter =  '}>';
    $smarty->force_compile  = true;
    $smarty->caching        = false;
    $smarty->compile_check  = true;
    $smarty->cache_lifetime = -1;
    $smarty->template_dir   = TEMPLATE_PATH;
    $smarty->compile_dir    = TEMPLATE_COMPILE_PATH;
    Zend_Registry::set('smarty', $smarty);

    //Database
    $config = new Zend_Config_Ini(CONFIG_DB_PATH ,'main');
    $dbReader = Zend_Db::factory($config->db);
    Zend_Registry::set('dbReader', $dbReader);

    //cache
    if(USE_CACHE)
    {
		$cache = Zend_Cache::factory('Core', CACHED_TYPE, $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
		Zend_Translate::setCache($cache);
    }

	//logger
	$logger = new Zend_Log();
    $writer = new Zend_Log_Writer_Stream(LOG_PATH.date('m_d_Y').'_app.log');
    $logger->addWriter($writer);
	Zend_Registry::set('logger', $logger);

	//translate
	//need load all languages

	$translate		= null;
	foreach($languageFiles as $locale => $name)
	{
		if(is_null($translate))
		{
			$translate = new Zend_Translate(
				array(
					'adapter'	=> 'array',
					'content'	=> LANGUAGE_PATH.$locale.'.php',
					'locale'	=> $locale
				)
			);
		}
		else
		{
			$translate->addTranslation(
				array(
					'content'	=> LANGUAGE_PATH.$locale.'.php',
					'locale'	=> $locale
				)
			);
		}
	}

	//set log for translate
	$translate->setOptions(
		array(
			'log'             => Zend_Registry::get('logger'),
			'logUntranslated' => true
		)
	);

	Zend_Registry::set('translate',$translate);

	//Dispatch
    $frontController->dispatch();
    $dbReader->closeConnection();
}
catch(Exception $e)
{
	if (DEBUG)
	{
		echo 'Error : '.$e->getMessage();
		echo '<br/>';
		echo 'Trace info : '.$e->getTraceAsString();
	}
	else
	{
		Zend_Registry::get('logger')->emerg('Page not found. Error trace :'.$e->getMessage());
		header("Location: ".WEBSITE_URL."page-error");
	}
}

unset($frontController);
unset($smarty);
if (USE_CACHE) unset($cache);
unset($dbReader);
unset($logger);

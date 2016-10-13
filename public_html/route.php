<?php
//routing--------------------------------------------------------------
	$router = $frontController->getRouter();

	$route = new Zend_Controller_Router_Route (
    ':lang/',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'home'
          )
	);
	$router->addRoute('', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/page/:unique_title',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'page'
          )
	);
	$router->addRoute('/page/:unique_title', $route);

    $route = new Zend_Controller_Router_Route (
    ':lang/page/:unique_title/:sub_unique_title',
    array(
			'module'	 => 'eu',
			'controller' => 'display',
			'action'     => 'sub-page'
          )
	);
	$router->addRoute('/page/:unique_title/:sub_unique_title', $route);

    $route = new Zend_Controller_Router_Route (
    ':lang/news/',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'news'
          )
	);
	$router->addRoute('/latest-news', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/news-detail/:id',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'news-detail',
            'item'       => 'current'
          )
	);
	$router->addRoute('/news-detail/:id', $route);

    $route = new Zend_Controller_Router_Route (
    ':lang/faq',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'display-info',
			'id'		 => '3'
          )
	);
	$router->addRoute('/faq', $route);

    $route = new Zend_Controller_Router_Route (
    ':lang/privacy',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'display-info',
			'id'		 => '4'
          )
	);
	$router->addRoute('/privacy', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/aim-announcements-archive',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'display-info',
			'id'		 => '5'
          )
	);
	$router->addRoute('/aim-announcements-archive', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/media-archive',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'display-info',
			'id'		 => '6'
          )
	);
	$router->addRoute('/media-archive', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/show/news/list/:page',
    array(
			'module'	 => 'eu',
			'controller' => 'display',
			'action'     => 'news-list'
          )
	);
	$router->addRoute('/show/news/list/:page', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/do/send/subcription/',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'send-subcription'
          )
	);
	$router->addRoute('/do/send/subcription/', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/search/:text',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'search'
          )
	);
	$router->addRoute('/search/:text', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/do/show/result/:title',
    array(
			'module'	 => 'eu',
			'controller' => 'display',
			'action'     => 'process-result-page'
          )
	);
	$router->addRoute('/do/show/result/:title', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/do-login',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'do-login'
          )
	);
	$router->addRoute('do-login', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/logout',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'logout'
          )
	);
	$router->addRoute('logout', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/staff',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'staff'
          )
	);
	$router->addRoute('staff', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/show-page/:unique_title',
    array(
			'module'	 => 'eu',
			'controller' => 'display',
			'action'     => 'display-info-by-unique-title',
          )
	);
	$router->addRoute('/show-page/:unique_title', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/gallery',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'gallery'
          )
	);
	$router->addRoute('gallery', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/gallery-slide-show/:idAlbum',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'gallery-slide-show'
          )
	);
	$router->addRoute('gallery-slide-show', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/subscription',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'subscription'
          )
	);
	$router->addRoute('subscription', $route);

	$route = new Zend_Controller_Router_Route (
    ':lang/page-error',
    array(
			'module'	 => 'default',
			'controller' => 'display',
			'action'     => 'page-error',
          )
	);
	$router->addRoute('/page-error', $route);
	//end routing--------------------------------------------------------
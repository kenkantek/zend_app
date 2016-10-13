<?php
class DisplayController extends Zend_Controller_Action
{
    var $uiVar;
	var $smarty;
	var $menuHelper;
	var $newsHelper;
	var $otherPageHelper;
	var $bannerHelper;
	var $searchHelper;
	var $modulePath;
	var $staffHelper;
	var $galleryHelper;

	var $headerImages = array(
		'about-us'			=> 'aboutus.jpg',
		'project-areas'		=> 'project.jpg',
		'aim-rule-26-info'	=> 'aim.jpg',
		'media-centre'		=> 'media.jpg',
		'investor-centre'	=> 'investor.jpg',
		'contact-us'		=> 'contact.jpg',
		'news'				=> 'news.jpg',
		'staff'				=> 'staff.jpg',
		'faq'				=> 'faq.jpg',
		'privacy'			=> 'privacy.jpg',
	);

	function init()
	{
        $this->smarty = Zend_Registry::get('smarty');
		$this->smarty->template_dir = TEMPLATE_DIR;
		$this->smarty->assign('resourceDir', RESOURCE_DIR);
		$this->smarty->assign('modulePath', WEBSITE_URL);

		//if not language specified , or not allowed language --> use DEFAULT
		//else use language specified
		$this->lang = $this->_request->getParam('lang');
		if(is_null($this->lang) || !isset(EuConfig::$allowedLanguage[$this->lang]))
		{
			$this->lang = DEFAULT_LANGUAGE;
		}

		$this->modulePath = WEBSITE_URL.$this->lang.'/';
		$this->smarty->assign('modulePath', $this->modulePath);

		$this->translate = Zend_Registry::get('translate');
		$this->translate->setLocale($this->lang);
		$this->smarty->assign('translate',$this->translate);

		if($this->lang == 'en') $lang = '';
		else $lang = '_'.$this->lang;

		$this->menuHelper		= new FrontMenuHelper(PAGES_TABLE, $lang);
		$this->otherPageHelper	= new OtherPageHelper(OTHER_PAGES_TABLE, $lang);
		$this->newsHelper		= new NewsHelper(NEWS_TABLE, $lang);

		//search helper
		$this->searchHelper		= new SearchHelper(PAGES_TABLE, OTHER_PAGES_TABLE, NEWS_TABLE, $lang);

		//banner slide
		$this->bannerHelper = new BannerHelper(SLIDE_IMAGES_TABLE, SLIDE_IMAGES_FOLDER, SLIDE_IMAGES_URL);
		$this->smarty->assign('slideBanner', $this->bannerHelper->getSlide());


		$this->uiVar['subMenuOfAboutUs']		= $this->menuHelper->getSubPageByParentUniqueTitle('about-us');
		$this->uiVar['subMenuOfProjectAreas']	= $this->menuHelper->getSubPageByParentUniqueTitle('project-areas');
		$this->uiVar['subMenuOfAIMRule26Info']	= $this->menuHelper->getSubPageByParentUniqueTitle('aim-rule-26-info');
		$this->uiVar['subMenuOfMediaCentre']	= $this->menuHelper->getSubPageByParentUniqueTitle('media-centre');
		$this->uiVar['subMenuOfInvestorCentre'] = $this->menuHelper->getSubPageByParentUniqueTitle('investor-centre');
		$this->uiVar['subMenuOfContactUs']		= $this->menuHelper->getSubPageByParentUniqueTitle('contact-us');

		$this->staffHelper = new StaffHelper(STAFF_TABLE, STAFF_NAMESPACE);
		if($this->staffHelper->isLogined())
		{
			$this->smarty->assign('staffLogged', true);
			$this->smarty->assign('staffInfo', $this->staffHelper->getInfo());
		}
		else
		{
			$this->smarty->assign('staffLogged', false);
		}

		$this->smarty->assign('menuHelper', $this->menuHelper);
		$this->smarty->assign('newsHelper', $this->newsHelper);

		//lastest news column
		$this->uiVar['latestNews']	= $this->newsHelper->getLatestNews();

		$this->galleryHelper= new GalleryHelper(GALLERY_ALBUM_TABLE, GALLERY_IMAGES_TABLE, GALLERY_IMAGES_FOLDER, GALLERY_IMAGES_URL, $lang);
	}

	/**
	 * for home
	 */
	function homeAction()
    {
		//home column
		$home = $this->otherPageHelper->getDetail(1);
		$this->uiVar['homeContent'] = $home['content'];

		$sharePriceChart = new SharePriceChart();
		$this->uiVar['sharePriceChart'] = $sharePriceChart->getStockPrice();

		$this->uiVar['rssWidget'] = file_get_contents("http://output15.rssinclude.com/output?type=php&id=983981&hash=8dca14158758e018004f384f2598f850");

		$this->smarty->assign('uiVar', $this->uiVar);
		$this->smarty->display('index.html');
    }

	/*
	 * for main-page page_unique_title
	 */
	function pageAction()
	{
		//get this record
		$unique_title = $this->_getParam('unique_title');
		$row = $this->menuHelper->getPageByTitle($unique_title);
		if(!$row)
		{
			die('Can not find this page');
		}

		$this->uiVar['initPage']	= $row;
		$this->uiVar['headerImage'] = $this->headerImages[$unique_title];

		$this->smarty->assign('uiVar', $this->uiVar);
        $this->smarty->display('page.html');
	}

	/*
	 * for main-page lang/region/page_unique_title/sub-page_unique_title
	 */
	function subPageAction()
	{
		$unique_title		= $this->_getParam('unique_title');
		$sub_unique_title	= $this->_getParam('sub_unique_title');
		$this->uiVar['main_page_unique_title'] = $unique_title;
		$this->uiVar['sub_page_unique_title'] = $sub_unique_title;
		//parent page
		$row = $this->menuHelper->getPageByTitle($unique_title);
		if(!$row)
		{
			die('Can not find parent page');
		}

		//current sub-page
		$row = $this->menuHelper->getPageByTitle($sub_unique_title);
		if(!$row)
		{
			die('Can not find sub page');
		}
		$this->uiVar['initPage'] = $row;

		$this->uiVar['headerImage'] = $this->headerImages[$unique_title];

		if($sub_unique_title == 'share-price-chart')
		{
			$sharePriceChart = new SharePriceChart();
			$this->uiVar['sharePriceChart'] = $sharePriceChart->getStockPrice();
		}

		$this->smarty->assign('uiVar', $this->uiVar);

		$this->smarty->display('page.html');
	}

	/**
	 * display news page
	 */
	function newsAction()
	{
		$this->uiVar['headerImage'] = $this->headerImages['news'];
		$this->smarty->assign('uiVar', $this->uiVar);
        $this->smarty->display('news.html');
	}

	/**
	 * display news content
	 */
	function newsDetailAction()
	{
		$this->uiVar['headerImage'] = $this->headerImages['news'];
        $this->uiVar['row'] = $this->newsHelper->getContentById($this->_request->getParam('id'));
		//$this->uiVar['relatedNews'] = $this->newsHelper->getRelatedNews($this->uiVar['row']['id']);

		$this->smarty->assign('uiVar', $this->uiVar);
		$this->smarty->display('news_detail.html');
	}

	/**
	 * get new list
	 * @return html content
	 */
	function newsListAction()
	{
		if(!$this->_request->isXmlHttpRequest())
		{
			echo JsonHelper::encodeContent('Not allowed', false);
		}

		$page = $this->_request->getParam('page');
		$this->smarty->assign('list',$this->newsHelper->getList($page));
		$this->smarty->assign('paging',$this->newsHelper->paging);

		echo JsonHelper::encodeContent($this->smarty->fetch('news_list.html'));
	}

	function sendSubcriptionAction()
	{
		$request = $this->_request;
		if(!$request->isXmlHttpRequest())
		{
			echo JsonHelper::encodeContent('Data not posted correctly !',false);
			return;
		}

		try
		{
			require_once 'MCAPI.class.php';
			$api = new MCAPI(MAILCHIMP_API);

			$merge_vars = array('FNAME'	=> $request->getParam('name'));
			$retval = $api->listSubscribe(MAILCHIMP_LIST_ID, $request->getParam('email'), $merge_vars ,'html', false, true);

			if($api->errorCode)
			{
				Zend_Registry::get('logger')->emerg("Unable to load listSubscribe()!. Code={$api->errorCode}, Msg={$api->errorMessage}");
				echo JsonHelper::encodeContent('Can not process.Please retry later', false);
			}
			else
			{
                echo JsonHelper::encodeContent($this->translate->_('SUBCRIBE_MSG_SEND_COMPLETE'));
			}

		}
		catch (Exception $e)
		{
			echo JsonHelper::encodeContent('Can not process . Error :'.$e->getMessage(), false);
		}
	}

	/**
     * display info from others table, based id
     */
    function displayInfoAction()
	{
		$id = $this->_request->getParam('id');

		//staff ?
		if($id == '2')
		{
			$this->_redirect($this->modulePath.'staff/');
		}

        $this->uiVar['initPage'] = $this->otherPageHelper->getDetail($id);

		if($id == '2')
			$this->uiVar['headerImage'] = $this->headerImages['staff'];
		elseif($id == '3')
			$this->uiVar['headerImage'] = $this->headerImages['faq'];
		elseif($id == '4')
			$this->uiVar['headerImage'] = $this->headerImages['privacy'];
		elseif($id == '5' || $id == '6')
			$this->uiVar['headerImage'] = $this->headerImages['investor-centre'];

		$this->smarty->assign('uiVar', $this->uiVar);

        $this->smarty->display('page.html');
	}

	/**
     * display info from others table, for UserDefine records
     */
    function displayInfoByUniqueTitleAction()
	{
		$uniqueTitle = $this->_request->getParam('unique_title');

		//staff ?
		if($uniqueTitle == 'staff')
		{
			$this->_redirect($this->modulePath.'staff/');
		}

        $this->uiVar['initPage'] = $this->otherPageHelper->getDetailByUniqueTitle($uniqueTitle);

		//get about us image
		$this->uiVar['headerImage'] = $this->headerImages['about-us'];

		$this->smarty->assign('uiVar', $this->uiVar);

        $this->smarty->display('page.html');
	}

	function pageErrorAction()
	{
		$this->smarty->display('error.html');
	}

	function searchAction()
	{
		$text = $this->_request->text;
		$result = $this->searchHelper->doSearch($text);

		if(count($result) == 0)
		{
			$this->smarty->assign('searchResult', $this->translate->_('SEARCH_MSG_NO_RESULT'));
		}
		else
		{
			//take care with content page
			$temp = array();
			foreach($result as $row)
			{
				//build description
				$description = $this->otherPageHelper->buildDescription($row['content']);

				if($row['object_type'] == 'content_page')
				{
					$temp[] = "<p>» <b>{$row['title']}</b> <br/> $description
								<a href='{$this->modulePath}do/show/result/{$row['id']}/'>{$this->translate->_('READ_MORE')}...</a></p>";
				}
				elseif($row['object_type'] == 'news_page')
				{
					$temp[] = "<p>» <b>{$row['title']}</b> <br/> $description
								<a href='{$this->modulePath}news-detail/{$row['id']}/'>{$this->translate->_('READ_MORE')}...</a></p>";
				}
				elseif($row['object_type'] == 'other_page')
				{
					if($row['id'] == 1)
					{
						$temp[] = "<p>» <b>{$row['title']}</b> <br/> $description
									<a href='{$this->modulePath}'>{$this->translate->_('READ_MORE')}...</a></p>";
					}
					elseif($row['id'] == 3)
					{
						$temp[] = "<p>» <b>{$row['title']}</b> <br/> $description
										<a href='{$this->modulePath}/faq/'>{$this->translate->_('READ_MORE')}...</a></p>";
					}
					elseif($row['id'] == 4)
					{
						$temp[] = "<p>» <b>{$row['title']}</b> <br/> $description
										<a href='{$this->modulePath}/privacy/'>{$this->translate->_('READ_MORE')}...</a></p>";
					}
					//staff???

				}

			}

			$this->smarty->assign('searchResult', implode('',$temp));
		}

		$this->smarty->assign('uiVar', $this->uiVar);
		$this->smarty->display('search_result.html');
	}

	/*
	 * process link for content page link for result list
	 */
	function processResultPageAction()
	{
		$unique_title = $this->_request->title;

		//get detail
		$row = $this->menuHelper->getPageByTitle($unique_title);

		//check parent
		if(!$row) die('Wrong title');

		//main menu
		if($row['parent_id'] == 0) $this->_redirect($this->modulePath.'page/'.$unique_title.'/');

		//check if level 2
		$rowParent = $this->menuHelper->getPageById($row['parent_id']);
		if($rowParent['parent_id'] == 0) $this->_redirect($this->modulePath.'page/'.$rowParent['page_unique_title'].'/'.$unique_title.'/');

	}

	/*
	 * check login
	 */
	function doLoginAction()
	{
		$request = $this->_request;
		if(!$request->isXmlHttpRequest())
		{
			echo JsonHelper::encodeContent('Data not posted correctly!',false);
			return;
		}

		try
		{
			$check = $this->staffHelper->checkLogin($request->getParam('staffUsername'), $request->getParam('staffPassword'));
			if($check !== true)
			{
				if($check == 0)
					echo JsonHelper::encodeContent($this->translate->_('STAFT_WRONG_USER_PASS'), false);
				elseif($check == -1)
					echo JsonHelper::encodeContent($this->translate->_('STAFT_USER_DISABLED'), false);
			}
			else
			{
				echo JsonHelper::encodeContent($this->translate->_('STAFT_LOGIN_SUCCESS'));
			}
		}
		catch (Exception $e)
		{
			echo JsonHelper::encodeContent('Cannot process . Error :'.$e->getMessage(), false);
		}
	}

	/**
	 * log out contractor
	 */

	function logoutAction()
	{
		$this->staffHelper->logout();
		$this->_redirect($this->modulePath);
	}

	/**
     * display info from others table, based id
     */
    function staffAction()
	{
		$id = 2;
		$this->uiVar['headerImage'] = $this->headerImages['staff'];

		if(!$this->staffHelper->isLogined())
		{
			$this->_redirect($this->modulePath);
			return;
		}

		$this->uiVar['initPage'] = $this->otherPageHelper->getDetail(2);

		$this->smarty->assign('uiVar', $this->uiVar);
        $this->smarty->display('page.html');
	}

	function galleryAction()
	{
		$list = $this->galleryHelper->getListAllbumFrontEnd();
		$this->smarty->assign('listAlbum', $list);
		$this->smarty->assign('galleryHelper', $this->galleryHelper);

		$this->smarty->assign('uiVar', $this->uiVar);
        $this->smarty->display('gallery.html');
	}


	function gallerySlideShowAction()
	{
		$idAlbum = $this->_request->getParam('idAlbum');

		$list = $this->galleryHelper->getListImageFrontEnd($idAlbum);

		if(count($list) == 0)
		{
			echo JsonHelper::encodeContent($this->translate->_('ALBUM_NO_IMAGE'), false);
			die;
		}

		$out = array();
		foreach($list as $row)
		{
			$out[] = '<a class="galleryImage"
				href="'.$this->galleryHelper->getImageUrl($row['image']).'"
				rel="prettyPhoto[album]"
				title="'.$row['description'].'">
				<img src="'.$this->galleryHelper->getImageUrl($row['image']).'" width="80" height="80"
				alt="'.$row['title'].'"" />
				</a>';
		}

		$out = implode('', $out);

		echo JsonHelper::encodeContent($out);
	}

	function subscriptionAction()
	{
		$this->smarty->assign('uiVar', $this->uiVar);
		$this->smarty->display('enews.html');
	}

}
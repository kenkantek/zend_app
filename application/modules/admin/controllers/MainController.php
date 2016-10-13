<?php
/**
*	Admin_MainController : admin main
*/

class Admin_MainController extends Zend_Controller_Action
{
	var $smarty;
    var $contentPageHelper;
    var $childTemplateDir;
	var $newsHelper;
	var $bannerHelper;
	var $staffHelper;
	var $otherPageHelper;
	var $galleryHelper;


	function init()
	{
		if(!AdminUser::isLogged())
		{
			if($this->_request->isXmlHttpRequest())
            {
                echo JsonHelper::encodeContent('TIME_OUT');
                die;
            }
			else $this->_redirect (ADMIN_MODULE_PATH);
		}

		$this->smarty = Zend_Registry::get('smarty');
		$this->smarty->template_dir = ADMIN_TEMPLATE_DIR;
		$this->smarty->assign('resourceDir', ADMIN_RESOURCE_DIR);
		$this->smarty->assign('adminInfo', AdminUser::getInfo());
		$this->smarty->assign('pageTitle','IronRidge Admin');
		$this->smarty->assign('controllerPath',ADMIN_MODULE_PATH.'main/');
		$this->smarty->assign('regionPrefix', 'main');

        $this->contentPageHelper = new ContentPageHelper(PAGES_TABLE);
		$this->otherPageHelper = new OtherPageHelper(OTHER_PAGES_TABLE);
		$this->newsHelper	= new BackEndNewsHelper(NEWS_TABLE);
		$this->staffHelper	= new StaffHelper(STAFF_TABLE, 'IronRidgeNameSpace');
		$this->bannerHelper = new BannerHelper(SLIDE_IMAGES_TABLE, SLIDE_IMAGES_FOLDER, SLIDE_IMAGES_URL);
		$this->galleryHelper= new GalleryHelper(GALLERY_ALBUM_TABLE, GALLERY_IMAGES_TABLE, GALLERY_IMAGES_FOLDER, GALLERY_IMAGES_URL);

        $this->childTemplateDir = '';
	}

	function indexAction()
	{
		$this->smarty->display('index.html');
	}

	function contentPagesAction()
	{
		$this->smarty->assign('navTitle','Manage Content Pages');
		$this->smarty->display($this->childTemplateDir.'manage_page.html');
	}

    function getComboParentAction()
    {
        if($this->_request->parent_id)
            echo JsonHelper::encodeContent($this->contentPageHelper->getComboParent(true, 'width: 140px; font-size: 10px;','parent_id', $this->_request->parent_id));
        else
            echo JsonHelper::encodeContent($this->contentPageHelper->getComboParent(true, 'width: 140px; font-size: 10px;','parent_id'));
    }

    function getListContentPageAction()
    {
        $parent_id = $this->_request->parent_id;
        $condition = array(
            array('field'		=> 'parent_id',
				  'value'		=> $parent_id,
				  'operator'	=> '=')
        );
		if($this->_request->text)
		{
			$condition[] = array('field'	=> 'title',
								'value'		=> '%'.$this->_request->text.'%',
								'operator'	=> 'LIKE');
		}

        $this->smarty->assign('list',$this->contentPageHelper->searchPage($condition));

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_content_page.html'));
    }

    function getFormAddNewPageAction()
    {
        $this->smarty->assign('parentCombo', $this->contentPageHelper->getComboParent(false,'width:320px;',
                                                                                        'parent_id_form'));
        $content = $this->smarty->fetch($this->childTemplateDir.'form_add_content_page.html');
        echo JsonHelper::encodeContent($content);
    }

    function addNewPageAction()
    {
        try
        {
            $title_en       = $this->_request->getParam('title');
			$title_fr       = $this->_request->getParam('title_fr');
            $title_unique   = $this->contentPageHelper->buildUniqueTitle($title_en);
            if(!$this->contentPageHelper->isPageTitleUnique($title_unique))
            {
                echo JsonHelper::encodeContent("This title already exists.  Please use a unique title different from < $title_unique >", false);
                return false;
            }

            $data = array(
                'page_unique_title' => $title_unique,
                'title'				=> $this->contentPageHelper->trimTitle($title_en),
                'content'			=> $this->_request->getParam('content'),
				'title_fr'			=> $this->contentPageHelper->trimTitle($title_fr),
                'content_fr'		=> $this->_request->getParam('content_fr'),
                'parent_id'         => $this->_request->getParam('parent_id_form')
            );

            if($this->contentPageHelper->add($data))
            {
                echo JsonHelper::encodeContent('Add page success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot add page. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

    function deleteContentPageAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->contentPageHelper->delete($id))
            {
                echo JsonHelper::encodeContent('Delete page success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

    function getFormEditPageAction()
    {
        $id     = $this->_request->getParam('id');
        $row    = $this->contentPageHelper->getDetail($id);

        $this->smarty->assign('parentCombo', $this->contentPageHelper->getComboParentForModify($row['parent_id'],'width:320px;',
                                                                                        'parent_id_form'));
        $this->smarty->assign('row',$row);
        $content = $this->smarty->fetch($this->childTemplateDir.'form_edit_content_page.html');
        echo JsonHelper::encodeContent($content);
    }

    function updatePageAction()
    {
        try
        {
            //should check page existed ?
            $id             = $this->_request->getParam('current_page_id');
            $title_en       = $this->_request->getParam('title');
			$title_fr       = $this->_request->getParam('title_fr');
            $title_unique   = $this->contentPageHelper->buildUniqueTitle($title_en);
			$current_unique_title = $this->_request->getParam('current_unique_title');
            if(!$this->contentPageHelper->isPageTitleUnique($title_unique, $current_unique_title))
            {
                echo JsonHelper::encodeContent("This title already exists.  Please use a unique title different from < $title_unique >", false);
                return false;
            }

            $data = array(
                'page_unique_title' => $title_unique,
                'title'				=> $this->contentPageHelper->trimTitle($title_en),
                'content'			=> $this->_request->getParam('content'),
				'title_fr'			=> $this->contentPageHelper->trimTitle($title_fr),
                'content_fr'		=> $this->_request->getParam('content_fr'),
                'parent_id'         => $this->_request->getParam('parent_id_form'),
                'date_modified'     => new Zend_Db_Expr('now()')
            );

            if($this->contentPageHelper->update($id, $data))
            {
                echo JsonHelper::encodeContent('Update page success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Update page. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

    function reorderPageAction()
    {
        try
        {
            if($this->contentPageHelper->reOrder($this->_request))
            {
                echo JsonHelper::encodeContent('Reorder list success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	//-------------------OTHER PAGE----------

	function otherPagesAction()
	{
		$this->smarty->assign('navTitle','Manage Other Pages Content');
		$this->smarty->display($this->childTemplateDir.'manage_other.html');
	}

	function getListOtherPageAction()
    {
        $this->smarty->assign('list',$this->otherPageHelper->searchOtherPage());

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_other_page.html'));
    }

	function getFormAddNewOtherPageAction()
    {
        $content = $this->smarty->fetch($this->childTemplateDir.'form_add_other_page.html');
        echo JsonHelper::encodeContent($content);
    }

    function addNewOtherPageAction()
    {
        try
        {
            $title_en       = $this->_request->getParam('title');
			$title_fr       = $this->_request->getParam('title_fr');
            $title_unique   = $this->otherPageHelper->buildUniqueTitle($title_en);
            if(!$this->otherPageHelper->isPageTitleUnique($title_unique))
            {
                echo JsonHelper::encodeContent("This title already exists.  Please use a unique title different from < $title_unique >", false);
                return false;
            }

            $data = array(
                'page_unique_title' => $title_unique,
                'title'				=> $this->otherPageHelper->trimTitle($title_en),
                'content'			=> $this->_request->getParam('content'),
				'title_fr'			=> $this->otherPageHelper->trimTitle($title_fr),
                'content_fr'		=> $this->_request->getParam('content_fr'),
				'type'				=> $this->_request->getParam('type')
            );

            if($this->otherPageHelper->add($data))
            {
                echo JsonHelper::encodeContent('Add page success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot add page. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

    function deleteOtherPageAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->otherPageHelper->delete($id))
            {
                echo JsonHelper::encodeContent('Delete page success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function getFormEditOtherPageAction()
    {
        $id     = $this->_request->getParam('id');
        $row    = $this->otherPageHelper->getDetailOtherPage($id);

        $this->smarty->assign('row',$row);
        $content = $this->smarty->fetch($this->childTemplateDir.'form_edit_other_page.html');
        echo JsonHelper::encodeContent($content);
    }

	function updateOtherPageAction()
    {
        try
        {
			//should check page existed ?
            $id             = $this->_request->getParam('current_page_id');
            $title_en       = $this->_request->getParam('title');
			$title_fr       = $this->_request->getParam('title_fr');
            $title_unique   = $this->otherPageHelper->buildUniqueTitle($title_en);
			$current_unique_title = $this->_request->getParam('current_unique_title');
            if(!$this->otherPageHelper->isPageTitleUnique($title_unique, $current_unique_title))
            {
                echo JsonHelper::encodeContent("This title already exists.  Please use a unique title different from < $title_unique >", false);
                return false;
            }

            $data = array(
                'page_unique_title' => $title_unique,
                'title'			=> $this->otherPageHelper->trimTitle($title_en),
                'content'		=> $this->_request->getParam('content'),
				'title_fr'		=> $this->otherPageHelper->trimTitle($title_fr),
                'content_fr'	=> $this->_request->getParam('content_fr'),
                'date_modified'	=> new Zend_Db_Expr('now()')
            );

            if($this->otherPageHelper->updateOtherPage($id, $data))
            {
                echo JsonHelper::encodeContent('Update page success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Update page. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	//-----NEWS
	function newsAction()
	{
		$this->smarty->assign('navTitle','Manage News');
		$this->smarty->display($this->childTemplateDir.'manage_news.html');
	}

	function getListNewsAction()
    {
        $condition = array();

		if($this->_request->getParam('from_date'))
		{
			$condition[] = array('field'	=> 'date',
								'value'		=> date("Y-m-d", strtotime($this->_request->getParam('from_date'))),
								'operator'	=> '>=');
		}

        if($this->_request->getParam('to_date'))
		{
			$condition[] = array('field'	=> 'date',
								'value'		=> date("Y-m-d", strtotime($this->_request->getParam('to_date'))),
								'operator'	=> '<=');
		}

        if($this->_request->getParam('textSearch'))
		{
			$condition[] = array('field'	=> 'title',
								'value'		=> '%'.$this->_request->getParam('textSearch').'%',
								'operator'	=> 'LIKE');
		}

		$page = $this->_request->page;
        if(empty($condition))
        {
            $this->smarty->assign('list', $this->newsHelper->getList($page));
        }
        else
        {
            $this->smarty->assign('list', $this->newsHelper->getList($page, $condition));
        }
		$this->smarty->assign('paging',$this->newsHelper->paging);

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_news.html'));
    }

    function getFormAddNewsAction()
    {
        $content = $this->smarty->fetch($this->childTemplateDir.'form_add_news.html');
        echo JsonHelper::encodeContent($content);
    }

    function addNewsAction()
    {
        try
        {
            $data = array(
                'date'			=> date("Y-m-d", strtotime($this->_request->getParam('date'))),
                'title'			=> $this->newsHelper->trimTitle($this->_request->getParam('title')),
                'content'		=> $this->_request->getParam('content'),
				'title_fr'		=> $this->newsHelper->trimTitle($this->_request->getParam('title_fr')),
                'content_fr'	=> $this->_request->getParam('content_fr'),
            );

            if($this->newsHelper->add($data))
            {
                echo JsonHelper::encodeContent('Add news success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot add news. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

    function getFormEditNewsAction()
    {
        $id     = $this->_request->getParam('id');
        $row    = $this->newsHelper->getDetail($id);

        $this->smarty->assign('row',$row);
        $content = $this->smarty->fetch($this->childTemplateDir.'form_edit_news.html');
        echo JsonHelper::encodeContent($content);
    }

    function updateNewsAction()
    {
        try
        {
            //should check news existed ?
            $id             = $this->_request->getParam('current_page_id');
            $data = array(
                'date'              => date("Y-m-d", strtotime($this->_request->getParam('date'))),
                'title'             => $this->newsHelper->trimTitle($this->_request->getParam('title')),
				'content'			=> $this->_request->getParam('content'),
				'title_fr'			=> $this->newsHelper->trimTitle($this->_request->getParam('title_fr')),
				'content_fr'		=> $this->_request->getParam('content_fr'),
                'date_modified'     => new Zend_Db_Expr('now()')
            );

            if($this->newsHelper->update($id, $data))
            {
                echo JsonHelper::encodeContent('Update news success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Update news. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function deleteNewsAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->newsHelper->delete($id))
            {
                echo JsonHelper::encodeContent('Delete item success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	//-------------------SLIDE BANNER----------

	function bannerAction()
	{
		$this->smarty->assign('navTitle','Manage Slide Banner');
		$this->smarty->display($this->childTemplateDir.'manage_banner.html');
	}

	function getListBannerAction()
    {
        $condition = array();

        if($this->_request->getParam('urlSearch'))
		{
			$condition[] = array('field'	=> 'url',
								'value'		=> '%'.$this->_request->getParam('urlSearch').'%',
								'operator'	=> 'LIKE');
		}

		$page = $this->_request->page;
        if(empty($condition))
        {
            $this->smarty->assign('list', $this->bannerHelper->getList($page));
        }
        else
        {
            $this->smarty->assign('list', $this->bannerHelper->getList($page, $condition));
        }
		$this->smarty->assign('paging',$this->bannerHelper->paging);

		$this->smarty->assign('helper', $this->bannerHelper);

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_banner.html'));
    }

	function getFormAddBannerAction()
    {
        $content = $this->smarty->fetch($this->childTemplateDir.'form_add_banner.html');
        echo JsonHelper::encodeContent($content);
    }

	function doUploadAction()
	{
		//check size
		if($_FILES['image_file']['size'] > (1024 * 1024 * SLIDE_IMAGE_MAX_SIZE))
		{
			echo JsonHelper::encodeContent('File size is so big. We only allow maximum '.SLIDE_IMAGE_MAX_SIZE.'Mb.', false);
			return;
		}
		//assign extention to array
		$_FILES['image_file']['ext'] = $_POST['file_extension'];

		//do upload
		$fileName = $this->bannerHelper->doUploadImage($_FILES['image_file']);
		if($fileName == false)
		{
			echo JsonHelper::encodeContent('Cannot upload file. Please refesh and try again', false);
			return;
		}
		//success
		echo JsonHelper::encodeContent($fileName);
	}

	function addBannerAction()
    {
        try
        {
            $data = array(
                'url'	=> trim($this->_request->getParam('url')),
				'image'	=> $this->_request->getParam('file_name')
            );

            if($this->bannerHelper->add($data))
            {
                echo JsonHelper::encodeContent('Add record success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot record image. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function getFormEditBannerAction()
    {
        $id     = $this->_request->getParam('id');
        $row    = $this->bannerHelper->getDetail($id);

        $this->smarty->assign('row',$row);
		$this->smarty->assign('helper', $this->bannerHelper);

        $content = $this->smarty->fetch($this->childTemplateDir.'form_edit_banner.html');
        echo JsonHelper::encodeContent($content);
    }

	function updateBannerAction()
    {
        try
        {
            $id             = $this->_request->getParam('current_page_id');
			$replaceFile	= $this->_request->getParam('replaceFile');
            $data = array(
                'url'	=> trim($this->_request->getParam('url')),
				'image'	=> $this->_request->getParam('file_name')
            );

            if($this->bannerHelper->update($id, $data, $replaceFile))
            {
                echo JsonHelper::encodeContent('Update success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Update. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function deleteBannerAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->bannerHelper->delete($id))
            {
                echo JsonHelper::encodeContent('Delete item success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function reorderBannerAction()
    {
        try
        {
            if($this->bannerHelper->reOrder($this->_request))
            {
                echo JsonHelper::encodeContent('Reorder list success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	//-----Staff
	function staffAction()
	{
		$this->smarty->assign('navTitle','Manage Staff Account');
		$this->smarty->display($this->childTemplateDir.'manage_staff.html');
	}

	function getListStaffAction()
    {
        $condition = array();

        if($this->_request->getParam('usernameSearch'))
		{
			$condition[] = array('field'	=> 'username',
								'value'		=> '%'.$this->_request->getParam('usernameSearch').'%',
								'operator'	=> 'LIKE');
		}

        if($this->_request->getParam('emailSearch'))
		{
			$condition[] = array('field'	=> 'email',
								'value'		=> ''.$this->_request->getParam('emailSearch').'',
								'operator'	=> '=');
		}

        if($this->_request->getParam('status') != '')
		{
			$condition[] = array('field'	=> 'status',
								'value'		=> $this->_request->getParam('status'),
								'operator'	=> '=');
		}

		$page = $this->_request->page;
        if(empty($condition))
        {
            $this->smarty->assign('list', $this->staffHelper->getList($page));
        }
        else
        {
            $this->smarty->assign('list', $this->staffHelper->getList($page, $condition));
        }
		$this->smarty->assign('paging', $this->staffHelper->paging);
		$this->smarty->assign('helper', $this->staffHelper);

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_staff.html'));
    }

	function deleteStaffAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->staffHelper->delete($id))
            {
                echo JsonHelper::encodeContent('Delete item success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Can not Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Can not Process. Error : '.$e->getMessage(), false);
        }
    }

    function getStaffEditAction()
    {
        $id = $this->_request->getParam('id');
        $row = $this->staffHelper->getDetail($id);
        if(!$row)
        {
            die('Staff not existed');
        }

        $this->smarty->assign('row', $row);
        $this->smarty->assign('helper', $this->staffHelper);
		$content = $this->smarty->fetch($this->childTemplateDir.'form_edit_staff.html');
		echo JsonHelper::encodeContent($content);
    }

    function changeStaffStatusAction()
    {
        try
        {
            $id     = $this->_request->getParam('id');
            $status = $this->_request->getParam('status');

            if($this->staffHelper->changeStatus($id, $status))
            {
                echo JsonHelper::encodeContent('Change status success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Can not Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Can not Process. Error : '.$e->getMessage(), false);
        }
    }

	function updateStaffAction()
    {
        try
        {
            $id				= $this->_request->getParam('current_page_id');
			$current		= $this->staffHelper->getDetail($id);
			$newUsername	= $this->_request->getParam('username');
			$newEmail		= $this->_request->getParam('email');

			if(!$this->staffHelper->isUsernameExsited($newUsername, $current['username']))
			{
				echo JsonHelper::encodeContent("Can not Update. Username $newUsername is existed", false);
				return;
			}

			if(!$this->staffHelper->isEmailExsited($newEmail, $current['email']))
			{
				echo JsonHelper::encodeContent("Can not Update. Email $newEmail is existed", false);
				return;
			}

            $data = array(
                'firstname'			=> $this->_request->getParam('firstname'),
				'lastname'			=> $this->_request->getParam('lastname'),
				'phone'				=> $this->_request->getParam('phone'),
				'username'			=> $newUsername,
				'email'				=> $newEmail,
				'date_modified'     => new Zend_Db_Expr('now()')
            );

			//set password if new password entered
			if($this->_request->getParam('password') != '')
			{
				$data['password'] = $this->staffHelper->buildPasswordHash($this->_request->getParam('password'));
			}

            if($this->staffHelper->backendUpdate($id, $data))
            {
                echo JsonHelper::encodeContent('Update record success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Can not Update record. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Can not Process. Error : '.$e->getMessage(), false);
        }
    }
//
//    /**
//     * export Staff to excel
//     */
//    function doExportStaffAction()
//    {
//        $this->staffHelper->doExport(EU_FRIENDLY_NAME);
//    }

	//-----Gallery
	function galleryAction()
	{
		$this->smarty->assign('navTitle','Manage Gallery');
		$this->smarty->display($this->childTemplateDir.'manage_gallery.html');
	}

	function getComboAlbumAction()
	{
        echo JsonHelper::encodeContent($this->galleryHelper->getComboAlbum());
	}

	function getListAlbumAction()
    {
        $condition = array();

        if($this->_request->getParam('albumNameSearch'))
		{
			$condition[] = array('field'	=> 'name',
								'value'		=> '%'.$this->_request->getParam('albumNameSearch').'%',
								'operator'	=> 'LIKE');
		}

        if($this->_request->getParam('status') != '')
		{
			$condition[] = array('field'	=> 'status',
								'value'		=> $this->_request->getParam('status'),
								'operator'	=> '=');
		}

		$page = $this->_request->page;
        if(empty($condition))
        {
            $this->smarty->assign('list', $this->galleryHelper->getListAlbum($page));
        }
        else
        {
            $this->smarty->assign('list', $this->galleryHelper->getListAlbum($page, $condition));
        }
		$this->smarty->assign('paging', $this->galleryHelper->paging);
		$this->smarty->assign('helper', $this->galleryHelper);

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_album.html'));
    }

	function getFormAddAlbumAction()
    {
        $content = $this->smarty->fetch($this->childTemplateDir.'form_add_album.html');
        echo JsonHelper::encodeContent($content);
    }

	function doUploadAlbumCoverAction()
	{
		//check size
		if($_FILES['image_file']['size'] > (1024 * 1024 * ALBUM_IMAGE_MAX_SIZE))
		{
			echo JsonHelper::encodeContent('File size is so big. We only allow maximum '.ALBUM_IMAGE_MAX_SIZE.'Mb.', false);
			return;
		}
		//assign extention to array
		$_FILES['image_file']['ext'] = $_POST['file_extension'];

		//do upload
		$fileName = $this->galleryHelper->doUploadImageAlbumCover($_FILES['image_file']);
		if($fileName === false)
		{
			echo JsonHelper::encodeContent('Cannot upload file. Please refesh and try again', false);
			return;
		}

		//success
		echo JsonHelper::encodeContent($fileName);
	}

	function addAlbumAction()
    {
        try
        {
            $data = array(
                'name'			=> trim($this->_request->getParam('name')),
				'description'	=> $this->_request->getParam('description'),
				'name_fr'		=> trim($this->_request->getParam('name_fr')),
				'description_fr'=> $this->_request->getParam('description_fr'),
				'image'			=> $this->_request->getParam('file_name'),
            );

            if($this->galleryHelper->addAlbum($data))
            {
                echo JsonHelper::encodeContent('Add album success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot add album. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function deleteAlbumAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->galleryHelper->deleteAlbum($id))
            {
                echo JsonHelper::encodeContent('Delete item success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function reorderAlbumAction()
    {
        try
        {
            if($this->galleryHelper->reOrderAlbum($this->_request))
            {
                echo JsonHelper::encodeContent('Reorder list success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function getFormEditAlbumAction()
    {
        $id     = $this->_request->getParam('id');
        $row    = $this->galleryHelper->getDetailAlbum($id);

        $this->smarty->assign('row',$row);
		$this->smarty->assign('helper', $this->galleryHelper);

        $content = $this->smarty->fetch($this->childTemplateDir.'form_edit_album.html');
        echo JsonHelper::encodeContent($content);
    }

	function updateAlbumAction()
    {
        try
        {
            $id             = $this->_request->getParam('idRecord');
			$replaceFile	= $this->_request->getParam('replaceFile');
            $data = array(
                'name'			=> trim($this->_request->getParam('name')),
				'description'	=> $this->_request->getParam('description'),
				'name_fr'		=> trim($this->_request->getParam('name_fr')),
				'description_fr'=> $this->_request->getParam('description_fr'),
				'image'			=> $this->_request->getParam('file_name'),
				'order_num'		=> intval($this->_request->getParam('order_num')),
				'status'		=> intval($this->_request->getParam('status'))
            );

            if($this->galleryHelper->updateAlbum($id, $data, $replaceFile))
            {
                echo JsonHelper::encodeContent('Update success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Update. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function getListImageAction()
    {
        $condition = array();

        if($this->_request->getParam('albumSearch') != '')
		{
			$condition[] = array('field'	=> 'id_album',
								'value'		=> $this->_request->getParam('albumSearch'),
								'operator'	=> '=');
		}

        if($this->_request->getParam('titleSearch') != '')
		{
			$condition[] = array('field'	=> 'title',
								'value'		=> '%'.$this->_request->getParam('titleSearch').'%',
								'operator'	=> 'LIKE');
		}

		$page = $this->_request->page;
        if(empty($condition))
        {
            $this->smarty->assign('list', $this->galleryHelper->getListImage($page));
        }
        else
        {
            $this->smarty->assign('list', $this->galleryHelper->getListImage($page, $condition));
        }
		$this->smarty->assign('paging', $this->galleryHelper->paging);
		$this->smarty->assign('helper', $this->galleryHelper);

		$this->smarty->assign('albumSelected', $this->_request->getParam('albumSearch') != '');

        echo JsonHelper::encodeContent($this->smarty->fetch($this->childTemplateDir.'list_image.html'));
    }

	function getFormAddImageAction()
    {
		$this->smarty->assign('comboAlbum', $this->galleryHelper->getComboAlbum(null, 'id_album'));

        $content = $this->smarty->fetch($this->childTemplateDir.'form_add_image.html');
        echo JsonHelper::encodeContent($content);
    }

	function doUploadImageAction()
	{
		//check size
		if($_FILES['image_file']['size'] > (1024 * 1024 * ALBUM_IMAGE_MAX_SIZE))
		{
			echo JsonHelper::encodeContent('File size is so big. We only allow maximum '.ALBUM_IMAGE_MAX_SIZE.'Mb.', false);
			return;
		}
		//assign extention to array
		$_FILES['image_file']['ext'] = $_POST['file_extension'];

		//do upload
		$fileName = $this->galleryHelper->doUploadImage($_FILES['image_file']);
		if($fileName === false)
		{
			echo JsonHelper::encodeContent('Cannot upload file. Please refesh and try again', false);
			return;
		}

		//success
		echo JsonHelper::encodeContent($fileName);
	}

	function addImageAction()
    {
        try
        {
            $data = array(
				'id_album'		=> trim($this->_request->getParam('id_album')),
				'title'			=> trim($this->_request->getParam('title')),
				'description'	=> $this->_request->getParam('description'),
				'title_fr'		=> trim($this->_request->getParam('title_fr')),
				'description_fr'=> $this->_request->getParam('description_fr'),
				'image'			=> $this->_request->getParam('file_name'),
            );

            if($this->galleryHelper->addImage($data))
            {
                echo JsonHelper::encodeContent('Add image success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot add image. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function deleteImageAction()
    {
        try
        {
            $id  = $this->_request->getParam('id');

            if($this->galleryHelper->deleteImage($id))
            {
                echo JsonHelper::encodeContent('Delete item success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function getFormEditImageAction()
    {
        $id     = $this->_request->getParam('id');
        $row    = $this->galleryHelper->getDetailImage($id);

		$this->smarty->assign('comboAlbum', $this->galleryHelper->getComboAlbum($row['id_album'], 'id_album', false));

        $this->smarty->assign('row',$row);
		$this->smarty->assign('helper', $this->galleryHelper);

        $content = $this->smarty->fetch($this->childTemplateDir.'form_edit_image.html');
        echo JsonHelper::encodeContent($content);
    }

	function updateImageAction()
    {
        try
        {
            $id             = $this->_request->getParam('idRecord');
			$replaceFile	= $this->_request->getParam('replaceFile');
            $data = array(
                'title'			=> trim($this->_request->getParam('title')),
				'description'	=> $this->_request->getParam('description'),
				'title_fr'		=> trim($this->_request->getParam('title_fr')),
				'description_fr'=> $this->_request->getParam('description_fr'),
				'image'			=> $this->_request->getParam('file_name'),
				'id_album'		=> intval($this->_request->getParam('id_album')),
				'order_num'		=> intval($this->_request->getParam('order_num')),
            );

            if($this->galleryHelper->updateImage($id, $data, $replaceFile))
            {
                echo JsonHelper::encodeContent('Update success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Update. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }

	function reorderImageAction()
    {
        try
        {
			if($this->_request->getParam('idAlbum')== '')
			{
				echo JsonHelper::encodeContent('Cannot order image. No album selected. Please try again', false);
				die;
			}

            if($this->galleryHelper->reOrderImage($this->_request))
            {
                echo JsonHelper::encodeContent('Reorder list success.', true);
            }
            else
            {
                echo JsonHelper::encodeContent('Cannot Process. Please try again', false);
            }
        }
        catch (Exception $e)
        {
            echo JsonHelper::encodeContent('Cannot Process. Error : '.$e->getMessage(), false);
        }
    }
}
<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminImageGallery extends pjAdmin
{
	public $sessionShow = 'pjShow_session';
	/***********Adding function*************/
    public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{

			$post_max_size = pjUtil::getPostMaxSize();
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
			{
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminImageGallery&action=pjActionIndex&err=AE05");
			}
			if (isset($_POST['image_gallery_create']))
			{
				
				
				$pjImageGalleryModel = pjImageGalleryModel::factory();
				
				$id = $pjImageGalleryModel->setAttributes($_POST)->insert()->getInsertId();
				
				if ($id !== false && (int) $id > 0)
				{
				
					$pjMultiLangModel = pjMultiLangModel::factory();
					/**
					 * @desc Below section use for multi language purpose
					 */
					if (isset($_POST['i18n']))
					{
						$pjMultiLangModel->saveMultiLang($_POST['i18n'], $id, 'pjImageGallery', 'data');
					
						if(isset($_POST['index_arr']) && $_POST['index_arr'] != '')
						{
							$index_arr = explode("|", $_POST['index_arr']);
							foreach($index_arr as $k => $v)
							{
								if(strpos($v, 'fd') !== false)
								{
									$p_data = array();
									$p_data['gallery_image_id'] = $id;
							
								}
							}
						}
					
					}
					
					if (isset($_FILES['gallery_image']))
					{
						if($_FILES['gallery_image']['error'] == 0)
						{
							if(getimagesize($_FILES['gallery_image']["tmp_name"]) != false)
							{
								if (is_writable('app/web/upload/gallery_images'))
								{
									$Image = new pjImage();
									if ($Image->getErrorCode() !== 200)
									{
										$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
										if ($Image->load($_FILES['gallery_image']))
										{
											$resp = $Image->isConvertPossible();
											if ($resp['status'] === true)
											{
												$hash = md5(uniqid(rand(), true));
												$image_path = PJ_UPLOAD_PATH . 'gallery_images/' . $id . '_' . $hash . '.' . $Image->getExtension();
												
												$Image->loadImage($_FILES['gallery_image']["tmp_name"]);
												$Image->resizeSmart(220, 320);
												$Image->saveImage($image_path);
												$data = array();
												$data['gallery_image'] = $image_path;
																			
												$pjImageGalleryModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
											}
										}
									}
								}else{
									pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=$id&err=AE11");
								}
							}else{
								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=$id&err=AE12");
							}
						}else if($_FILES['gallery_image']['error'] != 4){
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=$id&err=AE09");
						}

				
					}
					
					$err = 'AE03';
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=$id&err=$err");
				} else {
					$err = 'AE04';
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionIndex&err=$err");
				}
				
			} else {
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file'];
				}
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminImageGallery.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	/***********Ajax Fetching function*************/
	public function pjActionGetGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjImageGalleryModel = pjImageGalleryModel::factory()
							->join('pjMultiLang', "t2.model='pjImageGallery' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjImageGalleryModel->where('t2.content LIKE', "%$q%");
			}
			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjImageGalleryModel->where('t1.status', $_GET['status']);
			}
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjImageGalleryModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = array();
			
			$data = $pjImageGalleryModel
				->select(" t1.id, t1.gallery_image, t1.created, t1.status, t2.content as name")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	/***********Call List Page*************/
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminImageGallery.js');
		} else {
			$this->set('status', 2);
		}
	}
	/***********Ajax Change Status function*************/
	public function pjActionSaveGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjImageGalleryModel = pjImageGalleryModel::factory();
			if (!in_array($_POST['column'], $pjImageGalleryModel->i18n))
			{
				$value = $_POST['value'];
				
				$pjImageGalleryModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $value));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjImageGallery', 'data');
			}
		}
		exit;
	}
	/***********Edit And Update function*************/
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$post_max_size = pjUtil::getPostMaxSize();
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
			{
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminImageGallery&action=pjActionIndex&err=AE06");
			}	
			if (isset($_POST['image_gallery_update']))
			{
				$pjImageGalleryModel = pjImageGalleryModel::factory();
				//$pjSeatModel = pjSeatModel::factory();
				
				$arr = $pjImageGalleryModel->find($_POST['id'])->getData();
				if (empty($arr))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionIndex&err=AE08");
				}
				
				$data = array();
				
				if (isset($_FILES['gallery_image']))
				{
					if($_FILES['gallery_image']['error'] == 0)
					{
						if(getimagesize($_FILES['gallery_image']["tmp_name"]) != false)
						{
							if (is_writable('app/web/upload/gallery_images'))
							{
								if (file_exists(PJ_INSTALL_PATH . $arr['gallery_image']))
								{
									@unlink(PJ_INSTALL_PATH . $arr['gallery_image']);
								}
								
								$Image = new pjImage();
								if ($Image->getErrorCode() !== 200)
								{
									$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
									if ($Image->load($_FILES['gallery_image']))
									{
										$resp = $Image->isConvertPossible();
										if ($resp['status'] === true)
										{
											$hash = md5(uniqid(rand(), true));
											$image_path = PJ_UPLOAD_PATH . 'gallery_images/' . $_POST['id'] . '_' . $hash . '.' . $Image->getExtension();
											
											$Image->loadImage($_FILES['gallery_image']["tmp_name"]);
											$Image->resizeSmart(220, 320);
											$Image->saveImage($image_path);
											$data['gallery_image'] = $image_path;
											
										}
									}
								}
							}else{
								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=".$_POST['id']."&err=AE11");
							}
						}else{
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=".$_POST['id']."&err=AE12");
						}
					}else if($_FILES['gallery_image']['error'] != 4){
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=".$_POST['id']."&err=AE10");
					}
				}
				
				$pjImageGalleryModel->reset()->where('id', $_POST['id'])->limit(1)->modifyAll(array_merge($_POST, $data));
				
				$pjMultiLangModel = pjMultiLangModel::factory();
				
					
				if (isset($_POST['i18n']))
				{
					$pjMultiLangModel->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjImageGallery', 'data');
					
					if(isset($_POST['index_arr']) && $_POST['index_arr'] != '')
					{
						$index_arr = explode("|", $_POST['index_arr']);
						foreach($index_arr as $k => $v)
						{
							if(strpos($v, 'fd') !== false)
							{
								$p_data = array();
								
							}else{
								foreach ($_POST['i18n'] as $locale => $locale_arr)
								{
									foreach ($locale_arr as $field => $content)
									{
										if(is_array($content))
										{
											$sql = sprintf("INSERT INTO `%1\$s` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
												VALUES (NULL, :foreign_id, :model, :locale, :field, :update_content, :source)
												ON DUPLICATE KEY UPDATE `content` = :update_content, `source` = :source;",
													$pjMultiLangModel->getTable()
											);
											$foreign_id = $v;
											$model = 'pjPrice';
											$source = 'data';
											$update_content = $content[$v];
											$modelObj = $pjMultiLangModel->reset()->prepare($sql)->exec(compact('foreign_id', 'model', 'locale', 'field', 'update_content', 'source'));
											if ($modelObj->getAffectedRows() > 0 || $modelObj->getInsertId() > 0)
											{
													
											}
										}
									}
								}
							}
						}
					}
				}
				if(isset($_POST['remove_arr']) && $_POST['remove_arr'] != '')
				{
					$remove_arr = explode("|", $_POST['remove_arr']);
					$pjMultiLangModel->reset()->where('model', 'pjPrice')->whereIn('foreign_id', $remove_arr)->eraseAll();
					//$pjPriceModel->reset()->whereIn('id', $remove_arr)->eraseAll();
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionUpdate&id=".$_POST['id']."&err=AE01");
				
			} else {
				$pjMultiLangModel = pjMultiLangModel::factory();
				
				$arr = pjImageGalleryModel::factory()->find($_GET['id'])->getData();
				// echo "<pre>"; print_r($arr);
				if (count($arr) === 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminImageGallery&action=pjActionIndex&err=AE08");
				}
				$arr['i18n'] = $pjMultiLangModel->getMultiLang($arr['id'], 'pjImageGallery');
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file'];
				}
				
				// $price_arr = pjPriceModel::factory()->where('event_id', $_GET['id'])->findAll()->getData();
				// foreach($price_arr as $k => $v)
				// {
				// 	$price_arr[$k]['i18n'] = $pjMultiLangModel->reset()->getMultiLang($v['id'], 'pjPrice');
					
				// }
				// $this->set('price_arr', $price_arr);
				
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				$this->set('arr', $arr);
			
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminImageGallery.js');
			}
		} else {
			$this->set('status', 2);
		}
	}

	/*******Only Delete image from edit page********/
	public function pjActionDeleteImage()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
				
			$pjImageGalleryModel = pjImageGalleryModel::factory();
			$arr = $pjImageGalleryModel->find($_GET['id'])->getData();
				
			if(!empty($arr))
			{
				if(!empty($arr['gallery_image']))
				{
					@unlink(PJ_INSTALL_PATH . $arr['gallery_image']);
				}
	
				$data = array();
				$data['gallery_image'] = ':NULL';
				$pjImageGalleryModel->reset()->where(array('id' => $_GET['id']))->limit(1)->modifyAll($data);
	
				$response['code'] = 200;
			}else{
				$response['code'] = 100;
			}
				
			pjAppController::jsonResponse($response);
		}
	}
	/***********Single Delete function*************/
	public function pjActionDeleteGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			
			if (pjImageGalleryModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjImageGallery')->where('foreign_id', $_GET['id'])->eraseAll();
				
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	/***********Multi Delete function*************/
	public function pjActionDeleteGalleryBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjImageGalleryModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjImageGallery')->whereIn('foreign_id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
}
?>

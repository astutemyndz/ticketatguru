<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminVideo extends pjAdmin
{
	public $sessionShow = 'pjShow_session';
	/***********Adding function*************/
    public function pjActionCreate()
	{
		

			$post_max_size = pjUtil::getPostMaxSize();
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
			{
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminVideo&action=pjActionIndex&err=AVDO05");
			}
			if (isset($_POST['image_video_create']))
			{
					
				
				$pjVideoModel = pjVideoModel::factory();
				
				$id = $pjVideoModel->setAttributes($_POST)->insert()->getInsertId();
				
				if ($id !== false && (int) $id > 0)
				{
				
					$pjMultiLangModel = pjMultiLangModel::factory();
					/**
					 * @desc Below section use for multi language purpose
					 */
					if (isset($_POST['i18n']))
					{
						$pjMultiLangModel->saveMultiLang($_POST['i18n'], $id, 'pjVideo', 'data');
					
						if(isset($_POST['index_arr']) && $_POST['index_arr'] != '')
						{
							$index_arr = explode("|", $_POST['index_arr']);
							foreach($index_arr as $k => $v)
							{
								if(strpos($v, 'fd') !== false)
								{
									$p_data = array();
									$p_data['video_image_id'] = $id;
							
								}
							}
						}
					
					}
					
					if (isset($_FILES['video_path']))
					{
						if($_FILES['video_path']['error'] == 0)
						{
							// if(getimagesize($_FILES['video_path']["tmp_name"]) != false)
							// {
								if (is_writable('app/web/upload/home_video'))
								{
									$Image = new pjImage();
									if ($Image->getErrorCode() !== 200)
									{
										// $Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
										// if ($Image->load($_FILES['video_path']))
										// {
										// 	$resp = $Image->isConvertPossible();
										// 	if ($resp['status'] === true)
										// 	{
										// 		$hash = md5(uniqid(rand(), true));
										// 		$image_path = PJ_UPLOAD_PATH . 'home_video/' . $id . '_' . $hash . '.' . $Image->getExtension();
												
										// 		$Image->loadImage($_FILES['video_path']["tmp_name"]);
										// 		// $Image->resizeSmart(350, 150);
										// 		$Image->thumbnail(204, 204);
										// 		$Image->saveImage($image_path);
										// 		$data = array();
										// 		$data['video_path'] = $image_path;
																			
										// 		$pjVideoModel->reset()->where('id', $id)->limit(1)->modifyAll($data);
										// 	}
										// }
									$Image->setAllowedTypes(array('video/mp4','video/mov','video/avi','video/qt','video/mpeg','video/3gp'));
									if ($Image->load($_FILES['video_path']))
									{
										
										$resp = $Image->isConvertPossible();
										if ($resp['status'] === true)
										{
											$handle = new pjUpload();
											if ($handle->load($_FILES['video_path'])) 
											{
												$hash = md5(uniqid(rand(), true));
												$file_path = PJ_UPLOAD_PATH . 'home_video/' . $hash . '.' . $handle->getExtension();
												if($handle->save($file_path))
												{
													$data['video_path'] = $file_path;
												}
												$file_path = $arr['video_path'];
												if (file_exists(PJ_INSTALL_PATH . $file_path)) {
													@unlink(PJ_INSTALL_PATH . $file_path);
												}
											}
											
										}
										
									}else{
											pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=$id&err=AVDO12");
										}
									}
								}else{
									pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=$id&err=AVDO11");
								}
							// }else{
							// 	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=$id&err=AVDO12");
							// }
						}else if($_FILES['video_path']['error'] != 4){
							pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=$id&err=AVDO09");
						}

				
					}
					
					$err = 'AVDO03';
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=$id&err=$err");
				} else {
					$err = 'AVDO04';
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionIndex&err=$err");
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
				$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
				$this->appendJs('pjAdminVideo.js');
			}
		
	}
	/***********Ajax Fetching function*************/
	public function pjActionGetVideo()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjVideoModel = pjVideoModel::factory()
							->join('pjMultiLang', "t2.model='pjVideo' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjVideoModel->where('t2.content LIKE', "%$q%");
			}
			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjVideoModel->where('t1.status', $_GET['status']);
			}
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjVideoModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = array();
			
			$data = $pjVideoModel
				->select(" t1.id, t1.video_path, t1.mime_type, t1.created, t1.status, t2.content as name")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
			// echo "<pre>"; print_r($data);
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	/***********Call List Page*************/
	public function pjActionIndex()
	{
		
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminVideo.js');
	
	}
	/***********Ajax Change Status function*************/
	public function pjActionSaveVideo()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjVideoModel = pjVideoModel::factory();
			if (!in_array($_POST['column'], $pjVideoModel->i18n))
			{
				$value = $_POST['value'];
				
				$pjVideoModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $value));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjVideo', 'data');
			}
		}
		exit;
	}
	/***********Edit And Update function*************/
	public function pjActionUpdate()
	{
		
			$post_max_size = pjUtil::getPostMaxSize();
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size)
			{
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminVideo&action=pjActionIndex&err=AVDO06");
			}	
			if (isset($_POST['image_video_update']))
			{
				$pjVideoModel = pjVideoModel::factory();
				//$pjSeatModel = pjSeatModel::factory();
				
				$arr = $pjVideoModel->find($_POST['id'])->getData();
				if (empty($arr))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionIndex&err=AVDO08");
				}
				
				$data = array();
				
				if (isset($_FILES['video_path']))
				{
					if($_FILES['video_path']['error'] == 0)
					{
							if (is_writable('app/web/upload/home_video'))
							{
								if (file_exists(PJ_INSTALL_PATH . $arr['video_path']))
								{
									@unlink(PJ_INSTALL_PATH . $arr['video_path']);
								}
								$Image = new pjImage();
								if ($Image->getErrorCode() !== 200)
								{
									$Image->setAllowedTypes(array('video/mp4','video/mov','video/avi','video/qt','video/mpeg','video/3gp'));
									if ($Image->load($_FILES['video_path']))
									{
										
										$resp = $Image->isConvertPossible();
										// echo "<pre>"; print_r($_FILES);exit;
										// if ($resp['status'] === true)
										// {
										// 	$hash = md5(uniqid(rand(), true));
										// 	$image_path = PJ_UPLOAD_PATH . 'home_video/' . $_POST['id'] . '_' . $hash . '.' . $Image->getExtension();
											
										// 	$Image->loadImage($_FILES['video_path']["tmp_name"]);
										// 	$Image->thumbnail(204, 204);
										// 	$Image->saveImage($image_path);
										// 	$data['video_path'] = $image_path;
											
										// }
										$handle = new pjUpload();
										if ($handle->load($_FILES['video_path'])) 
										{
											$hash = md5(uniqid(rand(), true));
											$file_path = PJ_UPLOAD_PATH . 'home_video/' . $hash . '.' . $handle->getExtension();
											if($handle->save($file_path))
											{
												$data['video_path'] = $file_path;
												$data['file_name'] = $_FILES['video_path']['name'];
												$data['mime_type'] = $_FILES['video_path']['type'];
											}
											$file_path = $arr['video_path'];
											if (file_exists(PJ_INSTALL_PATH . $file_path)) {
												@unlink(PJ_INSTALL_PATH . $file_path);
											}
										}
									}
									else{
										pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=".$_POST['id']."&err=AVDO12");
									}
								}
							}else{
								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=".$_POST['id']."&err=AVDO11");
							}
						
					}else if($_FILES['video_path']['error'] != 4){
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=".$_POST['id']."&err=AVDO10");
					}
				}
				
				$pjVideoModel->reset()->where('id', $_POST['id'])->limit(1)->modifyAll(array_merge($_POST, $data));
				
				$pjMultiLangModel = pjMultiLangModel::factory();
									
				if (isset($_POST['i18n']))
				{
					$pjMultiLangModel->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjVideo', 'data');
					
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
							
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionUpdate&id=".$_POST['id']."&err=AVDO01");
				
			} else {
				$pjMultiLangModel = pjMultiLangModel::factory();
				
				$arr = pjVideoModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVideo&action=pjActionIndex&err=AVDO08");
				}
				$arr['i18n'] = $pjMultiLangModel->getMultiLang($arr['id'], 'pjVideo');
				
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
				$this->set('arr', $arr);
			
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
				$this->appendJs('pjAdminVideo.js');
			}
		
	}

	/*******Only Delete image from edit page********/
	public function pjActionDeleteImage()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
				
			$pjVideoModel = pjVideoModel::factory();
			$arr = $pjVideoModel->find($_GET['id'])->getData();
				
			if(!empty($arr))
			{
				if(!empty($arr['video_path']))
				{
					@unlink(PJ_INSTALL_PATH . $arr['video_path']);
				}
	
				$data = array();
				$data['video_path'] = ':NULL';
				$pjVideoModel->reset()->where(array('id' => $_GET['id']))->limit(1)->modifyAll($data);
	
				$response['code'] = 200;
			}else{
				$response['code'] = 100;
			}
				
			pjAppController::jsonResponse($response);
		}
	}
	/***********Single Delete function*************/
	public function pjActionDeleteVideo()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			
			if (pjVideoModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjVideo')->where('foreign_id', $_GET['id'])->eraseAll();
				
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	/***********Multi Delete function*************/
	public function pjActionDeleteVideoBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjVideoModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjVideo')->whereIn('foreign_id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
}
?>

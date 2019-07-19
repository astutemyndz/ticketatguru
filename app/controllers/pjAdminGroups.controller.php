<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminGroups extends pjAdmin
{
	public function pjActionCheckGroupName()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && isset($_GET['group_title']))
		{
			$pjGroupModel = pjGroupModel::factory();
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjGroupModel->where('t1.id !=', $_GET['id']);
			}
			echo $pjGroupModel->where('t1.group_title', $_GET['group_title'])->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['group_create']))
			{
				// echo "<pre>"; print_r($_POST);
				$pjGroupModel = pjGroupModel::factory();
				$id = $pjGroupModel->setAttributes($_POST)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AG03';
				} else {
					$err = 'AG04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminGroups&action=pjActionUpdate&id=$id&err=$err");
			} else {
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminGroups.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteGroup()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$response = array();
			if ($this->isAdmin())
			{
				if (pjGroupModel::factory()->reset()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
				{
					pjGroupSubscriberModel::factory()->where('group_id', $_GET['id'])->eraseAll();
					$response['code'] = 200;
				} else {
					$response['code'] = 100;
				}
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteGroupBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if ($this->isAdmin())
			{
				if (isset($_POST['record']) && count($_POST['record']) > 0)
				{
					pjGroupModel::factory()->reset()->whereIn('id', $_POST['record'])->eraseAll();
					pjGroupSubscriberModel::factory()->whereIn('group_id', $_POST['record'])->eraseAll();
				}
			}
		}
		exit;
	}
	
	public function pjActionExportGroup()
	{
		$this->checkLogin();
		
		if (isset($_POST['record']) && is_array($_POST['record']))
		{
			$arr = pjGroupModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Groups-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}
	
	public function pjActionGetGroup()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjGroupModel = pjGroupModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjGroupModel->where('t1.group_title LIKE', "%$q%");
			}

			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjGroupModel->where('t1.status', $_GET['status']);
			}
				
			$column = 'group_title';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjGroupModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$tbl1_name = pjGroupSubscriberModel::factory()->getTable();
			$tbl2_name = pjSubscriberModel::factory()->getTable();
			$data = $pjGroupModel
				->select("t1.*, (SELECT COUNT(*) FROM `".$tbl1_name."` as t2 WHERE t2.group_id = t1.id) as total,
								(SELECT COUNT(*) FROM `".$tbl1_name."` as t3 WHERE t3.group_id = t1.id AND t3.subscriber_id IN(SELECT t4.id FROM `".$tbl2_name."` AS t4 WHERE t4.subscribed='T') ) as subscribed,
								(SELECT COUNT(*) FROM `".$tbl1_name."` as t3 WHERE t3.group_id = t1.id AND t3.subscriber_id IN(SELECT t4.id FROM `".$tbl2_name."` AS t4 WHERE t4.subscribed='F') ) as unsubscribed")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();

			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminGroups.js');
			$this->appendJs('admin.php?controller=pjAdmin&action=pjActionMessages&page=list', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveGroup()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if($_POST['column'] == 'group_title')
			{
				if($_POST['value'] != '')
				{
					$pjGroupModel = pjGroupModel::factory();
					
					$check = $pjGroupModel->where('t1.group_title', $_POST['value'])->findCount()->getData() == 0 ? true : false;
					if($check == true)
					{
						$pjGroupModel->reset()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
					}
				}
			}else{
				pjGroupModel::factory()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			}
		}
		exit;
	}
	
	public function pjActionStatusGroup()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjGroupModel::factory()->whereIn('id', $_POST['record'])->modifyAll(array(
					'status' => ":IF(`status`='F','T','F')"
				));
			}
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$pjGroupModel = pjGroupModel::factory();
			if (isset($_POST['group_update']))
			{
				$data = array();
				if(isset($_POST['send_confirm']))
				{
					$data['send_confirm'] = 'T';
				}else{
					$data['send_confirm'] = 'F';
				}
				if(isset($_POST['send_response']))
				{
					$data['send_response'] = 'T';
				}else{
					$data['send_response'] = 'F';
				}
				unset($_POST['send_confirm']);
				unset($_POST['send_response']);
				
				$pjGroupModel->reset()->where('id', $_POST['id'])->limit(1)->modifyAll(array_merge($_POST, $data));
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminGroups&action=pjActionUpdate&id=" . $_POST['id'] . "&tab_id=" . $_POST['tab_id'] . "&err=AG01");
				
			} else {
				$tbl1_name = pjGroupSubscriberModel::factory()->getTable();
				$tbl2_name = pjSubscriberModel::factory()->getTable();
				$arr = $pjGroupModel
					->select("t1.*, (SELECT COUNT(*) FROM `".$tbl1_name."` as t2 WHERE t2.group_id = t1.id) as total,
								(SELECT COUNT(*) FROM `".$tbl1_name."` as t3 WHERE t3.group_id = t1.id AND t3.subscriber_id IN(SELECT t4.id FROM `".$tbl2_name."` AS t4 WHERE t4.subscribed='T') ) as subscribed,
								(SELECT COUNT(*) FROM `".$tbl1_name."` as t3 WHERE t3.group_id = t1.id AND t3.subscriber_id IN(SELECT t4.id FROM `".$tbl2_name."` AS t4 WHERE t4.subscribed='F') ) as unsubscribed")
					->find($_GET['id'])->getData();
					
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "admin.php?controller=pjAdminGroups&action=pjActionIndex&err=AG08");
				}
				$this->set('arr', $arr);
				
				$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('additional-methods.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminGroups.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
}
?>
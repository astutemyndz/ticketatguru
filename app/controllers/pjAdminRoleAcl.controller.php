<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminRoleAcl extends pjAdmin
{
	private function getPrivileges($data) {

		$privileges = (isset($data['privileges']) && count($data['privileges']) > 0) ? $data['privileges'] : [];
		$privilegesCount = (isset($data['privileges']) && count($data['privileges']) > 0) ? count($data['privileges']) : [];
		$role_id = (isset($data['role_id'])) ? $data['role_id'] : 0 ;

		$privilegesArr = array();

        if ($privilegesCount > 0) {
            foreach ($privileges as $id_module => $privilege) {
                $privilegesArr['is_visible'] 		= (isset($privilege['is_visible'])) ? $privilege['is_visible'] : 0;
                $privilegesArr['is_create'] 		= (isset($privilege['is_create'])) ? $privilege['is_create'] : 0;
                $privilegesArr['is_read'] 			= (isset($privilege['is_read'])) ? $privilege['is_read'] : 0;
                $privilegesArr['is_edit'] 			= (isset($privilege['is_edit'])) ? $privilege['is_edit']: 0;
                $privilegesArr['is_delete'] 		= (isset($privilege['is_delete'])) ? $privilege['is_delete'] : 0;
                $privilegesArr['id_tk_cbs_roles'] 	= $role_id;
                $privilegesArr['id_tk_cbs_modules'] = $id_module;
            }
		}
		
		if(isset($privilegesArr) && count($privilegesArr) > 0) {
			return $privilegesArr;
		}
		return $privilegesArr = array();
	}
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['role_acl']))
			{
				// echo "<pre>";
				// print_r($this->getPrivileges($_POST));
				// exit;
				$id = pjRoleAclModel::factory(array_merge($_POST, $this->getPrivileges($_POST)))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AU03';
				} else {
					$err = 'AU04';
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminRoleAcl&action=pjActionIndex&err=$err");
			} else {
				$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				$this->set('modules', pjModuleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				$this->set('role_acl_arr', pjRoleAclModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
		
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminRoleAcl.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	/*
	public function pjActionDeleteUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if ($_GET['id'] != $this->getUserId() && $_GET['id'] != 1)
			{
				if (pjUserModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
				{
					$response['code'] = 200;
				} else {
					$response['code'] = 100;
				}
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteUserBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjUserModel::factory()
					->where('id !=', $this->getUserId())
					->where('id !=', 1)
					->whereIn('id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionExportUser()
	{
		$this->checkLogin();
		
		if (isset($_POST['record']) && is_array($_POST['record']))
		{
			$arr = pjUserModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Users-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}
	
	public function pjActionGetUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjUserModel = pjUserModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjUserModel->where('t1.email LIKE', "%$q%");
				$pjUserModel->orWhere('t1.name LIKE', "%$q%");
			}

			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjUserModel->where('t1.status', $_GET['status']);
			}
				
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjUserModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = array();
			
			$data = $pjUserModel->select('t1.id, t1.email, t1.name, t1.created, t1.status, t1.is_active, t1.role_id, t2.role')
				->join('pjRole', 't2.id=t1.role_id', 'left')
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
			foreach($data as $k => $v)
			{
				$v['created'] = date($this->option_arr['o_date_format'], strtotime($v['created'])) . ', ' . date($this->option_arr['o_time_format'], strtotime($v['created']));
				$data[$k] = $v;
			}	
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	*/
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminRoleAcl.js');
		} else {
			$this->set('status', 2);
		}
	}
	

	public function pjActionSaveRoleAcl()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjRoleAclModel = pjRoleAclModel::factory();
			if (!in_array($_POST['column'], $pjRoleAclModel->i18n))
			{
				$value = $_POST['value'];
				$pjRoleAclModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $value));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjRoleAcl', 'data');
			}
		}
		exit;
	}
	/*
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
				
			if (isset($_POST['user_update']))
			{
				pjUserModel::factory()->where('id', $_POST['id'])->limit(1)->modifyAll($_POST);
				
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminUsers&action=pjActionIndex&err=AU01");
				
			} else {
				$arr = pjUserModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "admin.php?controller=pjAdminUsers&action=pjActionIndex&err=AU08");
				}
				$this->set('arr', $arr);
				
				$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminUsers.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	*/
}
?>
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
		//$this->checkLogin();
		
		if (App::isSuperAdmin())
		{
			if (isset($_POST['role_acl']))
			{
				$roleData = array();
				$id = NULL;
				$roleData['is_superadmin'] = 0;
				$roleId = pjRoleModel::factory(array_merge($_POST, $roleData))->insert()->getInsertId();

				$data = array();
				$data['role_id'] = $roleId;
				$data['privileges'] = $_POST['privileges'];

			
				if(isset($roleId) && !empty($roleId)) {
					$id = pjRoleAclModel::factory(array_merge($_POST, $this->getPrivileges($data)))->insert()->getInsertId();
				}
				
				
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AP01';
				} else {
					$err = 'AP03';
				}
				//Refresh Session Roles
				$pjRoleAclModel = pjRoleAclModel::factory();
				$roles = $pjRoleAclModel->select("t2.name, t2.path, t2.controller, t1.is_visible, t1.is_create, t1.is_read, t1.is_edit, t1.is_delete")
										->join('pjModule', 't2.id = t1.id_tk_cbs_modules', 'left outer')
										->where('t1.id_tk_cbs_roles =', App::getRoleId())
										->findAll()
										->getData();
				App::setSession('roles', $roles);
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
	*/
	public function pjActionGetRole()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjAdminRoleAcl = pjRoleModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjAdminRoleAcl->where('t1.role LIKE', "%$q%");
			}
		
			$column = 'role';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjAdminRoleAcl->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = array();
			
			$data = $pjAdminRoleAcl->select('t1.id, t1.role, t1.is_superadmin, t1.status')
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
			foreach($data as $k => $v)
			{
				$data[$k] = $v;
			}	
			// echo "<pre>";
			// print_r($data);
			// exit;
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if (App::isSuperAdmin())
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
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if (App::isSuperAdmin())
		{
			$id = NULL;
			$response = array();
			if (isset($_POST['role_acl_update']))
			{
				$role_id = (isset($_POST['role_id'])) ? $_POST['role_id'] : '';
				pjRoleModel::factory()->where('id =', $role_id)->modifyAll($_POST);
				
				
				if(pjRoleAclModel::factory()->where('id_tk_cbs_roles =', $role_id)->eraseAll())
				{
					$response['code'] = 200;
				} else {
					$response['code'] = 100;
				}
				$priv = $_POST['privileges'];
				
				if($response['code'] == 200) {
					if ($priv) {

						foreach ($priv as $id_modul => $data) {
							//echo $role_id;
							$currentPermission = pjRoleAclModel::factory()->where('id_tk_cbs_modules =', $id_modul)->where('id_tk_cbs_roles =', $role_id)->findAll()->getData();
							// echo "<pre>";
							// print_r($currentPermission);
							// exit;
							$currentPermission = $currentPermission[0];
							
							if ($currentPermission) {

								$privilegesArr = [];
								$privilegesArr['is_visible'] 		= (isset($data['is_visible'])) ? $data['is_visible'] : 0;
								$privilegesArr['is_create'] 		= (isset($data['is_create'])) ? $data['is_create'] : 0;
								$privilegesArr['is_read'] 			= (isset($data['is_read'])) ? $data['is_read'] : 0;
								$privilegesArr['is_edit'] 			= (isset($data['is_edit'])) ? $data['is_edit']: 0;
								$privilegesArr['is_delete'] 		= (isset($data['is_delete'])) ? $data['is_delete'] : 0;

								pjRoleAclModel::factory()->where('id =', $currentPermission['id'])->modifyAll($privilegesArr);
								$err = 'AP02';
							} else {
								//echo "inserted";
								$privilegesArr = [];
								$privilegesArr['is_visible'] 		= (isset($data['is_visible'])) ? $data['is_visible'] : 0;
								$privilegesArr['is_create'] 		= (isset($data['is_create'])) ? $data['is_create'] : 0;
								$privilegesArr['is_read'] 			= (isset($data['is_read'])) ? $data['is_read'] : 0;
								$privilegesArr['is_edit'] 			= (isset($data['is_edit'])) ? $data['is_edit']: 0;
								$privilegesArr['is_delete'] 		= (isset($data['is_delete'])) ? $data['is_delete'] : 0;
								$privilegesArr['id_tk_cbs_roles'] 	= $role_id;
								$privilegesArr['id_tk_cbs_modules'] = $id_modul;
								
								pjRoleAclModel::factory($privilegesArr)->insert();
								$err = 'AP02';
							}
							
						}
						
					}
			
					//Refresh Session Roles
					if ($role_id == App::getRoleId()) {

						$pjRoleAclModel = pjRoleAclModel::factory();
						$roles = $pjRoleAclModel->select("t2.name, t2.path, t1.is_visible, t1.is_create, t1.is_read, t1.is_edit, t1.is_delete")
													->join('pjModule', 't1.id_tk_cbs_modules = t2.id', 'left outer')
													->where('id_tk_cbs_roles =', App::getRoleId())
													->findAll()
													->getData();
						App::setSession($this->defaultUser['user_privileges_roles'], $roles);
			
					}
					//$id = pjRoleAclModel::factory(array_merge($_POST, $this->getPrivileges($_POST)))->insert()->getInsertId();
					// if ($id !== false && (int) $id > 0)
					// {
					// 	$err = 'AP02';
					// } else {
					// 	$err = 'AP04';
					// }
				}
			
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminRoleAcl&action=pjActionIndex&err=$err");
				
			} else {
				if(isset($_GET['id'])) {
					$id = $_GET['id'];
				}
				$arr = pjRoleModel::factory()->find($_GET['id'])->getData();

				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "admin.php?controller=pjAdminRoleAcl&action=pjActionIndex&err=AP08");
				}
				$this->set('arr', $arr);
			
				$this->set('id', $id);
				
				$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				$this->set('modules', pjModuleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
			
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminRoleAcl.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
}
?>
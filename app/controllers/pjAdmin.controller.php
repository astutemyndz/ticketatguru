<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController
{
	public $defaultUser = 'user';
	
	public $requireLogin = true;
	
	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			if (!$this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview', 'pjActionExportFeed')))
			{
				// echo 1;
				// exit;
				if (!$this->isXHR())
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
				} else {
					pjAppController::jsonResponse(array('redirect' => PJ_INSTALL_URL . "admin.php?controller=pjAdmin&action=pjActionLogin"));
				}
			} else {
				// App::printSession();
				// exit;
			}
			
		}
	}
	
	public function afterFilter()
	{
		parent::afterFilter();
		if ($this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin')))
		{
			$this->appendJs('admin.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		}
	}
	
	public function beforeRender()
	{
		
	}
		
	public function pjActionIndex()
	{
		
			
			$pjShowModel = pjShowModel::factory();
			
			$_movies_today = $pjShowModel
				->select("COUNT(DISTINCT t1.event_id) AS cnt")
				->where("DATE_FORMAT(t1.date_time,'%Y-%m-%d') = '".date('Y-m-d')."' AND t1.event_id IN (SELECT TE.id FROM `".pjEventModel::factory()->getTable()."` AS TE WHERE TE.status='T')")
				->findAll()
				->getData();
			$cnt_movies_today = !empty($_movies_today) ? $_movies_today[0]['cnt'] : 0;
			
			$cnt_bookings_today = pjBookingModel::factory()
				->where("DATE_FORMAT(t1.created,'%Y-%m-%d') = '".date('Y-m-d')."'")
				->findCount()
				->getData();	
			$cnt_halls = pjVenueModel::factory()->where('t1.status', 'T')->findCount()->getData();	
			
			$next_movies =$pjShowModel
				->reset()
				->select("DISTINCT t1.event_id, t1.date_time, t2.content as title, (SELECT SUM(TBS.cnt) FROM `".pjBookingShowModel::factory()->getTable()."` AS TBS WHERE TBS.booking_id IN (SELECT TB.id FROM `".pjBookingModel::factory()->getTable()."` AS TB WHERE TB.event_id=t1.event_id AND TB.date_time=t1.date_time) ) as cnt_tickets")
				->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.event_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where("t1.date_time > NOW()")
				->limit(3)
				->orderBy("t1.date_time ASC")
				->findAll()
				->getData();

			$latest_bookings = pjBookingModel::factory()
				->select("t1.*, (SELECT SUM(TBS.cnt) FROM `".pjBookingShowModel::factory()->getTable()."` AS TBS WHERE TBS.booking_id=t1.id) as cnt_tickets")
				->limit(5)
				->orderBy("t1.created DESC")
				->findAll()
				->getData();
			$now_showing = $pjShowModel
				->reset()				
				->select(sprintf("t1.event_id, t2.content AS `title`, t3.duration, t4.content AS `hall`,
					(SELECT SUM(TBS.cnt) 
						FROM `%1\$s` AS TBS 
						WHERE TBS.show_id=MIN(t1.id) 
						AND TBS.booking_ID IN (SELECT `id` FROM `%2\$s` WHERE `status`='confirmed') 
					) AS `cnt_people`",
					pjBookingShowModel::factory()->getTable(), pjBookingModel::factory()->getTable()
				))
				->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.event_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjEvent', "t1.event_id=t3.id", "left")
				->join('pjMultiLang', "t4.model='pjVenue' AND t4.foreign_id=t1.venue_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left outer')
				->where("t1.date_time <= NOW() AND NOW() <= DATE_ADD(t1.date_time, INTERVAL t3.duration MINUTE)")
				->limit(5)
				->groupBy("t1.event_id, t1.venue_id, t1.date_time, `title`, t3.duration, `hall`")
				->orderBy("t1.date_time ASC")
				->findAll()
				->getData();
			
			$this->set('cnt_movies_today', $cnt_movies_today);
			$this->set('cnt_bookings_today', $cnt_bookings_today);
			$this->set('cnt_halls', $cnt_halls);
			$this->set('next_movies', pjSanitize::clean($next_movies));
			$this->set('latest_bookings', pjSanitize::clean($latest_bookings));
			$this->set('now_showing', pjSanitize::clean($now_showing));
			// echo App::getRoleId();
			// exit;
		
	}
	
	public function pjActionForgot()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['forgot_user']))
		{
			if (!isset($_POST['forgot_email']) || !pjValidation::pjActionNotEmpty($_POST['forgot_email']) || !pjValidation::pjActionEmail($_POST['forgot_email']))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			}
			$pjUserModel = pjUserModel::factory();
			$user = $pjUserModel
				->where('t1.email', $_POST['forgot_email'])
				->limit(1)
				->findAll()
				->getData();
				
			if (count($user) != 1)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			} else {
				$user = $user[0];
				
				$Email = new pjEmail();
				$Email
					->setTo($user['email'])
					->setFrom($user['email'])
					->setSubject(__('emailForgotSubject', true));
				
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass'])
						->setSender($this->option_arr['o_smtp_user'])
					;
				}
				
				$body = str_replace(
					array('{Name}', '{Password}'),
					array($user['name'], $user['password']),
					__('emailForgotBody', true)
				);

				if ($Email->send($body))
				{
					$err = "AA11";
				} else {
					$err = "AA12";
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=$err");
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionMessages()
	{
		$this->setAjax(true);
		header("Content-Type: text/javascript; charset=utf-8");
	}
	
	public function pjActionLogin()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['login_user']))
		{
			//session_destroy();
			//exit;
			if (!isset($_POST['login_email']) || !isset($_POST['login_password']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_email']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_password']) ||
				!pjValidation::pjActionEmail($_POST['login_email']))
			{				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=4");
			}
			//echo $_POST['login_email']."=>".$_POST['login_password'];die;
			$pjUserModel = pjUserModel::factory();

			$user = $pjUserModel
				->where('t1.email', $_POST['login_email'])
				->where(sprintf("t1.password = AES_ENCRYPT('%s', '%s')", pjObject::escapeString($_POST['login_password']), PJ_SALT))
				->limit(1)
				->findAll()
				->getData();
			//echo "<pre>"; print_r($user);die;	
			if (count($user) != 1)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=1");
			} else {
				$user = $user[0];
				unset($user['password']);
				
				$priv = pjRoleModel::factory()->find($user['role_id'])->getData();//("id =", $user['id_cms_privileges'])->first();
				$isSuperAdmin = FALSE;
				if(count($user) > 0) {
					if(!$priv['is_superadmin']) {
						$isSuperAdmin = FALSE;
						$pjRoleAclModel = pjRoleAclModel::factory();
						$roles = $pjRoleAclModel->select("t2.name, t2.path, t2.controller, t1.is_visible, t1.is_create, t1.is_read, t1.is_edit, t1.is_delete")
													->join('pjModule', 't2.id = t1.id_tk_cbs_modules', 'left outer')
													->where('t1.id_tk_cbs_roles =', $user['role_id'])
													->findAll()
													->getData();
						//App::dd($roles);
						App::setSession('roles', $roles);
						App::setSession('role_id', $user['role_id']);
						App::setSession('role_name', $priv['role']);
						App::setSession('is_superadmin', $isSuperAdmin);
					} else {
						//App::dd($roles);
						App::setSession('roles', $roles);
						App::setSession('role_id', $user['role_id']);
						App::setSession('role_name', $priv['role']);
						//pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex&err=5");
						$isSuperAdmin = TRUE;
						App::setSession('is_superadmin', $isSuperAdmin);
						
					}
						
				}						
						
				
				
				
				
				if ($user['status'] != 'T')
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=3");
				}
				
				# Login succeed
				$last_login = date("Y-m-d H:i:s");
				if($user['last_login'] == $user['created'])
				{
					$user['last_login'] = date("Y-m-d H:i:s");
				}
    			App::setSession($this->defaultUser, $user);
    			// echo "<pre>";
				// print_r($_SESSION);
				// exit;	
    			$data = array();
    			$data['last_login'] = $last_login;
    			$pjUserModel->reset()->setAttributes(array('id' => $user['id']))->modify($data);
				
				if(App::isSuperAdmin()) {
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
    			// if ($this->isAdmin() || $this->isEditor())
    			// {
	    		// 	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
    			// }
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionLogout()
	{
		if ($this->isLoged())
        {
        	unset($_SESSION[$this->defaultUser]);
        	unset($_SESSION['roles']);
        	unset($_SESSION['role_id']);
        	unset($_SESSION['role_name']);
        	unset($_SESSION['is_superadmin']);
        }
       	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
	}
	
	public function pjActionProfile()
	{
		$this->checkLogin();
		
		
			if (isset($_POST['profile_update']))
			{
				$pjUserModel = pjUserModel::factory();
				$arr = $pjUserModel->find($this->getUserId())->getData();
				$data = array();
				$data['role_id'] = $arr['role_id'];
				$data['status'] = $arr['status'];
				$post = array_merge($_POST, $data);
				if (!$pjUserModel->validates($post))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA14");
				}
				$pjUserModel->set('id', $this->getUserId())->modify($post);
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA13");
			} else {
				$this->set('arr', pjUserModel::factory()->find($this->getUserId())->getData());
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdmin.js');
			}
		
	}
}
?>
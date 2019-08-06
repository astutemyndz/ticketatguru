<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminCustomers extends pjAdmin
{
	public  $pjCustomerModel = NULL;
	private $hash_method = 'bcrypt';
	private $store_salt  = FALSE;
	private $salt_length = 22;

	private $default_rounds = NULL;
	private $random_rounds = NULL;
	private $min_rounds = NULL;
	private $max_rounds = NULL;
	private $bcrypt = NULL;

	public function __construct()
	{
		
		parent::__construct();

		$this->default_rounds 	= 8;
        $this->random_rounds 	= FALSE;
        $this->min_rounds 		= 5;
		$this->max_rounds 		= 9;
		$this->salt_length 		= 22;
		$this->store_salt 		= FALSE;
		$this->hash_method 		= 'bcrypt';
		


		if ($this->hash_method == 'bcrypt')
        {
			//echo $this->random_rounds;
            if ($this->random_rounds)
            {
			
                $rand = rand($this->min_rounds,$this->max_rounds);
                $params = array('rounds' => $rand);
            }
            else
            {
                $params = array('rounds' => $this->default_rounds);
			}
			
			$params['salt_prefix'] = $this->getSaltPrefix();
			
			$this->bcrypt = new Bcrypt($params);
			
			
        }
		//$this->ion_auth = Ion_auth_model::factory();
		// self::allowCORS();
		
	}

	private function getSaltPrefix() {
		return version_compare(PHP_VERSION, '5.3.7', '<') ? '$2a$' : '$2y$';
	}
	
	public function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE)
    {
        if (empty($password))
        {
            return FALSE;
		}
		
        // bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
        {
            return $this->bcrypt->hash($password);
        } 
        if ($this->store_salt && $salt)
        {
            return sha1($password . $salt);
        }
        else
        {
			$salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
		
	}
	
	
	private static function salt()
    {
		
        $raw_salt_len = 16;
        $buffer = '';
        $buffer_valid = FALSE;
        if (function_exists('random_bytes'))
        {
			
			$buffer = random_bytes($raw_salt_len);
            if ($buffer)
            {
                $buffer_valid = TRUE;
			}
			
        }
        if (!$buffer_valid && function_exists('mcrypt_create_iv') && !defined('PHALANGER'))
        {
			$buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
            if ($buffer)
            {
                $buffer_valid = TRUE;
            }
        }
        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes'))
        {
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
            if ($buffer)
            {
                $buffer_valid = TRUE;
            }
        }
        if (!$buffer_valid && @is_readable('/dev/urandom'))
        {
			$f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $raw_salt_len)
            {
                $buffer .= fread($f, $raw_salt_len - $read);
                $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $raw_salt_len)
            {
                $buffer_valid = TRUE;
            }
        }
        if (!$buffer_valid || strlen($buffer) < $raw_salt_len)
        {
			$bl = strlen($buffer);
            for ($i = 0; $i < $raw_salt_len; $i++)
            {
                if ($i < $bl)
                {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                }
                else
                {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }
		$salt = $buffer;
	
        // encode string with the Base64 variant used by crypt
        $base64_digits = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $base64_string = base64_encode($salt);
		$salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);
	
		$salt = substr($salt, 0, 22);
        return $salt;
	}
	
	public function pjActionCheckEmail()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_GET['email']) || empty($_GET['email']))
			{
				echo 'false';
				exit;
			}
			$pjCustomerModel = pjCustomerModel::factory()->where('t1.email', $_GET['email']);
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjCustomerModel->where('t1.id !=', $_GET['id']);
			}
			echo $pjCustomerModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCloneUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				$MultiLangModel = new pjMultiLangModel();

				$data = pjCustomerModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
				foreach ($data as $item)
				{
					$item_id = $item['id'];
					unset($item['id']);
					unset($item['email']);

					$id = pjCustomerModel::factory($item)->insert()->getInsertId();
					if ($id !== false && (int) $id > 0)
					{
						$_data = pjMultiLangModel::factory()->getMultiLang($item_id, 'pjUser');
						$MultiLangModel->saveMultiLang($_data, $id, 'pjUser');
					}
				}
			}
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$pjCustomerModel = pjCustomerModel::factory();
		
			$email 			= (isset($_POST['email'])) ? $_POST['email'] : '';
			$password 		= (isset($_POST['password'])) ? $_POST['password'] : '';
			$first_name 	= (isset($_POST['first_name'])) ? $_POST['first_name'] : '';
			$last_name 		= (isset($_POST['last_name'])) ? $_POST['last_name'] : '';

			if (isset($_POST['user_create']))
			{
				$email = strtolower($email);
				$salt = $this->store_salt ? $this->salt() : FALSE;
				$password = $this->hash_password($password, $salt);
			
				$data = array();
				$data['first_name'] = $first_name;
				$data['password'] = $password;
				$data['last_name'] = $last_name;
				$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
				$data['username'] = $_POST['email'];
				$data['status'] = (isset($_POST['status']) && $_POST['status'] == 'T') ? 'T' : 'F';

				$id = $pjCustomerModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AU03';
				} else {
					$err = 'AU04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminCustomers&action=pjActionIndex&err=$err");
				
			
				
			} else {
				
				$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
		
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminCustomers.js');
			}
	
	}
	
	public function pjActionDeleteUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if ($_GET['id'] != $this->getUserId() && $_GET['id'] != 1)
			{
				if (pjCustomerModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
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
				pjCustomerModel::factory()
					->where('id !=', $this->getUserId())
					->where('id !=', 1)
					->whereIn('id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionExportUser()
	{
		
		
		if (isset($_POST['record']) && is_array($_POST['record']))
		{
			$arr = pjCustomerModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
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
			$pjCustomerModel = pjCustomerModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjCustomerModel->where('t1.email LIKE', "%$q%");
				$pjCustomerModel->orWhere('t1.first_name LIKE', "%$q%");
				$pjCustomerModel->orWhere('t1.last_name LIKE', "%$q%");
				$pjCustomerModel->orWhere('t1.username LIKE', "%$q%");
			}

			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjCustomerModel->where('t1.status', $_GET['status']);
			}
				
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjCustomerModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = array();
			
			$data = $pjCustomerModel->select('t1.id, t1.email,CONCAT_WS(" ", t1.`first_name`, t1.`last_name`) AS name, t1.created, t1.status')
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
	
	public function pjActionIndex()
	{
		
		$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendJs('pjAdminCustomers.js');
	}
	
	public function pjActionSetActive()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			$pjCustomerModel = pjCustomerModel::factory();
			
			$arr = $pjCustomerModel->find($_POST['id'])->getData();
			
			if (count($arr) > 0)
			{
				switch ($arr['is_active'])
				{
					case 'T':
						$sql_status = 'F';
						break;
					case 'F':
						$sql_status = 'T';
						break;
					default:
						return;
				}
				$pjCustomerModel->reset()->setAttributes(array('id' => $_POST['id']))->modify(array('is_active' => $sql_status));
			}
		}
		exit;
	}
	
	public function pjActionSaveUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjCustomerModel = pjCustomerModel::factory();
			
			$pass = true;
			if ((int) $_GET['id'] === 1)
			{
				if(in_array($_POST['column'], array('role_id', 'status', 'is_active')))
				{
					$pass = false;
				}else if(in_array($_POST['column'], array('name', 'email')) && $_POST['value'] == ''){
					$pass = false;
				}else if($_POST['column'] == 'email' && $_POST['value'] != '' && !filter_var($_POST['value'], FILTER_VALIDATE_EMAIL)){
					$pass = false;
				}
			}
			if ($pass)
			{
				$pjCustomerModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			}
		}
		exit;
	}
	
	public function pjActionStatusUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjCustomerModel::factory()->whereIn('id', $_POST['record'])->where('id <>', 1)->modifyAll(array(
					'status' => ":IF(`status`='F','T','F')"
				));
			}
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
	
				
			if (isset($_POST['user_update']))
			{
				pjCustomerModel::factory()->where('id', $_POST['id'])->limit(1)->modifyAll($_POST);
				
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjAdminCustomers&action=pjActionIndex&err=AU01");
				
			} else {
				$arr = pjCustomerModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "admin.php?controller=pjAdminCustomers&action=pjActionIndex&err=AU08");
				}
				$this->set('arr', $arr);
				
				$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminCustomers.js');
			}
		
	}
}
?>
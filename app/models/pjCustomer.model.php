<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCustomerModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'customers';
	

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'first_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'last_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'username', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'password', 'type' => 'varchar', 'default' => ':NULL'),
		//array('name' => 'created_on', 'type' => 'int', 'default' => ':time()'),
		array('name' => 'created', 'type' => 'timestamp', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T'),
		array('name' => 'active', 'type' => 'int', 'default' => 1)
	);
	// protected  $salt = $this->salt();
	// protected $password = $this->hash_password($password, $salt);
	
	
	
	protected $validate = array(
		'rules' => array(
			/*'role_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			),*/
			'email' => array(
				'pjActionEmail' => true,
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'password' => array(
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'name' => array(
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'status' => 'pjActionRequired'
		)
	);

	public static function factory($attr=array())
	{
		return new pjCustomerModel($attr);
	}

	public function test() {
		echo "test calling...";
	}


}
?>
<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjModuleModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'modules';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'icon', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'path', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'table_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'controller', 'type' => 'varchar', 'default' => ':NULL'),
		//array('name' => 'action', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_protected', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'is_active', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'created_at', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'updated_at', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'deleted_at', 'type' => 'datetime', 'default' => ':NOW()'),
	);
	/*
	protected $validate = array(
		'rules' => array(
			'role_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			),
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
	*/
	public static function factory($attr=array())
	{
		return new pjModuleModel($attr);
	}
}
?>
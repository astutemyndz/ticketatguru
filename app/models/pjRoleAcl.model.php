<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjRoleAclModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'role_acls';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'id_tk_cbs_roles', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'id_tk_cbs_modules', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'is_visible', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'is_create', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'is_read', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'is_edit', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'is_delete', 'type' => 'tinyint', 'default' => ':NULL'),
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
		return new pjRoleAclModel($attr);
	}
}
?>
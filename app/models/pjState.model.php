<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjStateModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'states';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'state_unique_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'country_id', 'type' => 'varchar', 'default' => ':NULL'),
	);
	
	
	public static function factory($attr=array())
	{
		return new pjStateModel($attr);
	}
}
?>
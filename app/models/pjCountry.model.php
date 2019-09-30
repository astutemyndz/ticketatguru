<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCountryModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'countries';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'sortname', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
	);
	
	
	public static function factory($attr=array())
	{
		return new pjCountryModel($attr);
	}
}
?>
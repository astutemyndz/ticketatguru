<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjSponsorsModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'sponsors';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'sponsor_image', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'sponsor_link', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'sponsor_year', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public $i18n = array('title');
	
	public static function factory($attr=array())
	{
		return new pjSponsorsModel($attr);
	}
}
?>
<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjGroupSubscriberModel extends pjAppModel
{
	
	protected $table = 'groups_subscribers';
	
	protected $schema = array(
		array('name' => 'group_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'subscriber_id', 'type' => 'int', 'default' => ':NULL')
	);
	
	public $i18n = array('name');
	
	public static function factory($attr=array())
	{
		return new pjGroupSubscriberModel($attr);
	}
}
?>
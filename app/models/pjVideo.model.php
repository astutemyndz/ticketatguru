<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjVideoModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'video';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'video_path', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'file_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'mime_type', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public $i18n = array('title','description');
	
	public static function factory($attr=array())
	{
		return new pjVideoModel($attr);
	}
}
?>
<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCmsModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'cms';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'page_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public $i18n = array('cms_title','cms_description','cms_meta_key','cms_meta_title','cms_meta_desc');
	
	public static function factory($attr=array())
	{
		return new pjCmsModel($attr);
	}
}
?>
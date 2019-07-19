<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjImageGalleryModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'image_galleries';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'gallery_image', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public $i18n = array('title','description');
	
	public static function factory($attr=array())
	{
		return new pjImageGalleryModel($attr);
	}
}
?>
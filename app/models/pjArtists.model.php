<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjArtistsModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'artists';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'artist_image', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
	
	public $i18n = array('title','description');
	
	public static function factory($attr=array())
	{
		return new pjArtistsModel($attr);
	}
}
?>
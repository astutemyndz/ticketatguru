<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCustomerRequestModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'customer_requests';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'tk_cbs_customers_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'tk_cbs_artists_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'content', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created_at', 'type' => 'timestamp', 'default' => ':current_timestamp()'),
		array('name' => 'updated_at', 'type' => 'timestamp', 'default' => ':NULL')
	);
	
	public $i18n = array('cms_title','cms_description','cms_meta_key','cms_meta_title','cms_meta_desc');
	
	public static function factory($attr=array())
	{
		return new pjCustomerRequestModel($attr);
	}
}
?>
<?php
//use ;

// ini_set("display_errors", "On");
// error_reporting(E_ALL|E_STRICT);

if (!headers_sent())
{
	if (isset($_GET['session_id']))
	{
		$session_id = trim($_GET['session_id']);
		$pattern = '/[a-zA-Z0-9]+/';
		if (version_compare(PHP_VERSION, '5.0.0', '>='))
		{
			$pattern = '/[a-zA-Z0-9,-]+/';
		}
		if (preg_match($pattern, $session_id))
		{
			session_id($session_id);
		}
	}
	session_name('TicketAtGuru');
	@session_start();
}
//echo "<pre>"; print_r($_SESSION);

$_SERVER['PHP_SELF']=htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES);
if (isset($_GET["reporting"]) && $_GET["reporting"] == '0') 
{
	$_SESSION["error_reporting"] = '0';
} else if (isset($_GET["reporting"]) && $_GET["reporting"]== '1') {
	$_SESSION["error_reporting"] = '1';
}
if (isset($_SESSION["error_reporting"]) && $_SESSION["error_reporting"]=='1')
{
	ini_set("display_errors", "On");
	error_reporting(E_ALL|E_STRICT);
} else {
	error_reporting(0);
}

header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}

require ROOT_PATH . 'app/config/options.inc.php';

require_once PJ_FRAMEWORK_PATH . 'pjAutoloader.class.php';
pjAutoloader::register();

if (!isset($_GET['controller']) || empty($_GET['controller']))
{
	header("HTTP/1.1 301 Moved Permanently");
	pjUtil::redirect(PJ_INSTALL_URL . basename($_SERVER['PHP_SELF'])."?controller=pjAdmin&action=pjActionIndex");
}

if (isset($_GET['controller']))
{
	$controller = (isset($_GET['controller'])) ? $_GET['controller'] : '';
	
	$pjMM = pjModuleModel::factory();
	$pjMM = $pjMM->findAll()->getData();

	$data['controller'] = $_GET['controller'];

	$explodeController = explode('pj', $controller);
	$c = (count($explodeController) > 0) ? count($explodeController) : 0;

	switch ($c) {
		case '2':
			$data['name'] = $explodeController[1];
			break;
		default:
		$data['name'] = $explodeController[1]." ". $data['name'] = $explodeController[2];
			break;
	}
	
	
	$data['path'] = $_SERVER['QUERY_STRING'];

	$isExists = false;
	for($i = 0; $i <= count($pjMM); $i++) {
		if($pjMM[$i]['controller'] == $controller) {
			$isExists = true;
			break;
		} else {
			$isExists = false;
			//$data['table_name'] = '';
			if($pjMM[$i]['controller'] == 'pjAdmin') {
				$isExists = true;
			}
			
			
		}
	}

	if(!$isExists) {
		$id = pjModuleModel::factory($data)->insert()->getInsertId();
	}
	$pjObserver = pjObserver::factory();
	$pjObserver->init();
	

	
	// echo "<pre>";
	// print_r($pjMM);
	//exit;			

	

	

	
}
?>
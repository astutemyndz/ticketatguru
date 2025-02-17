<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAutoloader
{
	public static function autoload($className)
	{

		$model = str_replace('Model', '', $className);
		
		$paths = array(
			PJ_FRAMEWORK_PATH . $className . '.class.php',
			PJ_CONTROLLERS_PATH . $className . '.controller.php',
			PJ_MODELS_PATH . $model . '.model.php',
			PJ_COMPONENTS_PATH. $className . '.component.php',
			PJ_FRAMEWORK_PATH . 'components/'. $className . '.component.php',
			PJ_APP_PATH . 'libraries/'. $className . '.php',
			PJ_APPLICATION_PATH . 'libraries/'. $className . '.php',
			PJ_APPLICATION_PATH . 'third_party/PayPal/'. $className . '.php',
			
		);
		///echo PJ_CONTROLLERS_PATH . $className . '.controller.php'."<br>";
		// echo "<pre>";
		// print_r($paths);
		//exit();

		foreach ($GLOBALS['CONFIG']['plugins'] as $plugin)
		{
			$paths[] = PJ_PLUGINS_PATH . $plugin . '/controllers/' . $className . '.controller.php';
			$paths[] = PJ_PLUGINS_PATH . $plugin . '/controllers/components/' . $className . '.component.php';
			$paths[] = PJ_PLUGINS_PATH . $plugin . '/models/' . $model . '.model.php';
		}
	
		foreach ($paths as $filename)
		{
			if (file_exists($filename))
			{
				//echo $filename."<br>";
				require $filename;
				return;
			}
		}
	}
	
	public static function register()
	{
		spl_autoload_register('pjAutoloader::autoload');
	}

	
}
?>
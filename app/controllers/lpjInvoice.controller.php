<?php 
use App\Plugins\PjInvoice\Controllers\pjInvoice as AppPluginPjInvoiceController;
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class lpjInvoice extends AppPluginPjInvoiceController
{
    public function __construct()
    {
        parent::__construct();
    }
	public function pjActionGenerateInvoice()
	{
		
		$pjInvoiceModel = pjInvoiceModel::factory();
		$pjInvoiceItemModel = pjInvoiceItemModel::factory();
			
		if (isset($_POST['invoice_create']))
		{
			$data = array();
			$data['foreign_id'] = $this->getForeignId();
			$data['issue_date'] = !empty($_POST['issue_date']) ? pjUtil::formatDate($_POST['issue_date'], $this->option_arr['o_date_format']) : NULL;
			$data['due_date'] = !empty($_POST['due_date']) ? pjUtil::formatDate($_POST['due_date'], $this->option_arr['o_date_format']) : NULL;
			$data['s_date'] = !empty($_POST['s_date']) ? pjUtil::formatDate($_POST['s_date'], $this->option_arr['o_date_format']) : NULL;
			$data = array_merge($_POST, $data);
			if (!$pjInvoiceModel->validates($data))
			{
				pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjInvoice&action=pjActionInvoices&err=PIN06");
			}
			$invoice_id = $pjInvoiceModel->setAttributes($data)->insert()->getInsertId();
			//App::dd($invoice_id);
			if ($invoice_id !== false && (int) $invoice_id > 0)
			{
				$pjInvoiceItemModel
					->where('tmp', $_POST['tmp'])
					->modifyAll(array(
						'invoice_id' => $invoice_id,
						'tmp' => ":NULL"
					));
				$err = "PIN07";
			} else {
				$err = "PIN08";
			}
			pjUtil::redirect(PJ_INSTALL_URL . "admin.php?controller=pjInvoice&action=pjActionInvoices&err=$err");
		} 
    }
    public function downloadInvoice() { 
    	// if ($request->has('file')) {
       	// 	$fileName = $request->input('file');
		// }

        $filePath = asset($fileName);
        $filename = basename($filePath);
    

         if(!empty($fileName)) {
            // Define headers
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
            
            // Read the file
            readfile($filePath);
            exit;
        } else{
            echo 'The file does not exist.';
        }
    }
    public function pjActionInvoices()
	{
		$this
			->set('invoice_config_arr', pjInvoiceConfigModel::factory()->getConfigData($this->getLocaleId()))
			->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/')
			->appendJs('pjInvoice.js', $this->getConst('PLUGIN_JS_PATH'))
			->appendCss('plugin_invoice.css', $this->getConst('PLUGIN_CSS_PATH'))
			->appendJs('admin.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true)
		;
	}
	
}
?>
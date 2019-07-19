<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminQueues extends pjAdmin
{
	public function pjActionDeleteQueue()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$response = array();
			if ($this->isAdmin())
			{
				if (pjQueueModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
				{
					$response['code'] = 200;
				} else {
					$response['code'] = 100;
				}
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteQueueBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if ($this->isAdmin())
			{
				if (isset($_POST['record']) && count($_POST['record']) > 0)
				{
					pjQueueModel::factory()->reset()->whereIn('id', $_POST['record'])->eraseAll();
				}
			}
		}
		exit;
	}
	
	public function pjActionSaveQueue()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if($_POST['column'] == 'status')
			{
				if($_POST['value'] != '')
				{
					pjQueueModel::factory()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
				}
			}else{
				pjQueueModel::factory()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			}
		}
		exit;
	}
	
	public function pjActionGetQueue()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjQueueModel = pjQueueModel::factory();
			
			$pjQueueModel->join('pjMessage', 't1.message_id = t2.id', 'left');
			$pjQueueModel->join('pjSubscriber', 't1.subscriber_id = t3.id', 'left');
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjQueueModel->where("(t2.subject LIKE '%$q%' OR t3.email LIKE '%$q%')");
			}
			if (isset($_GET['subscriber_id']) && !empty($_GET['subscriber_id']))
			{
				$pjQueueModel->where('t1.subscriber_id', $_GET['subscriber_id']);
				$pjQueueModel->where('t1.status', 'completed');
			}
			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('inprogress', 'completed')))
			{
				$pjQueueModel->where('t1.status', $_GET['status']);
			}
			
			$column = 'date_sent';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjQueueModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			
			$data = array();
			$arr = $pjQueueModel
				->select("t1.*, t2.subject, t3.email")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
				
			foreach($arr as $k => $v)
			{
				$v['date_sent'] = pjUtil::formatDate(date('Y-m-d', strtotime($v['date_sent'])), 'Y-m-d', $this->option_arr['o_date_format']) . ', ' . pjUtil::formatTime(date('H:i:s', strtotime($v['date_sent'])), 'H:i:s', $this->option_arr['o_time_format']);	
				$data[$k] = $v;
			}
			
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminQueues.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages&page=queue', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
}
?>
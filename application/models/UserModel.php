<?php
/**
 * Name:    User Model
 * Author:  Rakesh Maity
 *           rakesh@astutemyndz.com
 * Requirements: PHP5 or above
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends App_Model {
    private $pjUserModel;
    private $userId;
    private $table = 'tk_cbs_customers';
    public function __construct()
    {
        parent::__construct();
       
        $this->pjUserModel = pjUserModel::factory();
        
    }

    public function setUserId($value) {
        $this->userId = $value;
        return $this;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function getUser() {
        if($this->userId) {
            return $this->common->get($this->table, $this->userId);
        }
        return false;   
    }
}

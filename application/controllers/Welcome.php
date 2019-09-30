<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends App_Controller {
    public function __construct()
    {
        parent::__construct();
        echo "<pre>";
        print_r($_SESSION);
    }

    public function index() {
        echo $this->getForeignId();
        exit;
    }
}
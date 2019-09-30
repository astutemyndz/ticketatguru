<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CartLibrary extends CI_Cart {

    //var $product_name_rules = '[:print:]';
    var $product_name_safe  = FALSE;

    public function test() {
        return 'test';
    }

}
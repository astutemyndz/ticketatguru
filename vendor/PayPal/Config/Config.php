<?php

namespace PayPal\Config;

/**
 * Class ArrayUtil
 * Util Class for Arrays
 *
 * @package PayPal\Common
 */
class Config
{
    /**
     *
     * @param array $arr
     * @return true if $arr is an associative array
     */
    private $config = array();

    public function setConfig($config = array()) {
        $this->config = ($config) ? $config : $this->defaultConfig();
        return $this;
    }
    public static function getConfig()
    {
      return $this->config;
    }

    public static function getSandBoxConfig() {
        return array(
            'client_id' => 'AZZI7nBmRypZF4H7ajNM4G8JnbxGTor8OpxdwXmKUWh2R-zfKfYAX9Us1idmQ_6-zltNgcIeZCZWVakG',
            'secret' => 'EJ4gqjUBn0B8xf1SxlK5z4aFsxjOTaZ20AFn6BpVrX52BP1WYd-S7z0oqnYjFBHnEBYpNsKAdE4TWD_D',
            'url' => 'https://api.sandbox.paypal.com',
            'settings' => array(
                'mode' => 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' =>  APPPATH. '/logs/paypal.log',
                'log.LogLevel' => 'ERROR'
            )
        );
    }
}

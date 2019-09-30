<?php 
namespace Package\Component; 

class Prop {
    
    private $prop;

    public function __construct()
    {
        
    }

    public function setProp($prop) {
        $this->prop = $prop;
        return $this->prop;
    }

    public function getProp() {
        return $this->prop;
    }
}
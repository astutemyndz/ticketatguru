<?php 
namespace Package\Component;

use App;
class Dropdown {

    private $attribute;
    public function setAttribute($value) {
        $this->attribute = $value;
        return $this;
    } 
    public function option($props) {
       // App::dd($props['attributes']);
        if(!empty($props['attributes'])) {
            if(is_array($props['attributes']) && count($props['attributes'])) {
                foreach($props['attributes'] as $key => $value) {
                    $this->attribute .= $key."='".$value."' " ;
                }
            }
        }
        //App::dd($this->attribute);
        return "<option value='".$props['value']."'" .$this->attribute.">".$props['text']."</option>";
    }

}
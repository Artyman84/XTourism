<?php

class TArrayObject extends \ArrayObject{

    protected $tab;
    protected $prefix;

    public function __construct($array = array(), $prefix = "", $tab = "\t"){
        parent::__construct((array)$array);
        $this->tab = $tab;
        $this->prefix = $prefix;
    }

    public static function dump($array){
        echo '<pre>', (new static($array)), '</pre>';
    }

    public function __toString(){
        return $this->_recursive($this->getArrayCopy(), $this->prefix);
    }

    private function _recursive($array, $prefix){
        $string = array(
            'simple' => array(),
            'mixed' => array()
        );

        foreach($array as $key => $value){

            if($value instanceof noEscape || (!is_array($value) && !is_object($value))){
                $value = $value instanceof noEscape? $value->__toString(): json_encode($value);
                $string['mixed'][] = json_encode($key) . ' => ' . $value;
                $string['simple'][] = $value;
            }else{
                $string['mixed'][] = json_encode($key) . ' => ' . $this->_recursive($value, $prefix . $this->tab);
            }

        }

        if((count($string['mixed']) === count($string['simple'])) && (array_keys((array)$array) === array_keys(array_keys((array)$array)))){
            return '[' . implode(',', $string['simple']) . ']';
        }else{
            return '[' . "\n" . $prefix . $this->tab . implode(",\n" . $prefix . $this->tab, $string['mixed']) . "\n" . $prefix . ']';
        }

    }

}


class noEscape {

    protected $value;

    public function __construct($value){
        $this->value = $value;
    }

    public function __toString(){
        return (string)$this->value;
    }
}
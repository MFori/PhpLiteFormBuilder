<?php

abstract class Element
{
    protected $name;
    protected $id;
    protected $value;
    protected $required = false;
    protected $classes = array();
    protected $attributes = array();
    protected $pre_fill = false;
    protected $method = '';
    protected $divId;
    protected $divClasses;
    protected $label;

    public function __construct($divId = null, $divClasses = array()){
        $this->divId = $divId;
        $this->divClasses = $divClasses;
    }

    public abstract function render();

    public abstract function isValid();

    public function setName($name)
    {
        $this->name = htmlspecialchars($name, ENT_QUOTES);
        if($this->pre_fill){
            if(isset($_POST[$name])) $this->setValue($_POST[$name]);
            if(isset($_GET[$name])) $this->setValue($_GET[$name]);
        }
        return $this;
    }

    public function setRequired($required = true){
        if(is_bool($required)) $this->required = $required;
        return $this;
    }

    public function setPreFill($pre_fill){
        if(is_bool($pre_fill)) $this->pre_fill = $pre_fill;
        return $this;
    }

    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    public function getValue(){
        return $this->value;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setClasses($classes = array())
    {
        $this->classes = $this->secure($classes);
        return $this;
    }

    public function setClass($class)
    {
        if(is_string($class)){
            $this->classes[] = htmlspecialchars($class, ENT_QUOTES);
        }
        return $this;
    }

    public function setAttribute($attribute){
        $this->attributes[] = $attribute;
        return $this;
    }

    public function setLabel($label){
        if($label instanceof Label){
            $this->label = $label;
            $this->label->setInputId($this->id);
        }
        elseif(is_string($label)){
            $this->label = new Label($label);
            $this->label->setInputId($this->id);
        }
        return $label;
    }

    public function setMethod($method){
        $this->method = $method;
    }

    public function setId($id)
    {
        $this->id = htmlspecialchars($id, ENT_QUOTES);
        if(isset($this->label)){
            $this->label->setInputId($id);
        }
        return $this;
    }

    public function disable(){
        $this->attributes[] = 'disabled';
        return $this;
    }

    public function onlyRead(){
        $this->attributes[] = 'readonly';
        return $this;
    }

    protected function renderOpenDiv(){
        $html = '<div';
        if(isset($this->divId)) $html .= ' id="' . $this->divId . '"';
        if(isset($this->divClasses) && sizeof($this->divClasses)>0){
            $html .= ' class="';
            foreach($this->divClasses as $class){
                $html .= $class . ' ';
            }
            $html = substr($html, 0, -1);
            $html .= '"';
        }
        $html .= '>';
        return $html;
    }

    private function secure($array = array())
    {
        if (is_string($array)) return htmlspecialchars($array, ENT_QUOTES);
        else if (is_array($array)) {
            foreach ($array as $key => $val) {
                $array[$key] = $this->secure($val);
            }
            return $array;
        } else return $array;
    }
}
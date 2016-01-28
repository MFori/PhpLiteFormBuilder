<?php

class Label extends Element {
    private $inputId;
    private $text;

    public function __construct($text){
        if(is_string($text)) $this->text = $text;
        else $this->text = '';
        parent::__construct();
    }

    function isValid(){
        return true;
    }

    function render(){
        $html = '<label for="' . $this->inputId .'"';
        if(isset($this->id)) $html .= ' id="' . $this->id .'"';
        if(sizeof($this->classes)>0){
            $html .= 'class="';
            foreach($this->classes as $class){
                $html .= $class . ' ';
            }
            $html = substr($html, 0, -1);
        }
        $html .= '">' . $this->text . '</label>';
        return $html;
    }

    public function setInputId($id){
        $this->inputId = $id;
    }

    public function getInputId(){
        return $this->inputId;
    }
}
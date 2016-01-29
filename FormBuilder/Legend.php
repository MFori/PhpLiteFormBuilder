<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class Legend extends Element {
    private $text='';

    public function __construct($text = ''){
        parent::__construct();
        $this->text = $text;
    }

    public function setText($text){
        $this->text = $text;
        return $this;
    }

    function isValid(){
        return true;
    }

    function render(){
        $html = '<legend';
        if(isset($this->id)) $html .= ' id="' . $this->id . '"';
        if(sizeof($this->classes)>0){
            $html .= ' class="';
            foreach($this->classes as $class){
                $html .= $class . ' ';
            }
            $html = substr($html, 0, -1);
            $html .= '"';
        }
        foreach($this->attributes as $attr){
            $html .= ' ' . $attr;
        }
        $html .= '>' . $this->text . '</legend>';

        return $html;
    }
}
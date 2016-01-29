<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class InputFile extends Input {
    private $accept;

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
    }

    public function setAccept($accept){
        $this->accept = $accept;
        return $this;
    }

    function isValid(){
        return true;
    }

    function render(){
        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<input type="file"';
        if(isset($this->id)) $html .= ' id="' . $this->id .'"';
        if(isset($this->name)) $html .= ' name="' . $this->name . '"';
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
        if(isset($this->accept)) $html .= ' accept="' . $this->accept . '"';
        if($this->required) $html .= ' required';
        $html .= '></div>';
        return $html;
    }
}
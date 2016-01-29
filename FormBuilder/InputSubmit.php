<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class InputSubmit extends Input {

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
    }

    function isValid(){
        return true;
    }

    function render(){
        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<input type="submit"';
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
        if(isset($this->value) && $this->pre_fill) $html .= ' value="' . $this->value . '"';
        foreach($this->attributes as $attr){
            $html .= ' ' . $attr;
        }
        $html .= '></div>';
        return $html;
    }
}
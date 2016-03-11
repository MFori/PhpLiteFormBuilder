<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class InputCheckBox extends Input{

    private $checked;

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
    }

    public function setChecked($checked = true){
        $this->checked = $checked;
        return $this;
    }

    function isValid(){
        $value = $this->getValue();
        if(isset($this->required) && $this->required){
            if($value == '') return false;
            if($value == null) return false;
            if(strtoupper($value) == 'OFF') return false;
        }
        return true;
    }

    function render(){
        if($this->checked) $this->setAttribute('checked');

        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<input type="checkbox"';
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
        if($this->required) $html .= ' required';
        $html .= '></div>';
        return $html;
    }
}
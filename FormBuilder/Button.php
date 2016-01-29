<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class Button extends Element {
    private $type;
    private $text;

    public function setType($type){
        $this->type = $type;
        return $this;
    }

    public function setText($text){
        $this->text = $text;
        return $this;
    }

    function render(){
        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<button';
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
        if(isset($this->value)) $html .= ' value="' . $this->value . '"';
        foreach($this->attributes as $attr){
            $html .= ' ' . $attr;
        }
        if(isset($this->type)) $html .= ' type="' . $this->type . '"';
        $html .= '>';
        if(isset($this->text)) $html .= $this->text;
        $html .= '</button></div>';
        return $html;
    }

    function isValid(){
        return true;
    }
}
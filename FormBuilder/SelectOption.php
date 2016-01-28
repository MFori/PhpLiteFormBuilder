<?php

class SelectOption extends Element{
    private $selected;
    private $text;
    private $select;

    public function setSelected($selected = true){
        $this->selected = $selected;
        return $this;
    }

    public function setText($text){
        $this->text = $text;
        return $this;
    }

    function isValid(){
        return true;
    }

    function render(){
        $html = '<option';
        if(isset($this->id)) $html .= ' id="' . $this->id . '"';
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
        if(isset($this->value)) $html .= ' value="' . $this->value . '"';
        if($this->selected) $html .= ' selected';
        $html .= '>';
        if(isset($this->text)) $html .= $this->text;
        $html .= '</option></div>';
        return $html;
    }
}
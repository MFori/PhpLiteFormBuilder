<?php

class FieldSet extends Element {
    private $disabled = false;
    private $legend;

    public function setLegend($legend){
        if($legend instanceof Legend){
            $this->legend = $legend;
            return $legend;
        }
        elseif(is_string($legend)){
            $legend = new Legend($legend);
            $this->legend = $legend;
            return $legend;
        }
        else throw new FormException('legend must be instance of Legend or String');
    }

    public function setDisabled($disabled = true){
        $this->disabled = $disabled;
        return $this;
    }

    function isValid(){
        return true;
    }

    function render(){
        $html = '<fieldset';
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
        if($this->disabled) $html .= ' disabled';
        $html .= '>';
        if(isset($this->legend)) $html .= $this->legend->render();

        return $html;
    }
}
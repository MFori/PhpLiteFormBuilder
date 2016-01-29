<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class Select extends Element {

    private $size;
    private $multiple;
    private $options = array();

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
    }

    public function addOption($option){
        if($option instanceof SelectOption){
            $this->options[] = $option;
        }
        return $option;
    }

    public function getOptions(){
        return $this->options;
    }

    public function setHeader($text){
        $optionsHeader = new SelectOption();
        $optionsHeader->setText($text);
        $optionsHeader->setSelected();
        $this->addOption($optionsHeader);
        return $this;
    }

    public function getOptionByValue($value){
        foreach($this->options as $option){
            if($option->getValue() == $value) return $option;
        }
        return null;
    }

    function isValid(){
        $value = $this->getValue();
        if(isset($this->required)){
            if(!isset($value)) return false;
            elseif($value==null) return false;
            elseif($value=='') return false;
        }
        return true;
    }

    function render(){
        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<select';
        if(isset($this->size)) $html .= ' size="' . $this->size . '"';
        if(isset($this->multiple) && $this->multiple) $html .= ' multiple';
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
        if(isset($this->required)) $html .= ' required';
        foreach($this->attributes as $attr){
            $html .= ' ' . $attr;
        }
        $html .= '>';
        foreach($this->options as $option){
            $html .= $option->render();
        }
        $html .= '</select></div>';
        return $html;
    }
}
<?php

class TextArea extends Element{

    private $cols;
    private $rows;
    private $maxlength;
    private $minlength;
    private $text;

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
    }

    public function setCols($cols){
        $this->cols = $cols;
        return $this;
    }

    public function setRows($rows){
        $this->rows = $rows;
        return $this;
    }

    public function setMaxLength($maxLength){
        $this->maxlength = $maxLength;
        return $this;
    }

    public function setMinLength($minLength){
        $this->minlength = $minLength;
        return $this;
    }

    function isValid(){
        $value = $this->text;
        if($this->required){
            if(!isset($value)) return false;
            elseif($value==null) return false;
            elseif($value=='') return false;
        }
        if(isset($this->maxlength)) if(strlen($value)>$this->maxlength) return false;
        if(isset($this->minlength)) if(strlen($value)<$this->minlength) return false;
        return true;
    }

    function setText($text){
        $this->text = $text;
        return $this;
    }

    function render(){
        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<textarea';
        if(isset($this->rows)) $html .= ' rows="' . $this->rows . '"';
        if(isset($this->cols)) $html .= ' cols="' . $this->cols . '"';
        if(isset($this->maxlength)) $html .= ' maxlength="' . $this->maxlength . '"';
        if(isset($this->minlength)) $html .= ' minlength="' . $this->minlength . '"';
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
        if(isset($this->placeholder)) $html .= ' placeholder="' . $this->placeholder . '"';
        foreach($this->attributes as $attr){
            $html .= ' ' . $attr;
        }
        if($this->required) $html .= ' required';
        $html .= '>';
        if(isset($this->text) && $this->pre_fill) $html .= $this->text;
        $html .= '</textarea></div>';
        return $html;
    }
}
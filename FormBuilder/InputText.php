<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class InputText extends Input{

    private $maxLength;
    private $minLength;
    private $exactLength;
    private $placeholder;

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
    }

    public function setMaxLength($maxLength){
        $this->maxLength = $maxLength;
        $this->setPattern('.{0,'.$maxLength.'}');
        $this->setTitle('Max length: ' . $maxLength);
        return $this;
    }

    public function setMinLength($minLength){
        $this->minLength = $minLength;
        $this->setPattern('.{'.$minLength.',}');
        $this->setTitle('Min length: ' . $minLength);
        return $this;
    }

    public function setExactLength($length){
        $this->exactLength = $length;
        $this->setPattern('.{'.$length.'}');
        $this->setTitle('Length: '.$length);
        return $this;
    }

    public function getMinLength(){
        return $this->minLength;
    }

    public function getMaxLength(){
        return $this->maxLength;
    }

    public function getExactLength(){
        return $this->exactLength;
    }

    public function setPlaceholder($placeholder){
        $this->placeholder = $placeholder;
        return $this;
    }

    public function getPlaceholder(){
        return $this->placeholder;
    }

    function isValid(){
        $value = $this->getValue();
        if($this->required){
            if($value == '') return false;
            elseif($value == null) return false;
        }
        if(isset($this->pattern)){
            $pattern = '/'.$this->pattern.'/';
            preg_match($pattern, $value, $match);
            if(!isset($match[0])) return false;
        }
        return true;
    }

    function render(){
        $html = $this->renderOpenDiv();
        if(isset($this->label)){
            $html .= $this->label->render();
        }
        $html .= '<input type="text"';
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
        if(isset($this->value) && $this->pre_fill) $html .= ' value="' . $this->value . '"';
        foreach($this->attributes as $attr){
            $html .= ' ' . $attr;
        }
        if(isset($this->pattern)) $html .= ' pattern="' . $this->pattern . '"';
        if(isset($this->title)) $html .= ' title="' . $this->title . '"';
        if($this->required) $html .= ' required';
        $html .= '></div>';
        return $html;
    }

}
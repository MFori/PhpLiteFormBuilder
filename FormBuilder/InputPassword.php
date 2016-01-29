<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class InputPassword extends Input {

    private $maxLength;
    private $minLength;
    private $exactLength;

    public function __construct($divId = null, $divClasses = array()){
        parent::__construct($divId, $divClasses);
        $this->required = true;
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

    function isValid(){
        $value = $this->getValue();
        if($this->required){
            if(!isset($value)) return false;
            elseif($value == null) return false;
            elseif($value == '') return false;
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
        $html .= '<input type="password"';
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
        if(isset($this->pattern)) $html .= ' pattern="' . $this->pattern . '"';
        if(isset($this->title)) $html .= ' title="' . $this->title . '"';
        if($this->required) $html .= ' required';
        $html .= '></div>';
        return $html;
    }
}
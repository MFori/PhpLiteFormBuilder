<?php

include_once 'Element.php';

abstract class Input extends Element{
    protected $pattern;

    public function setPattern($pattern){
        $this->pattern = $pattern;
        $this->required = true;
        return $this;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

}
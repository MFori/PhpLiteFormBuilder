<?php

include_once 'Element.php';

/**
 * Lite form builder library
 * @author Martin Forejt
 */
abstract class Input extends Element{
    protected $pattern;
    protected $title;

    /**
     * Setting pattern to an input
     * @param string $pattern
     * @return Input
     */
    public function setPattern($pattern){
        $this->pattern = $pattern;
        $this->required = true;
        return $this;
    }

    /**
     * Setting title to an input
     * @param string $title
     * @return Input
     */
    public function setTitle($title){
        $this->title = htmlspecialchars($title, ENT_QUOTES);
        return $this;
    }

}
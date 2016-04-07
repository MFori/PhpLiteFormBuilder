<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class InputFile extends Input
{
    private $accept;
    private $maxSize;
    private $file;

    public function __construct($divId = null, $divClasses = array())
    {
        parent::__construct($divId, $divClasses);
    }

    public function setAccept($accept)
    {
        $this->accept = $accept;
        return $this;
    }

    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    function isValid()
    {
        if($this->isSend()){
            if ($this->required) {
                if (!isset($this->file)) return false;
                if ($this->file == '') return false;
                if (!sizeof($this->file) > 0) return false;
            }
            if (isset($this->maxSize)) {
                if ($this->file['size'] > $this->maxSize) return false;
            }
            if (isset($this->accept) && isset($this->file)) {
                $pattern = '#'.$this->accept.'#';
                preg_match($pattern, $this->file['type'], $match);
                if(!isset($match[0])) return false;
            }
            return true;
        }
        return !$this->required;
    }

    private function isSend()
    {
        foreach($this->file as $key => $item) if(trim($item)==''){
            return false;
        }
        return true;
    }

    function render()
    {
        $html = $this->renderOpenDiv();
        if (isset($this->label)) {
            $html .= $this->label->render();
        }
        $html .= '<input type="file"';
        if (isset($this->id)) $html .= ' id="' . $this->id . '"';
        if (isset($this->name)) $html .= ' name="' . $this->name . '"';
        if (isset($this->type)) $html .= ' type="' . $this->type . '"';
        if (sizeof($this->classes) > 0) {
            $html .= ' class="';
            foreach ($this->classes as $class) {
                $html .= $class . ' ';
            }
            $html = substr($html, 0, -1);
            $html .= '"';
        }
        foreach ($this->attributes as $attr) {
            $html .= ' ' . $attr;
        }
        if (isset($this->accept)) $html .= ' accept="' . $this->accept . '"';
        if ($this->required) $html .= ' required';
        $html .= '></div>';
        return $html;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}
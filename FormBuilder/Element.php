<?php

/**
 * Lite form builder library
 * @author Martin Forejt
 */
abstract class Element
{
    protected $name;
    protected $id;
    protected $value;
    protected $required = false;
    protected $classes = array();
    protected $attributes = array();
    protected $pre_fill = false;
    protected $divId;
    protected $divClasses = array();
    protected $label;

    /**
     * @param string | null $divId, id attribute of div
     * @param string | array $divClass, class attribute of div
     */
    public function __construct($divId = null, $divClass = array()){
        $this->divId = $this->secure($divId);
        if(is_string($divClass))
            $this->divClasses[] = $this->secure($divClass);
        elseif(is_array($divClass))
            $this->divClasses = $this->secure($divClass);
    }

    /**
     * Rendering element
     * @return string
     */
    public abstract function render();

    /**
     * Validate element
     * @return bool
     */
    public abstract function isValid();

    /**
     * Setting elements name
     * @param string $name, name attribute
     * @return Element
     */
    public function setName($name)
    {
        $this->name = $this->secure($name);
        if($this->pre_fill){
            if(isset($_POST[$name])) $this->setValue($_POST[$name]);
            if(isset($_GET[$name])) $this->setValue($_GET[$name]);
        }
        return $this;
    }

    /**
     * Setting required attribute
     * @param bool $required
     * @return Element
     */
    public function setRequired($required = true){
        if(is_bool($required)) $this->required = $required;
        return $this;
    }

    /**
     * Setting preFill - if element has value from previous form sent set it
     * @param bool $pre_fill
     * @return Element
     */
    public function setPreFill($pre_fill = true){
        if(is_bool($pre_fill)) $this->pre_fill = $pre_fill;
        return $this;
    }

    /**
     * Setting value
     * @param string $value
     * @return Element
     */
    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    /**
     * Return the elements value
     * @return string
     */
    public function getValue(){
        return $this->value;
    }

    /**
     * Return the elements id attribute
     * @return string
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Return the element name attribute
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Setting the elements class attribute, either string or array ( class1, class2, ... )
     * @param array|string $class
     * @return Element
     */
    public function setClass($class = '')
    {
        if(is_string($class))
            $this->classes[] = $this->secure($class);
        elseif(is_array($class))
            $this->classes = array_merge($this->classes, $this->secure($class));
        return $this;
    }

    /**
     * Setting attribute to element
     * @param string $attribute
     * @return Element
     */
    public function setAttribute($attribute){
        $this->attributes[] = $this->secure($attribute);
        return $this;
    }

    /**
     * Setting label to element, element must have id
     * @param string|Label $label
     * @return Label
     */
    public function setLabel($label){
        if($label instanceof Label){
            $this->label = $label;
            $this->label->setInputId($this->id);
        }
        elseif(is_string($label)){
            $this->label = new Label($this->secure($label));
            $this->label->setInputId($this->id);
        }
        return $label;
    }

    /**
     * Setting elements id attribute
     * @param string $id
     * @return Element
     */
    public function setId($id)
    {
        $this->id = $this->secure($id);
        if(isset($this->label)){
            $this->label->setInputId($this->secure($id));
        }
        return $this;
    }

    /**
     * Setting attribute 'disabled' to element
     * @return Element
     */
    public function disable(){
        $this->attributes[] = 'disabled';
        return $this;
    }

    /**
     * Setting attribute 'readonly' to element
     * @return Element
     */
    public function onlyRead(){
        $this->attributes[] = 'readonly';
        return $this;
    }

    /**
     * Render the outside div of element
     * @return string
     */
    protected function renderOpenDiv(){
        $html = '<div';
        if(isset($this->divId)) $html .= ' id="' . $this->divId . '"';
        if(isset($this->divClasses) && sizeof($this->divClasses)>0){
            $html .= ' class="';
            foreach($this->divClasses as $class){
                $html .= $class . ' ';
            }
            $html = substr($html, 0, -1);
            $html .= '"';
        }
        $html .= '>';
        return $html;
    }

    /**
     * @param array|string $param
     * @return array|string
     */
    private function secure($param = array())
    {
        if (is_string($param)) return htmlspecialchars($param, ENT_QUOTES);
        else if (is_array($param)) {
            foreach ($param as $key => $val) {
                $param[$key] = $this->secure($val);
            }
            return $param;
        } else return $param;
    }
}
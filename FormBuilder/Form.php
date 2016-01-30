<?php

include_once 'Input.php';
include_once 'Element.php';
include_once 'InputText.php';
include_once 'InputPassword.php';
include_once 'InputEmail.php';
include_once 'InputSearch.php';
include_once 'InputNumber.php';
include_once 'InputTel.php';
include_once 'InputSubmit.php';
include_once 'InputCheckBox.php';
include_once 'InputRadio.php';
include_once 'Label.php';
include_once 'TextArea.php';
include_once 'SelectOption.php';
include_once 'Select.php';
include_once 'Button.php';
include_once 'FieldSet.php';
include_once 'Legend.php';
include_once 'InputFile.php';
include_once 'FormException.php';

/**
 * Lite form builder library
 * @author Martin Forejt
 */
class Form
{
    private $name;
    private $method;
    private $action;
    private $id;
    private $classes = array();
    private static $forms;
    private $elements = array();
    private $attributes = array();
    private $enctype;

    /**
     * @param string $name , unique identifier of the form
     * @param string $method , form method attribute
     * @param string $action , form action attribute
     */
    public function __construct($name, $method = 'POST', $action = null)
    {
        $this->name = $name;
        $this->method = $this->secure(strtoupper($method));
        $this->action = $this->secure($action);
    }

    /**
     * Render the form
     */
    public function render()
    {
        $html = '<div id="' . $this->name . '"><form method="' . $this->method . '"';
        if (isset($this->action)) $html .= ' action="' . $this->action . '"';
        if (isset($this->id)) $html .= ' id="' . $this->id . '"';
        if (isset($this->enctype)) $html .= ' enctype="' . $this->enctype . '"';
        foreach ($this->attributes as $attr) $html .= ' ' . $attr;
        if (sizeof($this->classes) > 0) {
            $html .= ' class="';
            foreach ($this->classes as $class) {
                $html .= $class . ' ';
            }
            $html = substr($html, 0, -1);
            $html .= '"';
        }
        $html .= '>';

        foreach ($this->elements as $element) {
            if ($element instanceof Element) $html .= $element->render();
            elseif (is_string($element)) $html .= $element;
        }

        $html .= $this->checkOpenedFieldSet();
        $html .= '</form></div>';
        $_SESSION['php_lite_form_builder'][$this->name] = $this;

        echo $html;
    }

    /**
     * Return Form object by name, must call before validating, getting data
     * @param string $name , unique identifier of the form
     * @return Form | null
     */
    public static function getForm($name)
    {
        if (!isset(self::$forms))
            self::$forms = $_SESSION['php_lite_form_builder'];

        foreach (self::$forms as $form) {
            if ($form instanceof Form) {
                if ($form->getName() == $name) {
                    $form->process();
                    return $form;
                }
            }
        }
        return null;
    }

    /**
     * Verify if exist an element
     * @param Element $element
     * @return bool
     */
    public function issetElement($element)
    {
        if (in_array($element, $this->elements)) return true;
        else return false;
    }

    /**
     * Save form to static variable $forms
     */
    public function create()
    {
        self::$forms[$this->name] = $this;
    }

    /**
     * @return string, form method attribute
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Save form form session to static variable $forms, assigning values to element,
     * must call before validating or getting data
     */
    private function process()
    {
        if (isset($_SESSION['php_lite_form_builder'])) {
            foreach (self::$forms as $form) {

                if ($form instanceof Form) {

                    if ($form->getName() == $this->getName()) {

                        if ($form->getMethod() == 'POST') $data = $_POST;
                        elseif ($form->getMethod() == 'GET') $data = $_GET;
                        else return;

                        foreach ($form->getElements() as $element) {
                            if ($element instanceof Element) {
                                if ($element instanceof InputCheckBox || $element instanceof InputRadio) {
                                    if (isset($data[$element->getName()])) {
                                        $this->getElementByName($element->getName())->setChecked();
                                        $this->getElementByName($element->getName())->setValue($data[$element->getName()]);
                                    } else {
                                        $element->setValue(null);
                                        $element->setChecked(false);
                                    }
                                } elseif ($element instanceof Select) {
                                    if (isset($data[$element->getName()])) {
                                        foreach ($element->getOptions() as $option) {
                                            if ($data[$element->getName()] == $option->getValue()) {
                                                $this->getElementByName($element->getName())->getOptionByValue($option->getValue())->setSelected();
                                                $this->getElementByName($element->getName())->setValue($data[$element->getName()]);
                                            }
                                        }
                                    }
                                } elseif ($element instanceof TextArea) {
                                    if (isset($data[$element->getName()])) {
                                        $this->getElementByName($element->getName())->setText($data[$element->getName()]);
                                        $this->getElementByName($element->getName())->setValue($data[$element->getName()]);
                                    }
                                } elseif ($element instanceof InputFile) {
                                    if (isset($_FILES[$element->getName()])) {
                                        $this->getElementByName($element->getName())->setFile($_FILES[$element->getName()]);
                                    }
                                } else {
                                    if (isset($data[$element->getName()])) {
                                        $this->getElementByName($element->getName())->setValue($data[$element->getName()]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Validate all form elements by that params (e.g. required, length, pattern,...)
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->elements as $element) {
            if ($element instanceof Element) {
                if (!$element->isValid()) return false;
            }
        }
        return true;
    }

    /**
     * @return array, all form elements
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @return array, values of all elements as array ( element_name => value )
     */
    public function getData()
    {
        $res = array();
        foreach ($this->elements as $element) {
            if ($element instanceof Element) {
                if ($element instanceof InputFile)
                    $res[$element->getName()] = $this->getElementByName($element->getName())->getFile();
                else
                    $res[$element->getName()] = $this->getElementByName($element->getName())->getValue();
            }
        }
        return $res;
    }

    /**
     * Adding new element
     * @param Element $element
     * @param bool $required
     * @param bool $pre_fill
     * @return Element
     * @throws FormException
     */
    public function addElement($element, $required = false, $pre_fill = true)
    {
        if ($element instanceof Element) {
            $element->setRequired($required);
            $element->setPreFill($pre_fill);
            $this->elements[] = $element;

            if ($element instanceof InputFile)
                $this->setEnctype('multipart/form-data');

            return $element;
        } else
            throw new FormException('element is not instance of class Element!');
    }

    /**
     * Create new fieldset, 'open' him
     * @param FieldSet $fieldSet
     * @return FieldSet
     * @throws FormException
     */
    public function openFieldSet($fieldSet)
    {
        if ($fieldSet instanceof FieldSet) {
            $this->elements[] = $fieldSet;
            return $fieldSet;
        } else
            throw new FormException('param fieldset is not instance of class FieldSet!');
    }

    /**
     * Close the last open fieldset and return it
     * @return FieldSet
     * @throws FormException
     */
    public function closeFieldSet()
    {
        $open = false;
        foreach ($this->elements as $element) {
            if ($element instanceof FieldSet) $open = $element;
            elseif ($element == '</fieldset>') $open = false;
        }
        if ($open) {
            $this->elements[] = '</fieldset>';
            return $element;
        } else throw new FormException('before calling closeFieldSet must be fieldset opened.');
    }

    /**
     * If the last fieldset is not close, close it
     * @return string
     */
    private function checkOpenedFieldSet()
    {
        $open = false;
        foreach ($this->elements as $element) {
            if ($element instanceof FieldSet) $open = true;
            elseif ($element == '</fieldset>') $open = false;
        }
        return $open ? '</fieldset>' : '';
    }

    /***
     * Insert own html code
     * @param string
     * @return Form
     */
    public function addHtml($html)
    {
        if (is_string($html)) {
            $this->elements[] = $html;
        }
        return $this;
    }

    /**
     * Move element to position
     * @param int $position , new element position
     * @param Element
     */
    public function moveElement($position, $element)
    {
        $elements = array_values($this->elements);
        $result = array();
        $prevPosition = $position;
        foreach ($elements as $k => $v)
            if ($v == $element) $prevPosition = $k;

        if ($prevPosition == $position) return;

        for ($i = 0; $i < sizeof($elements); $i++) {
            if ($elements[$i] == $element) {
                unset($elements[$i]);
            } else {
                if (($prevPosition > $position && $i == $position) || ($prevPosition < $position && $i - 1 == $position)) {
                    $result[] = $element;
                    $i--;
                    $position = -1;
                } else {
                    $result[] = $elements[$i];
                }
            }
        }
        $this->elements = $result;
    }

    /**
     * Setting attribute to form
     * @param string $attr
     * @return Form
     */
    public function setAttribute($attr)
    {
        $this->attributes[] = $this->secure($attr);
        return $this;
    }

    /**
     * Setting id to form
     * @param string $id
     * @return Form
     */
    public function setId($id)
    {
        $this->id = $this->secure($id);
        return $this;
    }

    /**
     * Setting form class, you can call multiple times
     * @param string $class
     * @return Form
     */
    public function setClass($class)
    {
        $this->classes[] = $this->secure($class);
        return $this;
    }

    /**
     * Setting the form method attribute
     * @param string $method
     * @return Form
     */
    public function setMethod($method)
    {
        if (strtoupper($method) == 'POST' || strtoupper($method) == 'GET')
            $this->method = $method;
        return $this;
    }

    /**
     * Setting the form action attribute
     * @param string $action
     * @return Form
     */
    public function setAction($action)
    {
        $this->action = $this->secure($action);
        return $this;
    }

    /**
     * Setting the from enctype attribute
     * @param string $enctype
     * @return Form
     */
    public function setEnctype($enctype)
    {
        $this->enctype = $enctype;
        return $this;
    }

    /**
     * @return string, form id attribute
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string, the form name - unique identifier of the form
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $id , the element id attribute
     * @return Element | null
     */
    public function getElementById($id)
    {
        foreach ($this->elements as $element) {
            if ($element instanceof Element) {
                if ($element->getId() == $id) return $element;
            }
        }
        return null;
    }

    /**
     * @param string $name , the element name attribute
     * @return Element | null
     */
    public function getElementByName($name)
    {
        foreach ($this->elements as $element) {
            if ($element instanceof Element) {
                if ($element->getName() == $name) return $element;
            }
        }
        return null;
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
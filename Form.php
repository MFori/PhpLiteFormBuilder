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
include_once 'FormException.php';
include_once 'InputFile.php';

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

    public function __construct($name, $method = 'POST', $action = null)
    {
        $this->name = $name;
        $this->method = strtoupper($method);
        $this->action = $action;
    }

    /*
     * Vykreslí formulář
     */
    public function render()
    {
        $html = '<div id="' . $this->name . '"><form method="' . $this->method . '"';
        if (isset($this->action)) $html .= ' action="' . $this->action . '"';
        if (isset($this->id)) $html .= ' id="' . $this->id . '"';
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
        $_SESSION['forms'][$this->name] = $this;
        echo $html;
    }

    /*
     * Vrací formulář podle jména, musí se volat vždy po odeslání formuláře k zisku dat
     */
    public static function getForm($name)
    {
        if (!isset(self::$forms)) self::$forms = $_SESSION['forms'];
        foreach (self::$forms as $form) {
            if ($form instanceof Form) {
                if ($form->getName() == $name) {
                    //unset($_SESSION['forms'][$name]);
                    return $form;
                }
            }
        }
        return null;
    }

    /*
     * existuje element
     */
    public function issetElement($element)
    {
        if (in_array($element, $this->elements)) return true;
        else return false;
    }

    /*
     * Uloží formulář do proměnné, může se volat na jeden formůlář vícekrát, vždy se pouze přidá
     */
    public function create()
    {
        self::$forms[$this->name] = $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /*
     * ukládá formuláře ze session do statického pole, ukládá se hodnota formulářových prvků
     */
    public function process()
    {
        //self::$forms = $_SESSION['forms'];
        if (isset($_SESSION['forms'])) {
            foreach (self::$forms as $form) {

                if ($form->getMethod() == 'POST') $data = $_POST;
                elseif ($form->getMethod() == 'GET') $data = $_GET;
                else $data = null;

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

    /*
     * Validuje všechny elementy formuláře podle zadaných kriterií (required, length, pattern,...)
     */
    public function validate()
    {
        foreach ($this->elements as $element) {
            if ($element instanceof Element) {
                if (!$element->isValid()) return false;
            }
        }
        return true;
    }

    /*
     * Vrací všechny elementy formuláře
     */
    public function getElements()
    {
        return $this->elements;
    }

    /*
     * Vrací zadaného hodnoty všech elementů jako pole 'element' => 'hodnota'
     */
    public function getData()
    {
        $res = array();
        foreach ($this->elements as $element) {
            if ($element instanceof Element)
                $this->getElementByName($element->getName())->getValue();
            if ($element instanceof Element)
                $res[$element->getName()] = $this->getElementByName($element->getName())->getValue();
        }
        return $res;
    }

    /*
     * Přidá další element, required..., pre_fill - element automaticky předvyplněn
     */
    public function addElement($element, $required = false, $pre_fill = true)
    {
        if ($element instanceof Element) {
            $element->setRequired($required);
            $element->setPreFill($pre_fill);
            $element->setMethod($this->method);
            $this->elements[] = $element;
            return $element;
        } else
            throw new FormException('element is not instance of class Element!');
    }

    /*
     * Vytvoří nový fieldset 'otvře' jej
     */
    public function openFieldSet($fieldSet)
    {
        if ($fieldSet instanceof FieldSet) {
            $this->elements[] = $fieldSet;
            return $fieldSet;
        } else
            throw new FormException('fieldset is not instance of class FieldSet!');
    }

    /*
     * Zavírá předešlí otevřený fieldset a vrací ho
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

    /*
     * Pokud je neuzavřený fieldset zavírá ho
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

    /*
     * Vloží vlastní html kód
     */
    public function addHtml($html)
    {
        if (is_string($html)) {
            $this->elements[] = $html;
        }
        return $this;
    }

    /*
     * Přesune element na danou pozici
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

    /*
     *
     */
    public function setAttribute($attr)
    {
        $this->attributes[] = $attr;
        return $this;
    }

    /*
     * Nastavení id formuláře
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /*
     * Nastevení třídy formůláře, může se volat vícekrát - více tříd
     */
    public function setClass($class)
    {
        $this->classes[] = htmlspecialchars($class, ENT_QUOTES);
        return $this;
    }

    /*
     * Nastavení metody
     */
    public function setMethod($method)
    {
        if (strtoupper($method) == 'POST' || strtoupper($method) == 'GET')
            $this->method = $method;
        return $this;
    }

    /*
     * Nastavení action
     */
    public function setAction($action)
    {
        $this->action = htmlspecialchars($action, ENT_QUOTES);
        return $this;
    }

    /*
     * Vrací id formuláře
     */
    public function getId()
    {
        return $this->id;
    }

    /*
     * Vrací název formuláře
     */
    public function getName()
    {
        return $this->name;
    }

    /*
     * Vrací element formuláře podle id
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

    /*
     * Vrací element formuláře podle name
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
}
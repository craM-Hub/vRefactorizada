<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\DataElement;
use ProyectoWeb\utils\Forms\SelectElement;
class OptionElement extends DataElement
{
    private $parent;
    private $text;
    public function __construct(SelectElement $parent, string $text)
	{
        $this->parent = $parent;
        $this->text = $text;
        parent::__construct();
    }

    public function isSelected(): bool
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            //Puede ser un array si es multiple
            if ($this->parent->isMultiple()) {
                if (!empty($this->parent->getValue())) {
                    foreach ($this->parent->getValue() as $chkval) {	
                        if ($chkval === $this->defaultValue) {
                            return true;
                        }
                    }
                }
            } else {
                return ($this->parent->getValue() === $this->defaultValue);   
            }
        } else {
            if ($this->parent->isMultiple()) {
                if (is_array($this->parent->getDefaultValue())) {
                    return in_array($this->defaultValue, $this->parent->getDefaultValue());
                }
            } else {
                return $this->parent->getDefaultValue() === $this->defaultValue;
            }
        }
        return false;
    }
   
     /**
     * Genera el HTML del elemento
     *
     * @return string
     */
    public function render(): string
    {
        $html = '<option ' ;
        $html .= " value='{$this->defaultValue}'";
        $html .= $this->renderAttributes();
        $html .= ($this->isSelected() ? " selected" : "");    
        $html .= ">" . $this->text;
        return $html;
    }
}
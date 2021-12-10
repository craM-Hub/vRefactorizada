<?php

namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\DataElement;
class InputElement extends DataElement
{
    /**
     * Tipo del input
     *
     * @var string
     */
    protected $type;


    public function __construct(string $type)
    {
        $this->type = $type;
        parent::__construct();
    }

    /**
     * Genera el HTML del elemento
     *
     * @return string
     */
    public function render(): string
    {
        $this->setPostValue();
        $html = "<input type='{$this->type}' name='{$this->name}'" ; 
        
        if ('POST' === $_SERVER['REQUEST_METHOD']) {           
            $html .= " value='" . $this->getValue() . "'";
        } else {
            $html .= " value='{$this->defaultValue}'";
        }
        $html .= $this->renderAttributes();
        $html .= '>';
        return $html;
    }

}

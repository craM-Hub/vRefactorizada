<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\InputElement;

class PasswordElement extends InputElement
{

    public function __construct()
    {
        parent::__construct('password');
    }

    public function render(): string
    {
        $this->setPostValue();
        $html = "<input type='{$this->type}' name='{$this->name}'" ; 
        $html .= $this->renderAttributes();
        $html .= '>';
        return $html;
    }
}

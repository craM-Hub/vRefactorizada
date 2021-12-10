<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\utils\Validator\NumberValidator;

class NumberElement extends InputElement
{

    public function __construct()
    {
        $this->setValidator(new NumberValidator("Número inválido", true));
        parent::__construct('number');
    }

}

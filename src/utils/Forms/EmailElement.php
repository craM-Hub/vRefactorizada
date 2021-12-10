<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\InputElement;

use ProyectoWeb\utils\Validator\EmailValidator;

class EmailElement extends InputElement
{

    public function __construct()
    {
        $this->setValidator(new EmailValidator("Formato inválido de correo", true));
        parent::__construct('email');
    }

}

<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class NumberValidator extends Validator {

    public function doValidate(): bool{
        $ok = ($this->data === "0") || filter_var($this->data, FILTER_VALIDATE_INT);
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        return $ok;
    } 
}
<?php
namespace ProyectoWeb\utils\Validator;
use ProyectoWeb\utils\Validator\Validator;

class EmailValidator extends Validator {

    public function doValidate(): bool{
        //FILTER_SANITIZE_EMAIL
        $result = filter_var($this->data, FILTER_VALIDATE_EMAIL);
        if (!$result) {
            $this->errors[] =  $this->message;
        }
        
        return $result;
    } 
}
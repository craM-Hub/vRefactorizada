<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class NotEmptyValidator extends Validator {
    public function doValidate(): bool{
        if (is_array($this->data)) {
            $ok = (count($this->data) > 0);
        }else {
            $ok = !empty($this->data);
        }
        if (!$ok) {
            $this->errors[] = $this->message;
        }
        return $ok;
    } 
}
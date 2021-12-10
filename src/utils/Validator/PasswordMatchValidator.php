<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;
use ProyectoWeb\utils\Forms\PasswordElement;
class PasswordMatchValidator extends Validator {
    private $passwordEl;

    public function __construct(PasswordElement $passwordEl, string $message, bool $last = false)
    {
        $this->passwordEl = $passwordEl;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
        $ok =  ($this->data === $this->passwordEl->getValue());
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        
        return $ok;
    } 
}
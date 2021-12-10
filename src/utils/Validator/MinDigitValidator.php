<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class MinDigitValidator extends Validator {

    private $minDigit;
    public function __construct(int $minDigit, string $message, bool $last = false)
    {
        $this->minDigit = $minDigit;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
        $cont = 0;
        for ($i = 0; $i < strlen($this->data); $i++){
            if (ctype_digit($this->data[$i])) {
                $cont++;
            }
        }
        $ok = ($cont >= $this->minDigit);
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        return $ok;
    } 
}
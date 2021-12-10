<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class MinLowerCaseValidator extends Validator {

    private $minLowerCase;
    public function __construct(int $minLowerCase, string $message, bool $last = false)
    {
        $this->minLowerCase = $minLowerCase;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
        $cont = 0;
        for ($i = 0; $i < strlen($this->data); $i++){
            if (ctype_lower($this->data[$i])) {
                $cont++;
            }
        }
        $ok = ($cont >= $this->minLowerCase);
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        return $ok;
    } 
}
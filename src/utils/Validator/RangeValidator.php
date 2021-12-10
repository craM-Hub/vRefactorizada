<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class RangeValidator extends Validator {

    private $min;
    private $max;
    
    public function __construct(int $min, int $max, string $message, bool $last = false)
    {
        $this->min = $min;
        $this->max = $max;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
       
        $ok = ($this->data >= $this->min) && ($this->data <= $this->max);
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        return $ok;
    } 
}
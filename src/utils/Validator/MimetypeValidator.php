<?php
namespace ProyectoWeb\utils\Validator;
use ProyectoWeb\utils\Validator\Validator;

class MimetypeValidator extends Validator {

    private $mimeTypes;
    public function __construct(array $mimeTypes, string $message, bool $last = false)
    {
        $this->mimeTypes = $mimeTypes;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
        $ok = in_array($this->data["type"], $this->mimeTypes);
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        
        return $ok;
    } 
}
<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class EmailDomainValidator extends Validator {
    private $domain;

    public function __construct(string $domain, string $message, bool $last = false)
    {
        $this->domain = $domain;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
        $result = (substr($this->data, strlen($this->data) - strlen("@" . $this->domain) ) === "@" . $this->domain);
        if (!$result) {
            $this->errors[] =  $this->message;
        }
        
        return $result;
    } 
}
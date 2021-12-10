<?php
namespace ProyectoWeb\utils\Forms\custom;

use  ProyectoWeb\utils\Forms\DivElement;

use  ProyectoWeb\utils\Forms\CompoundElement;

class MyFormGroup extends CompoundElement
{
    private $container;
    public function __construct(array $formElements ){
        $this->container = new DivElement();
        $this->container->setCssClass("form-group");
        foreach($formElements as $formElement) {
            $this->container->appendChild($formElement);
        }        
        $this->appendChild($this->container);
    }
    public function render(): string{
        return $this->container->render();
    }
    public function validate(){
        $this->container->validate();
    }
    public function hasError(): bool{
        return $this->container->hasError();
    }
    public function getErrors(): array
    {
        return $this->container->getErrors();
    }
 
}
<?php

namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\Element;
use ProyectoWeb\utils\Validator\Validator;
use ProyectoWeb\utils\Validator\IValidator;


abstract class DataElement extends Element implements IValidator
{
    /**
     * Nombre del campo en el formulario
     *
     * @var string
     */
    protected $name;
    /**
     * Valor del campo después del post
     *
     * @var string
     */
    protected $value;

    /**
     * Valor por defecto (puede ser de cualquier tipo: string, array, ...)
     */
    protected $defaultValue;

    /**
     * Validator para este campo
     */
    protected $validator;

      /**
     * Errores de validación del elemento
     *
     * @var array
     */
    public $errors;

    /**
     * bandera para indicar que ya se han fijados los datos del POST
     *
     * @var bool
     */
    private $donePostValue;

    public function __construct()
    {
        $this->donePostValue = false;
        $this->errors = [];
        
    }
     /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get nombre del campo en el formulario
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }
     /**
     * Get valor del campo
     *
     * @return  string
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set valor del campo en el POST
     *
     * @param  string  $value  Valor del campo en el POST
     *
     * @return  self
     */ 
    public function setPostValue(): DataElement
    {
        //Sólo hay que hacerlo una vez, pero se puede llamar dos veces: en validate y en render
        if (!$this->donePostValue) {
            if ('POST' === $_SERVER['REQUEST_METHOD']) { 
                $this->value = $this->sanitizeInput();
                $this->donePostValue = true;
            }
        }
       
        return $this;
    }

    /**
     * Set valor por defecto
     *
     * @param  mixed  $defaultValue  Valor por defecto
     *
     * @return  self
     */ 
    public function setDefaultValue($defaultValue): DataElement
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }


    /**
     * Get valor por defecto (puede ser de cualquier tipo: string, array, ...)
     */ 
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Protección ante hackeos del campo del POST
     *
     * @return mixed
     */
    protected function sanitizeInput()
    {
        if (isset($_POST[$this->name])){
            $_POST[$this->name] =  $this->sanitize($_POST[$this->name]);
            return $_POST[$this->name];
        }
        return "";
    }

    /**
     * Protección ante hackeos
     *
     * @return string
     */
    protected function sanitize($data): string
    {
        if (isset($data)){
           return htmlspecialchars(stripslashes(trim($data)));
        }
        return "";
    }
    /**
     * Devuelve el valor del campo a su valor original
     *
     * @return void
     */
    public function reset(){
        $this->value = $this->defaultValue;
    }
    
    /**
     * Get the value of validator
     */ 
    public function getValidator()
    {
        return $this->validator;
    }
    
    /**
     * Set the value of validator
     *
     * @param  Validator  $validator
     *
     * @return  self
     */ 
    public function setValidator(Validator $validator): DataElement
    {
        $this->validator = $validator; 
        return $this;
    }

    /**
     * Añade un validador al validador por defecto
     *
     * @param Validator $validator
     * @return self
     */
    public function appendValidator(Validator $validator): DataElement
    {
        if (!empty($this->validator)) {
            $this->validator->setNextValidator($validator);
        }
       
        return $this;
    }

    /**
     * Valida el campo según los criterios del validador
     *
     * @return void
     */
    public function validate()
    {
        $this->setPostValue();
        if (!empty($this->getValidator())) {
            $this->validator->setData($this->getValue());
            $this->validator->validate();
        }
    }

    /**
     * True si el validador tiene errores
     *
     * @return boolean
     */
    public function hasError(): bool
    {
        if (!empty($this->getValidator())) {
            return $this->validator->hasError();
        }
        return false;
    }
    
    /**
     * Devuelve los errores de validación
     *
     * @return array
     */
    public function getErrors(): array
    {
        if (!empty($this->getValidator())) {
            return $this->validator->getErrors();
        }
        return [];
    }

}
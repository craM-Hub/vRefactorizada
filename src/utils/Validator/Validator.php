<?php
namespace ProyectoWeb\utils\Validator;
abstract class Validator{

    /**
     * Texto del mensaje de error de validación
     *
     * @var string
     */
    protected $message;

    /**
     * next element in chain or responsibilities
     *
     * @var Validator
     */
    protected $nextValidator;
    

    /**
     * Si es true, se para la cadena de responsabilidad
     *
     * @var bool
     */
    protected $last;

    /**
     * Datos a los que aplicar la validación
     *
     * @var string
     */
    protected $data;

    /**
     * Errores de validación
     *
     * @var array
     */
    protected $errors;
    
    /**
     * @param string $message
     * @param boolean $last
     */
    public function __construct(string $message, bool $last = false)
    {
        $this->message = $message;
        $this->last = $last;
        $this->errors = [];
    }

    /**
     * Set the value of data
     *
     * @return  self
     */ 
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
    
    /**
     * Fija el siguiente validador en la cadena de validación
     * @param AbstractValidator
     */
    public function setNextValidator(Validator $nextValidator)
    {
        $this->nextValidator = $nextValidator;
        return $this;
    }

    /**
     * Implementa la cadena de validación y fija los errores de validación
     *
     * @return void
     */
    public function validate()
    {
        $this->doValidate();
        //Debemos continuar la cadena?
        if ((!($this->last && $this->hasError())) 
            && $this->nextValidator != null){
            $this->nextValidator->setData($this->data);
            $this->nextValidator->validate();
            if ($this->nextValidator->hasError()) {
                $this->errors = array_merge($this->errors, $this->nextValidator->getErrors());
            }
            
        }
 
    }

    /**
     * Tiene errores?
     *
     * @return boolean
     */
    public function hasError(): bool{
        return (count($this->errors));
    }

    /**
     * Devuelve los errores de validación
     *
     * @return array
     */
    public function getErrors(): array{
        return $this->errors;
    }
    
    /**
     * Devuelve true si pasa la validación. False en caso contrario
     * Este método es el único que deben implementar todos los validadores.
     *
     * @return boolean
     */
    abstract public function doValidate(): bool;
    
}
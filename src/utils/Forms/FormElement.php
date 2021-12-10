<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\CompoundElement;
use ProyectoWeb\utils\Forms\DataElement;
class FormElement extends CompoundElement
{
    /**
     * @var string
     *      
    **/
    private $action;
    
    /**
     * @var string
     *      
    **/
    private $enctype;
    
 
    public function __construct(string $action = "", string $enctype = "")
	{
        $this->action = $action;
        $this->enctype = $enctype;
        parent::__construct();
    }
    public function render(): string
    {
        $html = 
            "<form action='{$this->action}' method='POST'" .
            (!empty($this->enctype) ? " enctype='$this->enctype' " : '');
            $html .= $this->renderAttributes();
            $html .= ">";
            foreach ($this->getChildren() as $child) {
              $html .= $child->render();
            }   
        $html .= "</form>"; 
        return $html;
    }
    public function hasError(): bool
    {
        return (count($this->errors) > 0);
    }
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $error){
        $this->errors[] = $error;
    }

    public function validate()
    {
        if (empty($_POST)) {
            $this->errors[] = 'Se ha producido un error al validar el formulario';
            if ($this->hasChildOfClass('FileElement')){
                if ( !empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES)) {	
                    $this->errors[]  = 'El archivo es demasiado grande. Debes subir un archivo menor que ' . ini_get("upload_max_filesize");
                } 
            }

        } else {
            foreach ($this->getChildren() as $child) {
                if (is_subclass_of($child, "ProyectoWeb\utils\Forms\DataElement")) {
                    $child->validate();
                    if ($child->hasError()){
                        $this->errors = array_merge($this->errors, $child->getErrors());
                    }	
                }
            } 
        }
    }

    /**
     * Devuelve a su valor por defecto todos los campos del formulario
     *
     * @return void
     */
    public function reset()
    {
        foreach ($this->getChildren() as $child) {
            if (is_subclass_of($child, "ProyectoWeb\utils\Forms\DataElement")) {
                $child->reset();
            }
        } 
    }
}
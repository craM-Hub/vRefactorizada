<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\CompoundElement;

class SelectElement extends CompoundElement
{
    /**
     * Es un select múltiple?
     * @var bool
     */
    private $multiple;

    /**
     * @param boolean $multiple
     */
    public function __construct(bool $multiple = false)
	{
        $this->multiple = $multiple;
        parent::__construct();
    }

    /**
     * Devuelve los options seleccionados
     *
     * @return void
     */
    public function getSelected(){
        $values = [];
        foreach ($this->getChildren() as $child) {
            if ($child->isSelected()) {
                $values[] = $child->getDefaultValue();
            }
           
        }  
        return $values;
    }
     /**
     * Valida el campo según los criterios del validador
     * Es un caso especial porque aunque tenga hijos, estos no tiene validador (hereda de CompoundElement)
     * @return void
     */
    public function validate()
    {
        $this->setPostValue();
        if (!empty($this->getValidator())) {
            $this->validator->setData($this->getValue());
            $this->validator->validate();
            $this->errors = array_merge($this->errors, $this->validator->getErrors());
        }
    }

     /**
     * Protección ante hackeos
     *
     * @return string
     */
    public function sanitizeInput() {
       
        if ($this->isMultiple()) {
            if (!empty($_POST[$this->name])){
                foreach ($_POST[$this->name] as $key => $data){
                    $_POST[$this->name][$key] = htmlspecialchars(stripslashes(trim($data)));
                }
                return $_POST[$this->name];
            }
        }else{
            return parent::sanitizeInput();
        }
        return "";
    }
    /**
     *
     * @return boolean
     */
    public function isMultiple(): bool
    {
        return ($this->multiple === true);
    }
    
    public function render(): string
    {
       
        $this->setPostValue();
        $html = "<select name='{$this->name}" . ($this->multiple ? '[]' : '') . "'" ;
        $html .= $this->renderAttributes();
        $html .= ($this->multiple ? " multiple " : '');
        $html .= ">";
        foreach ($this->getChildren() as $child) {
            $html .= $child->render();
          }  
        $html .= '</select>';  
        return $html;
    }
}
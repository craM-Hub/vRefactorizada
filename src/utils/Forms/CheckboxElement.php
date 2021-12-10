<?php
namespace ProyectoWeb\utils\Forms;

use  ProyectoWeb\utils\Forms\DataElement;


class CheckboxElement extends DataElement
{
    /**
     * Texto de la opción
     *
     * @var string
     */
    private $text;
    
    /**
     * Marcado por defecto?
     *
     * @var bool
     */
    private $checked;

    public function __construct(string $text, bool $checked = false)
	{
       $this->text = $text;
       $this->checked = $checked;
       parent::__construct();
    }
    private function isInputArray(): bool
    {
        return (substr($this->name, strlen($this->name) - strlen('[]') ) === '[]');
    }

    private function getRealName(): string
    {
        return substr($this->name, 0, strlen($this->name) - 2);
    }

    protected function sanitizeInput(){
        if ($this->isInputArray()) {
            $realName = $this->getRealName();
            if (!empty($_POST[$realName])){
                foreach ($_POST[$realName] as $key => $data){
                    $_POST[$realName][$key] = $this->sanitize($data);
                }
                return $_POST[$realName];
            }
        } else {
            return parent::sanitizeInput();
        }
        return "";
    }
    
    public function isChecked(): bool
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if ($this->isInputArray()) {
                $realName =  $realName = $this->getRealName();
                if (!empty($_POST[$realName])) {
                    foreach($_POST[$realName] as $chkval) {	
                        if($chkval === $this->defaultValue) {
                            return true;
                        }
                    }
                }
            } else if (isset($_POST[$this->name])) {
                return ($_POST[$this->name] === $this->defaultValue);
            }
        } else {
            return $this->checked;
        }
        return false;
    }
    /**
     * Genera el HTML del elemento
     *
     * @return string
     */
    public function render(): string
    {
        $html = "<input type='checkbox' name='{$this->name}' " ;
        $html .= " value='{$this->defaultValue}'";
        $html .= $this->renderAttributes();
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $this->setPostValue();
        }
        $html .= ($this->isChecked() ? ' checked' : '');
        $html .= '>' . $this->text;
        return $html;
    }

}
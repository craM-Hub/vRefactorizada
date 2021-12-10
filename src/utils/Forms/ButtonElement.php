<?php
namespace ProyectoWeb\utils\Forms;

use  ProyectoWeb\utils\Forms\DataElement;

class ButtonElement extends DataElement
{
    /**
     * Texto del botón
     *
     * @var string
     */
    private $text;
    
    public function __construct(string $text)
	{
        $this->text = $text;
        parent::__construct();
    }

    public function render(): string
    {
       return 
            "<button type='submit'" . 
                (!empty($this->name) ? " name='$this->name' " : '') .
                $this->renderAttributes() . 
            ">{$this->text}</button>";
       
    }
}
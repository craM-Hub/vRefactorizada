<?php
namespace ProyectoWeb\utils\Forms;

abstract class Element
{
    
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $cssClass;
    /**
     * @var string
     */
    protected $style;
   
    public function __construct()
	{
    }
    /**
     * Get the value of id
     *
     * @return  string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     *
     * @return  self
     */ 
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of cssClass
     *
     * @return  string
     */ 
    public function getCssClass()
    {
        return $this->cssClass;
    }
    /**
     * Set the value of class
     *
     * @param  string  $class
     *
     * @return  self
     */ 
    public function setCssClass(string $cssClass)
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    /**
     * Set the value of style
     *
     * @param  string  $style
     *
     * @return  self
     */ 
    public function setStyle(string $style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Genera el HTML para los atributos comunes
     *
     * @return string
     */
    protected function renderAttributes(): string
    {
        $html = (!empty($this->id) ? " id='$this->id' " : '');
        $html .= (!empty($this->cssClass) ? " class='$this->cssClass' " : '');
        $html .= (!empty($this->style) ? " style='$this->style' " : '');
        return $html;
    }
    /**
     * Genera el código HTML del elemento
     *
     * @return string
     */
    abstract public function render(): string;
 
}
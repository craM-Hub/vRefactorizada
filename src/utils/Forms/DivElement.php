<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\CompoundElement;

class DivElement extends CompoundElement
{
    public function render(): string
    {
        $html = 
            "<div " . 
            $this->renderAttributes() .
            ">";
            foreach ($this->getChildren() as $child) {
              $html .= $child->render();
            }   
        $html .= "</div>"; 
        return $html;
    }
}
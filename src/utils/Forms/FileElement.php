<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\exceptions\FileException;

class FileElement extends InputElement
{
    /**
     * Nombre del fichero una vez movido a su destino
     *
     * @var string
     */
    private $fileName;
    
    public function __construct()
    {
        $this->fileName = "";
        parent::__construct('file');
    }

     /**
     * Protección ante hackeos del campo del POST
     *
     * @return mixed
     */
    protected function sanitizeInput()
    {
        if (isset($_FILES[$this->name])){
            $_FILES[$this->name]['name'] =  $this->sanitize($_FILES[$this->name]['name']);
            return $_FILES[$this->name];
        }
        return "";
    }
     /**
     * Valida el campo según los criterios del validador
     *
     * @return void
     */
    public function validate()
    {
        //Los posibles errores de subida de archivos se contemplan en FormElement
        //See http://www.php.net/manual/en/ini.core.php#ini.post-max-size
        if ($_FILES[$this->name]["error"] !== UPLOAD_ERR_OK){
            switch ($_FILES[$this->name]["error"]){
                case UPLOAD_ERR_NO_FILE:
                    $this->errors[] = 'Debes seleccionar un fichero';
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $this->errors[] = 'El fichero es demasiado grande';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->errors[] = 'No se ha podido subir el fichero completo';
                    break;
                default:
                   $this->errors[] = 'No se ha podido subir el fichero';
            }
          
        }

        if ((!empty($_FILES[$this->name]["tmp_name"])) && (false === is_uploaded_file($_FILES[$this->name]["tmp_name"]))){
            $this->errors[] = 'El archivo no se ha subido mediante un formulario';
        }

        $this->setPostValue();
        // Si no pasa esta validación no continuamos
        if (!($this->hasError()) && !empty($this->getValidator())) {
            $this->validator->setData($this->getValue());
            $this->validator->validate();
            $this->errors = array_merge($this->errors, $this->validator->getErrors());
        }
        if (!($this->hasError())) {
            //Evitar hackeo (mediante inyección de html)
            $this->fileName = $this->sanitize($_FILES[$this->name]["name"]);
        }
    }
    public function hasError(): bool
    {
        return (count($this->errors) > 0);
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
    /**
     * Genera el HTML del elemento
     *
     * @return string
     */
    public function render(): string
    {
        $this->setPostValue();
        $html = "<input type='{$this->type}' name='{$this->name}'" ; 
        $html .= $this->renderAttributes();
        $html .= '>';
        return $html;
    }

    /**
     * Get nombre del fichero una vez movido a su destino
     *
     * @return  string
     */ 
    public function getFileName()
    {
        return $this->fileName;
    }
     /**
     * Guarda en $desPath el fichero $this->fileName
     * @param string $destPath
     * @throws FileException
     */
    public function saveUploadedFile(string $destPath)
    {
        $ruta =  $destPath . $this->fileName;

        if (true === is_file($ruta)){
            $idUnico = time();
            $this->fileName = $idUnico  . "_" . $this->fileName;
            $ruta =  $destPath . $this->fileName;
        }
        if (false === @move_uploaded_file($_FILES[$this->name]["tmp_name"], $ruta)){
            throw new FileException("No se puede mover el fichero a su destino");
        }
    }


}

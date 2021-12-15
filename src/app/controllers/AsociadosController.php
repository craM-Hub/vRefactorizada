<?php

namespace ProyectoWeb\app\controllers;

use Psr\Container\ContainerInterface;
use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\utils\Forms\TextareaElement;
use ProyectoWeb\utils\Forms\ButtonElement;
use ProyectoWeb\utils\Forms\FormElement;
use ProyectoWeb\utils\Forms\custom\MyFormControl;
use ProyectoWeb\utils\Validator\NotEmptyValidator;
use ProyectoWeb\core\App;
use ProyectoWeb\utils\Forms\LabelElement;
use ProyectoWeb\utils\Forms\FileElement;
use ProyectoWeb\utils\Validator\MimetypeValidator;
use ProyectoWeb\utils\Validator\MaxSizeValidator;
use ProyectoWeb\entity\Asociado;
use ProyectoWeb\repository\AsociadoRepository;
use ProyectoWeb\exceptions\QueryException;

class AsociadosController
{
    protected $container;
    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function asociados($request, $response, $args)
    {
        $info = $urlImagen = "";
        $title =  'Asociados';
        $nombre = new InputElement('text');
        $nombre
            ->setName('nombre')
            ->setId('nombre')
            ->setValidator(new NotEmptyValidator('El nombre es obligatorio', true));
        $nombreWrapper = new MyFormControl($nombre, 'Nombre', 'col-xs-12');

        $description = new TextareaElement();
        $description
            ->setName('descripcion')
            ->setId('descripcion');
        $descriptionWrapper = new MyFormControl($description, 'Descripción', 'col-xs-12');

        $fv = new MimetypeValidator(['image/jpeg', 'image/jpg', 'image/png'], 'Formato no soportado', true);
        $fv->setNextValidator(new MaxSizeValidator(2 * 1024 * 1024, 'El archivo no debe exceder 2M', true));

        $file = new FileElement();
        $file
            ->setName('imagen')
            ->setId('imagen')
            ->setValidator($fv);

        $labelFile = new LabelElement('Imagen', $file);

        $b = new ButtonElement('Send');
        $b->setCssClass('pull-right btn btn-lg sr-button');

        $form = new FormElement('', 'multipart/form-data');
        $form
            ->setCssClass('form-horizontal')
            ->appendChild($labelFile)
            ->appendChild($file)
            ->appendChild($nombreWrapper)
            ->appendChild($descriptionWrapper)
            ->appendChild($b);

        $repositorio = new AsociadoRepository();
        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
            if (!$form->hasError()) {
                try {

                    $file->saveUploadedFile(APP::get('rootDir') . Asociado::RUTA_IMAGENES_ASOCIADO);
                    // Create a new SimpleImage object
                    $simpleImage = new \claviska\SimpleImage();
                    $simpleImage
                        ->fromFile(Asociado::RUTA_IMAGENES_ASOCIADO . $file->getFileName())
                        ->resize(50, 50)
                        ->toFile(Asociado::RUTA_IMAGENES_ASOCIADO . $file->getFileName());
                    $info = 'Imagen enviada correctamente';
                    $urlImagen = Asociado::RUTA_IMAGENES_ASOCIADO . $file->getFileName();
                    $asociado = new Asociado($nombre->getValue(), $file->getFileName(), $description->getValue());
                    $repositorio->save($asociado);
                    $form->reset();
                } catch (Exception $err) {
                    $form->addError($err->getMessage());
                    $imagenErr = true;
                }
            } else {
            }
        }

        try {
            $asociados = $repositorio->findAll();
        } catch (QueryException $qe) {
            $asociados = [];
            echo $qe->getMessage();
            //En este caso podríamos generar un mensaje de log o parar el script mediante die($qe->getMessage())
        }
        return $this->container->renderer->render($response, "asociados.view.php", compact('title', 'info', 'form', 'asociados'));
    }
}
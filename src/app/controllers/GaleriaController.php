<?php

namespace ProyectoWeb\app\controllers;

use Psr\Container\ContainerInterface;
use ProyectoWeb\utils\Forms\TextareaElement;
use ProyectoWeb\utils\Forms\ButtonElement;
use ProyectoWeb\utils\Forms\FormElement;
use ProyectoWeb\utils\Forms\custom\MyFormControl;
use ProyectoWeb\utils\Validator\NotEmptyValidator;
use ProyectoWeb\core\App;
use ProyectoWeb\utils\Forms\LabelElement;
use ProyectoWeb\utils\Forms\FileElement;
use ProyectoWeb\utils\Forms\SelectElement;
use ProyectoWeb\utils\Forms\OptionElement;
use ProyectoWeb\utils\Validator\MimetypeValidator;
use ProyectoWeb\utils\Validator\MaxSizeValidator;
use ProyectoWeb\entity\ImagenGaleria;
use ProyectoWeb\repository\ImagenGaleriaRepository;
use ProyectoWeb\repository\CategoriaRepository;

class GaleriaController
{
    protected $container;
    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function galeria($request, $response, $args)
    {
        $title = 'Contact';
        $info = $urlImagen = "";

        $description = new TextareaElement();
        $description
            ->setName('descripcion')
            ->setId('descripcion')
            ->setValidator(new NotEmptyValidator('La descripción es obligatoria', true));
        $descriptionWrapper = new MyFormControl($description, 'Descripción', 'col-xs-12');

        $fv = new MimetypeValidator(['image/jpeg', 'image/jpg', 'image/png'], 'Formato no soportado', true);

        $fv->setNextValidator(new MaxSizeValidator(2 * 1024 * 1024, 'El archivo no debe exceder 2M', true));
        $file = new FileElement();
        $file
            ->setName('imagen')
            ->setId('imagen')
            ->setValidator($fv);

        $labelFile = new LabelElement('Imagen', $file);


        $repositorio = new ImagenGaleriaRepository();

        $repositorioCategoria = new CategoriaRepository();

        $categoriasEl = new SelectElement(false);

        $categoriasEl
            ->setName('categoria');
        $categorias = $repositorioCategoria->findAll();
        foreach ($categorias as $categoria) {
            $option = new OptionElement($categoriasEl, $categoria->getNombre());

            $option->setDefaultValue($categoria->getId());

            $categoriasEl->appendChild($option);
        }

        $categoriaWrapper = new MyFormControl($categoriasEl, 'Categoría', 'col-xs-12');

        $b = new ButtonElement('Send');
        $b->setCssClass('pull-right btn btn-lg sr-button');

        $form = new FormElement('', 'multipart/form-data');
        $form
            ->setCssClass('form-horizontal')
            ->appendChild($labelFile)
            ->appendChild($file)
            ->appendChild($descriptionWrapper)
            ->appendChild($categoriaWrapper)
            ->appendChild($b);

        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
            if (!$form->hasError()) {
                try {
                    $file->saveUploadedFile(APP::get('rootDir') . ImagenGaleria::RUTA_IMAGENES_GALLERY);
                    // Create a new SimpleImage object
                    $simpleImage = new \claviska\SimpleImage();
                    $simpleImage
                        ->fromFile(APP::get('rootDir') . ImagenGaleria::RUTA_IMAGENES_GALLERY . $file->getFileName())
                        ->resize(975, 525)
                        ->toFile(APP::get('rootDir') . ImagenGaleria::RUTA_IMAGENES_PORTFOLIO . $file->getFileName())
                        ->resize(650, 350)
                        ->toFile(APP::get('rootDir') . ImagenGaleria::RUTA_IMAGENES_GALLERY . $file->getFileName());

                    $urlImagen = ImagenGaleria::RUTA_IMAGENES_GALLERY . $file->getFileName();
                    //Grabamos en la base de datos
                    $imagenGaleria = new ImagenGaleria($file->getFileName(), $description->getValue(), 0, 0, 0, $categoriasEl->getValue());
                    $repositorio->save($imagenGaleria);
                    $info = 'Imagen insertada correctamente';
                    $form->reset();
                } catch (Exception $err) {
                    $form->addError($err->getMessage());
                } catch (QueryException $qe) {
                    $form->addError($qe->getMessage());
                }
            }
        }

        try {
            $imagenes = $repositorio->findAll();
        } catch (QueryException $qe) {
            $imagenes = [];
            echo $qe->getMessage();
            //En este caso podríamos generar un mensaje de log o parar el script mediante die($qe->getMessage())
        }
        return $this->container->renderer->render($response, "galeria.view.php", compact('title', 'info', 'form', 'imagenes', 'repositorio'));
    }
}
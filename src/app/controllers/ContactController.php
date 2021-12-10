<?php
namespace ProyectoWeb\app\controllers;
use Psr\Container\ContainerInterface;
use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\utils\Forms\TextareaElement;
use ProyectoWeb\utils\Forms\EmailElement;
use ProyectoWeb\utils\Forms\ButtonElement;
use ProyectoWeb\utils\Forms\FormElement;
use ProyectoWeb\utils\Forms\custom\MyFormGroup;
use ProyectoWeb\utils\Forms\custom\MyFormControl;
use ProyectoWeb\utils\Validator\NotEmptyValidator;
use ProyectoWeb\entity\Mensaje;
use ProyectoWeb\repository\MensajeRepository;

class ContactController
{
    protected $container;
    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function contact($request, $response, $args)
    {
        $title= 'Contact';
        $info = "";
        $firstName = new InputElement('text');
        $firstName
        ->setName('firstName')
        ->setId('firstName')
        ->setValidator(new NotEmptyValidator('El campo first name no puede estar vacío', true));


        $lastName = new InputElement('text');
        $lastName
        ->setName('lastName')
        ->setId('lastName');

        $name = new MyFormGroup([new MyFormControl($firstName, "First Name"), new MyFormControl($lastName, "Last Name")]);

        $email = new EmailElement();
        $email
            ->setName('email')
            ->setId('email');

        $emailWrapper = new MyFormGroup([new MyFormControl($email, 'Correo', 'col-xs-12')]);


        $subject = new InputElement('text');
        $subject
            ->setName('subject')
            ->setId('subject')
            ->setValidator(new NotEmptyValidator('El campo asunto no puede estar vacío', true));

        $subjectWrapper = new MyFormGroup([new MyFormControl($subject, 'Asunto', 'col-xs-12')]);

        $message = new TextareaElement();
        $message
            ->setName('message')
            ->setId('message');
        $messageWrapper = new MyFormGroup([new MyFormControl($message, 'Mensaje', 'col-xs-12')]);

        $b = new ButtonElement('Send');
        $b->setCssClass('pull-right btn btn-lg sr-button');

        $form = new FormElement();

        $form
            ->setCssClass('form-horizontal')
            ->appendChild($name)
            ->appendChild($emailWrapper)
            ->appendChild($subjectWrapper)
            ->appendChild($messageWrapper)
            ->appendChild($b);


        $repositorio = new MensajeRepository();

        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
            if (!$form->hasError()) {

                $mensaje = new Mensaje($firstName->getValue(), $lastName->getValue(), $subject->getValue(), $email->getValue(), $message->getValue());
                $repositorio->save($mensaje);
                $info = "Mensaje insertado correctamente:";
                $form->reset();
            } else {
                if ($firstName->hasError()) {
                    $firstName->setCssClass($firstName->getCssClass() . ' has-error');
                }
                if ($subject->hasError()) {
                    $subject->setCssClass($subject->getCssClass() . ' has-error');
                }
                if ($email->hasError()) {
                    $email->setCssClass($email->getCssClass() . ' has-error');
                }
            }
        }
        return $this->container->renderer->render($response, "contact.view.php", compact('title', 'info', 'form'));
    }
}
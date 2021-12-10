<?php

namespace ProyectoWeb\app\controllers;

use Psr\Container\ContainerInterface;
use ProyectoWeb\utils\Forms\FormElement;
use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\utils\Forms\PasswordElement;
use ProyectoWeb\utils\Forms\ButtonElement;
use ProyectoWeb\utils\Forms\LabelElement;
use ProyectoWeb\utils\Forms\EmailElement;
use ProyectoWeb\utils\Forms\custom\MyFormControl;
use ProyectoWeb\utils\Validator\NotEmptyValidator;
use ProyectoWeb\utils\Validator\PasswordMatchValidator;
use ProyectoWeb\utils\Validator\MinLowerCaseValidator;
use ProyectoWeb\utils\Validator\MinDigitValidator;
use ProyectoWeb\utils\Validator\MinLengthValidator;
use ProyectoWeb\entity\Usuario;
use ProyectoWeb\repository\UsuarioRepository;
use ProyectoWeb\security\BCryptPasswordGenerator;
use ProyectoWeb\exceptions\QueryException;
use ProyectoWeb\exceptions\NotFoundException;
use ProyectoWeb\core\App;

class UserController
{
    protected $container;
    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function login($request, $response, $args)
    {
        $title = 'Login';
        $info = "";
        if (!isset($_SESSION['username'])) {

            $repositorio = new UsuarioRepository(new BCryptPasswordGenerator());

            $nombreUsuario = new InputElement('text');
            $nombreUsuario
                ->setName('username')
                ->setId('username');
            $userWrapper = new MyFormControl($nombreUsuario, 'Nombre de usuari@', 'col-xs-12');

            $pass = new PasswordElement();
            $pass
                ->setName("password")
                ->setId("password");

            $passWrapper = new MyFormControl($pass, 'Contraseña', 'col-xs-12');

            //En este caso puede venir en el POST (formulario) o en el GET (enlace)
            $hrefReturnToUrl = '';
            if (isset($_GET['returnToUrl'])) {
                $hrefReturnToUrl = $_GET['returnToUrl'];
            } else  if (isset($_POST['returnToUrl'])) {
                $hrefReturnToUrl = $_POST['returnToUrl'];
            }
            $returnToUrl = new InputElement('hidden');
            $returnToUrl
                ->setName('returnToUrl')
                ->setDefaultValue($hrefReturnToUrl);

            $b = new ButtonElement('Login');
            $b->setCssClass('pull-right btn btn-lg sr-button');


            $form = new FormElement();
            $form
                ->appendChild($userWrapper)
                ->appendChild($passWrapper)
                ->appendChild($returnToUrl)
                ->appendChild($b);

            if ("POST" === $_SERVER["REQUEST_METHOD"]) {
                $form->validate();
                if (!$form->hasError()) {
                    try {
                        $usuario = $repositorio->findByUserNameAndPassword($nombreUsuario->getValue(), $pass->getValue());
                        $_SESSION['username'] = $nombreUsuario->getValue();
                        return $response->withRedirect($this->container->router->pathFor('home'), 303);
                    } catch (QueryException $qe) {
                        $form->addError($qe->getMessage());
                    } catch (NotFoundException $nfe) {
                        /************************ CUIDADO *****************/
                        /*
              Hay que tratar antes NotFoundException que la excepción general
              Exception, sino siempre entrará por esta última
              */
                        $form->addError("Credenciales incorrectas");
                    } catch (\Exception $err) {
                        $form->addError($err->getMessage());
                    }
                }
            }
        }
        return $this->container->renderer->render($response, "login.view.php", compact('title', 'info', 'form'));
    }

    public function register($request, $response, $args)
    {
        $title = 'Register';
        $info = "";
        $repositorio = new UsuarioRepository(new BCryptPasswordGenerator());

        $nombreUsuario = new InputElement('text');
        $nombreUsuario
            ->setName('username')
            ->setId('username')
            ->setValidator(new NotEmptyValidator('El nombre de usuari@ no puede estar vacío', true));

        $userWrapper = new MyFormControl($nombreUsuario, 'Nombre de usuari@', 'col-xs-12');

        $email = new EmailElement();
        $email
            ->setName('email')
            ->setId('email');

        $emailWrapper = new MyFormControl($email, 'Correo electrónico', 'col-xs-12');

        $pv = new NotEmptyValidator('La contraseña no puede estar vacía', true);
        $mlv = new MinLengthValidator(6, 'La contraseña debe tener al menos 6 caracteres', false);
        $mlcv =  new MinLowerCaseValidator(2, 'La contraseña debe tener al menos 2 letras minúsculas', false);
        $mdv =  new MinDigitValidator(2, 'La contraseña debe tener al menos 2 dígitos', false);

        $mlcv->setNextValidator($mdv);
        $mlv->setNextValidator($mlcv);
        $pv->setNextValidator($mlv);

        $pass = new PasswordElement();
        $pass
            ->setName("password")
            ->setId("password")
            ->setValidator($pv);

        $passWrapper = new MyFormControl($pass, 'Contraseña', 'col-xs-12');

        $repite = new PasswordElement();
        $repite
            ->setName("repite_password")
            ->setId("repite_password")
            ->setValidator(new PasswordMatchValidator($pass, 'Las contraseñas no coinciden', true));

        $repiteWrapper = new MyFormControl($repite, 'Repita la contraseña', 'col-xs-12');

        $b = new ButtonElement('Registro');
        $b->setCssClass('pull-right btn btn-lg sr-button');

        //En este caso vamos a hacer que siempre se pase por GET (enlace)
        $hrefReturnToUrl = '';
        if (isset($_GET['returnToUrl'])) {
            $hrefReturnToUrl = $_GET['returnToUrl'];
        }

        $form = new FormElement((!empty($hrefReturnToUrl) ? '?returnToUrl=' . $hrefReturnToUrl : ''));
        $form
            ->appendChild($userWrapper)
            ->appendChild($emailWrapper)
            ->appendChild($passWrapper)
            ->appendChild($repiteWrapper)
            ->appendChild($b);


        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
            if (!$form->hasError()) {
                try {
                    //Grabamos en la base de datos
                    $usuario = new Usuario($nombreUsuario->getValue(), $email->getValue(), $pass->getValue());
                    $repositorio->save($usuario);
                    $_SESSION['username'] = $nombreUsuario->getValue();
                    return $response->withRedirect($this->container->router->pathFor('home'), 303);
                } catch (QueryException $qe) {
                    $excepcion = $qe->getMessage();
                    if ((strpos($excepcion, '1062') !== false)) {
                        if ((strpos($excepcion, 'username') !== false)) {
                            $form->addError('Ya existe un usuario registrado con dicho nombre de usuario');
                        } else if ((strpos($excepcion, 'email') !== false)) {
                            $form->addError('Ya existe un usuario registrado con dicho correo electrónico');
                        } else {
                            $form->addError($qe->getMessage());
                        }
                    } else {
                        $form->addError($qe->getMessage());
                    }
                } catch (\Exception $err) {
                    $form->addError($err->getMessage());
                }
            }
        }

        return $this->container->renderer->render($response, "register.view.php", compact('title', 'info', 'form'));
    }

    public function logout($request, $response, $args)
    {
        session_unset();
        session_destroy();
        return $response->withRedirect($this->container->router->pathFor('home'), 303);
    }
}
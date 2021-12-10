<?php
    $title = "Login";
 
    use ProyectoWeb\utils\Forms\FormElement;
    use ProyectoWeb\utils\Forms\InputElement;
    use ProyectoWeb\utils\Forms\PasswordElement;
    use ProyectoWeb\utils\Forms\ButtonElement;
    use ProyectoWeb\utils\Forms\custom\MyFormControl;
    use ProyectoWeb\repository\UsuarioRepository;
    use ProyectoWeb\security\BCryptPasswordGenerator;
    use ProyectoWeb\exceptions\QueryException;
    use ProyectoWeb\exceptions\NotFoundException;

    
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
              if (!empty($hrefReturnToUrl)) {
                header('location: ' . $hrefReturnToUrl);
              } else {
                header('location: /');
              }
            }catch(QueryException $qe) {
                $form->addError($qe->getMessage());
            }catch(NotFoundException $nfe){
              /************************ CUIDADO *****************/
              /*
              Hay que tratar antes NotFoundException que la excepción general
              Exception, sino siempre entrará por esta última
              */
              $form->addError("Credenciales incorrectas");
            }catch(\Exception $err) {
              $form->addError($err->getMessage());
            }
          }
      }
    }
    include(__DIR__ . "/../views/login.view.php");
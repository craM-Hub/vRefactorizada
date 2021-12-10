<!-- Navigation Bar -->
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a  class="navbar-brand page-scroll" href="#page-top">
                <span>[PHOTO]</span>
            </a>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="menu">
            <ul class="nav navbar-nav">
                <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("index")? "active" : "");?> lien">
                  <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("index")? "#" : "/");?>"><i class="fa fa-home sr-icons"></i> Home</a>
                </li>
                <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("about")? "active" : "");?> lien">
                  <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("about")? "#" : "/about");?>"><i class="fa fa-bookmark sr-icons"></i> About</a>
                </li>
                <li class="<?= (ProyectoWeb\app\utils\Utils::existeOpcionMenuActivaEnArray(["blog", "single_post"])? "active" : "");?> lien">
                  <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("blog")? "#" : "/blog");?>"><i class="fa fa-file-text sr-icons"></i> Blog</a>
                </li>
                <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("contact")? "active" : "");?> lien">
                  <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("contact")? "#" : "/contact");?>"><i class="fa fa-phone-square sr-icons"></i> Contact</a>
                </li>
                <?php  if (!isset($_SESSION['username'])):?>
                    <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("login")? "active" : "");?> lien">
                        <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("login")? "#" : "/login");?>">
                            <i class="fa fa-user-secret sr-icons"></i> Login
                        </a>
                    </li>
                    <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("register")? "active" : "");?>">
                        <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("register")? "#" : "/register");?>">
                            <i class="fa fa-sign-in sr-icons"></i> Registro
                        </a>
                    </li>
                <?php else :?>
                    <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("galeria")? "active" : "");?> lien">
                    <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("galeria")? "#" : "/galeria");?>"><i class="fa fa-image sr-icons"></i> Galería</a>
                    </li>
                    <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("asociados")? "active" : "");?> lien">
                    <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("asociados")? "#" : "/asociados");?>"><i class="fa fa-hand-o-right sr-icons"></i> Asociados</a>
                    </li>
                    <li class="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("logout")? "active" : "");?>">
                    <a href="<?= (ProyectoWeb\app\utils\Utils::esOpcionMenuActiva("logout")? "#" : "/logout");?>">
                        <i class="fa fa-sign-out sr-icons"></i> <?=$_SESSION['username']?> - Salir
                    </a>
                </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>
<!-- End of Navigation Bar -->
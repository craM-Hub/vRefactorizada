<?php
    $title = "Home";
    use ProyectoWeb\entity\ImagenGaleria;
    use ProyectoWeb\entity\Asociado;
    use ProyectoWeb\repository\ImagenGaleriaRepository;
    use ProyectoWeb\repository\AsociadoRepository;
    
    $repositorio = new ImagenGaleriaRepository();
    $galeria = $repositorio->findAll();
    
    $repositorioAsociados = new AsociadoRepository();
    $asociados = $repositorioAsociados->findAll();



    include(__DIR__ . "/../views/index.view.php");

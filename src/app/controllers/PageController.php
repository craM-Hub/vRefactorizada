<?php

namespace ProyectoWeb\app\controllers;

use Psr\Container\ContainerInterface;
use ProyectoWeb\repository\AsociadoRepository;
use ProyectoWeb\repository\ImagenGaleriaRepository;

class PageController
{
    protected $container;
    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function home($request, $response, $args)
    {
        $title = "Home";
        $repositorio = new ImagenGaleriaRepository();
        $galeria = $repositorio->findAll();
        $repositorioAsociados = new AsociadoRepository();
        $asociados = $repositorioAsociados->findAll();

        return $this->container
            ->renderer
            ->render(
                $response,
                "index.view.php",
                compact('title', 'galeria', 'asociados')
            );
    }

    public function about($request, $response, $args)
    {
        $title = "About";
        return $this
            ->container
            ->renderer
            ->render($response, "about.view.php", compact('title'));
    }


    public function blog($request, $response, $args)
    {
        $title = "About";
        return $this
            ->container
            ->renderer
            ->render($response, "blog.view.php", compact('title'));
    }


    public function singlePost($request, $response, $args)
    {
        $title = "Single post";
        return $this
            ->container
            ->renderer
            ->render($response, "single_post.view.php", compact('title'));
    }
    public function login($request, $response, $args)
    {
        $title = "Login";
        return $this
            ->container
            ->renderer
            ->render($response, "login.view.php", compact('title'));
    }
    public function register($request, $response, $args)
    {
        $title = "Register";
        return $this
            ->container
            ->renderer
            ->render($response, "register.view.php", compact('title'));
    }
    public function asociados($request, $response, $args)
    {
        $title = "Asociados";
        return $this
            ->container
            ->renderer
            ->render($response, "asociados.view.php", compact('title'));
    }
    public function galeria($request, $response, $args)
    {
        $title = "Galeria";
        return $this
            ->container
            ->renderer
            ->render($response, "galeria.view.php", compact('title'));
    }
}
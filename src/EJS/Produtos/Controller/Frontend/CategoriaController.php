<?php

namespace EJS\Produtos\Controller\Frontend;

use Silex\Application;
use Silex\ControllerProviderInterface;

class CategoriaController implements ControllerProviderInterface{

    public function connect(Application $app)
    {
        $categoriaController = $app['controllers_factory'];

        //////////////////////////////////////////////////////////
        //Rota para a lista de categorias
        $app->get('/categorias', function() use($app){
            $categorias = $app['categoriaService']->listCategorias();
            return $app['twig']->render('listCategorias.twig',['categorias' => $categorias]);
        })->bind('categorias');

        //outras rotas aqui

        return $categoriaController;
    }
} 
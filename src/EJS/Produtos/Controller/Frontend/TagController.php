<?php

namespace EJS\Produtos\Controller\Frontend;

use Silex\Application;
use Silex\ControllerProviderInterface;

class TagController implements ControllerProviderInterface{

    public function connect(Application $app)
    {
        $tagController = $app['controllers_factory'];

        /////////////////////////////////////////////////////////////////////
        //Rota para a lista de tags
        $app->get('/tags', function() use($app){
            $tags = $app['tagService']->listTags();
            return $app['twig']->render('listTags.twig',['tags' => $tags]);
        })->bind('tags');

        //outras rotas aqui

        return $tagController;
    }
} 
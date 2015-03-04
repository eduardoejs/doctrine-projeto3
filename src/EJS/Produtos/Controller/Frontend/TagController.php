<?php

namespace EJS\Produtos\Controller\Frontend;

use EJS\Produtos\Entity\Tag;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class TagController implements ControllerProviderInterface{

    public function connect(Application $app)
    {
        $tagController = $app['controllers_factory'];

        /////////////////////////////////////////////////////////////////////
        //Rota para a lista de tags
        $tagController->get('/', function() use($app){
            $tags = $app['tagService']->listTags();
            return $app['twig']->render('listTags.twig',['tags' => $tags]);
        })->bind('tags');

        $tagController->get('/view/{id}', function($id) use($app){
            $tag = $app['tagService']->listTagById($id);
            return $app['twig']->render('viewTag.twig',['tag' => $tag]);
        })->bind('viewTag');

        $tagController->get('/alterar/{id}', function($id) use($app){
            $tag = $app['tagService']->listTagById($id);
            return $app['twig']->render('formTag.twig',['tag' => $tag]);
        })->bind('formTag');

        $tagController->post('/alterar', function(Request $request) use($app){
            $data = $request->request->all();
            $tag = new Tag();
            $tag->setNome($data['nome_tag']);
            $tag->setId($data['id']);

            $result = $app['tagService']->updateTag($data, $tag->getId());
            return $app['twig']->render('status_updateTag.twig', ['status' => $result, 'tag' => $tag]);
        })->bind('alterarTag');

        $tagController->get('/delete/{id}', function($id) use($app){
            if($app['tagService']->deleteTag($id)){
                return $app->redirect($app['url_generator']->generate('tags'));
            }else{
                $app->abort(500, "Erro ao excluir produto");
            }
        })->bind('excluirTag');


        return $tagController;
    }
} 
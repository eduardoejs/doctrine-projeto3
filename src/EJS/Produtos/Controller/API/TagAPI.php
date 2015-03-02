<?php

namespace EJS\Produtos\Controller\API;

use EJS\Produtos\Entity\Tag;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class TagAPI implements ControllerProviderInterface{

    private $tag;

    public function connect(Application $app)
    {
        $tagControllerAPI = $app['controllers_factory'];

        //retorna lista de tags
        $app->get('/t', function () use ($app) {
            $data = $app['tagService']->listTags();
            return $app->json($data);
        })->bind('API-ListTags');

        //retorna uma tag
        $app->get('/{id}', function ($id) use ($app) {
            $tag = new Tag();
            $data['nome_tag'] = $tag->getNome();

            $data = $app['tagService']->listTagById($id);

            return $app->json($data);
        })->bind('API-ListTagsID');

        //insert tag
        $app->post('/', function (Request $request) use ($app) {

            $data = $request->request->all();

            $tag = new Tag();
            $tag->setNome($data['nome_tag']);

            if ( $app['tagService']->insertTag($data)) {
                return $app->json([
                    'SUCCESS' => true
                ]);
            } else {
                return $app->json([
                    "SUCCESS" => false
                ]);
            }
        })->bind('API-InsertTags');

        //update tag
        $app->put('/{id}', function (Request $request, $id) use ($app) {
            $data = $request->request->all();
            $data['id'] =  $id;
            $data['nome_tag'] = $request->request->get('nome_tag');

            if ($app['tagService']->updateTag($data, $id)) {
                return $app->json([
                    "SUCCESS" => true
                ]);
            } else {
                return $app->json([
                    "SUCCESS" => false
                ]);
            }
        })->bind('API-UpdateTags');

        //delete tag
        $app->delete('/{id}', function ( $id) use ($app) {

            if ($app['tagService']->deleteTag($id)) {
                return $app->json([
                    "SUCCESS" => true
                ]);
            } else {
                return $app->json([
                    "SUCCESS" => false
                ]);
            }
        })->bind('API-DeleteTags');

        return $tagControllerAPI;
    }
} 
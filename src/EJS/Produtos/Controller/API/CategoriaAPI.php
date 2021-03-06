<?php

namespace EJS\Produtos\Controller\API;

use EJS\Produtos\Entity\Categoria;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoriaAPI implements ControllerProviderInterface{

    private $categoria;

    public function connect(Application $app)
    {
        $categoriaControllerAPI = $app['controllers_factory'];

        //retorna lista de categorias
        $categoriaControllerAPI->get('/', function () use ($app) {
            $data = $app['categoriaService']->listCategorias();
            return $app->json($data);
        })->bind('API-ListCategorias');

        //retorna uma categoria
        $categoriaControllerAPI->get('/{id}', function ($id) use ($app) {
            $data = $app['categoriaService']->listCategoriaById($id);
            return $app->json($data);
        })->bind('API-ListCategoriasID');

        //insert categoria
        $categoriaControllerAPI->post('/', function (Request $request) use ($app) {
            $data = $request->request->all();
            $result = $app['categoriaService']->insertCategoria($data);
            return $app->json($result);
        })->bind('API-InsertCategorias');

        //update categoria
        $categoriaControllerAPI->put('/{id}', function (Request $request, $id) use ($app) {
            $data = $request->request->all();
            $result = $app['categoriaService']->updateCategoria($data, $id);
            return $app->json($result);

        })->bind('API-UpdateCategorias');

        //delete categoria
        $categoriaControllerAPI->delete('/{id}', function ( $id) use ($app) {

            if ($app['categoriaService']->deleteCategoria($id)) {
                return $app->json([
                    "SUCCESS" => true
                ]);
            } else {
                return $app->json([
                    "SUCCESS" => false
                ]);
            }
        })->bind('API-DeleteCategorias');

        return $categoriaControllerAPI;
    }
} 
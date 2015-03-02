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
        $app->get('/', function () use ($app) {
            $data = $app['categoriaService']->listCategorias();
            return $app->json($data);
        })->bind('API-ListCategorias');

        //retorna uma categoria
        $app->get('/{id}', function ($id) use ($app) {
            $categoria = new Categoria();
            $data['nome_categoria'] = $categoria->getNomeCategoria();

            $data = $app['categoriaService']->listCategoriaById($id);

            return $app->json($data);
        })->bind('API-ListCategoriasID');

        //insert categoria
        $app->post('/', function (Request $request) use ($app) {

            $data = $request->request->all();

            $categoria = new Categoria();
            $categoria->setNomeCategoria($data['nome_categoria']);

            if ( $app['categoriaService']->insertCategoria($data)) {
                return $app->json([
                    'SUCCESS' => true
                ]);
            } else {
                return $app->json([
                    "SUCCESS" => false
                ]);
            }
        })->bind('API-InsertCategorias');

        //update categoria
        $app->put('/{id}', function (Request $request, $id) use ($app) {
            $data = $request->request->all();
            $data['id'] =  $id;
            $data['nome_categoria'] = $request->request->get('nome_categoria');

            if ($app['categoriaService']->updateCategoria($data, $id)) {
                return $app->json([
                    "SUCCESS" => true
                ]);
            } else {
                return $app->json([
                    "SUCCESS" => false
                ]);
            }

        })->bind('API-UpdateCategorias');

        //delete categoria
        $app->delete('/{id}', function ( $id) use ($app) {

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
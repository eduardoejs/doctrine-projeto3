<?php

namespace EJS\Produtos\Controller\Frontend;

use EJS\Produtos\Entity\Categoria;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoriaController implements ControllerProviderInterface{

    public function connect(Application $app)
    {
        $categoriaController = $app['controllers_factory'];

        //////////////////////////////////////////////////////////
        //Rota para a lista de categorias
        $categoriaController->get('/', function() use($app){
            $categorias = $app['categoriaService']->listCategorias();
            return $app['twig']->render('listCategorias.twig',['categorias' => $categorias]);
        })->bind('categorias');

        $categoriaController->get('/view/{id}', function($id) use($app){
            $categoria = $app['categoriaService']->listCategoriaById($id);
            return $app['twig']->render('viewCategoria.twig',['categoria' => $categoria]);
        })->bind('viewCategoria');

        $categoriaController->get('/alterar/{id}', function($id) use($app){
            $categoria = $app['categoriaService']->listCategoriaById($id);
            return $app['twig']->render('formCategoria.twig',['categoria' => $categoria]);
        })->bind('formCategoria');

        $categoriaController->post('/alterar', function(Request $request) use($app){
            $data = $request->request->all();
            $categoria = new Categoria();
            $categoria->setId($data['id']);
            $result = $app['categoriaService']->updateCategoria($data, $categoria->getId());
            return $app['twig']->render('status_updateCateg.twig', ['status' => $result, 'categoria' => $categoria]);
        })->bind('alterarCateg');

        $categoriaController->get('/delete/{id}', function($id) use($app){
            if($app['categoriaService']->deleteCategoria($id)){
                return $app->redirect($app['url_generator']->generate('categorias'));
            }else{
                $app->abort(500, "Erro ao excluir produto");
            }
        })->bind('excluirCateg');

        $categoriaController->get('/novo', function() use($app){
            return $app['twig']->render('formCategoriaInsert.twig',[]);
        })->bind('novaCateg');

        //Rota: após pegar dados do formulário insere no banco de dados
        $categoriaController->post('/inserir', function(Request $request) use($app){
            $data = $request->request->all();
            $result = $app['categoriaService']->insertCategoria($data);
            return $app['twig']->render('status_insertCateg.twig', ['msg' => $result]);
        })->bind('inserirCateg');

        return $categoriaController;
    }
} 
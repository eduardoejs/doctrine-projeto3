<?php

namespace EJS\Produtos\Controller\API;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/*
 * Controller para APIs Publicas de Produto
 */
class ProdutoAPI implements ControllerProviderInterface{

    private $produto;

    public function connect(Application $app){

        $produtoControllerAPI = $app['controllers_factory'];

        //API para listar todos os produtos
        $produtoControllerAPI->get('/', function() use($app){
            $dados = $app['produtoService']->listProdutos();
            return $app->json($dados);
        })->bind('API-ListProdutos');

        //API para listar 1 registro apenas
        $produtoControllerAPI->get('/{id}', function($id) use($app){
            $dados = $app['produtoService']->listProdutoById($id);
            if($dados){
                return $app->json($dados);
            }else{
                return $app->json(['ERRO' => 'Não foi possível exibir o registro']);
            }
        })->bind('API-ListProdutosID');

        //API para inserir novo registro
        $produtoControllerAPI->post('/', function(Request $request) use($app){
            $dados['nome'] = $request->get('nome');
            $dados['descricao'] = $request->get('descricao');
            $dados['valor'] = $request->get('valor');

            $dados['categoria_produto'] = $request->get('categoria');
            $dados['tags_produto'] = $request->get('tags');

            $result = $app['produtoService']->insertProduto($dados);
            return $app->json($result);
        })->bind('API-InsertProdutos');

        //API para alterar um registro
        $produtoControllerAPI->put('/{id}', function($id, Request $request) use($app){
            $dados['id'] =  $id;
            $dados['nome'] = $request->request->get('nome');
            $dados['descricao'] = $request->request->get('descricao');
            $dados['valor'] = $request->request->get('valor');

            $dados['categoria_produto'] = $request->request->get('categoria');
            $dados['tags_produto'] = $request->request->get('tags');

            $result = $app['produtoService']->alterarProduto($dados);
            return $app->json($result);
        })->bind('API-UpdateProdutos');

        //API para remover um registro
        $produtoControllerAPI->delete('/{id}', function($id) use($app){

            $dados = $app['produtoService']->listProdutoById($id);

            if($dados){
                if($app['produtoService']->deleteProduto($id)){
                    return $app->json(['SUCCESSO' => 'Registro excluído com sucesso']);
                }else{
                    return $app->json(['ERRO' => 'Não foi possível excluir o registro']);
                }
            }else{
                return $app->json(['ERRO' => 'Registro não encontrado']);
            }
        })->bind('API-DeleteProdutos');

        return $produtoControllerAPI;
    }

} 
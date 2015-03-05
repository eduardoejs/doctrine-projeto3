<?php

namespace EJS\Produtos\Controller\Frontend;

use EJS\Produtos\Entity\Categoria;
use EJS\Produtos\Entity\Produto;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


class ProdutoController implements ControllerProviderInterface {

    private $produto;

    public function connect(Application $app)
    {
        $produtoController = $app['controllers_factory'];

        //Rota: index(listagem de produtos)
        $produtoController->get('/', function() use ($app){
            //return $app['twig']->render('index.twig', []);
            $pagina = 1;
            $produtos = $app['produtoService']->paginacao(5, $pagina);
            return $app['twig']->render('conteudo.twig', ['produtos' => $produtos, 'data' => $produtos->count()]);
        })->bind('index');

        //Rota: listar produto por ID
        $produtoController->get('/view/{id}', function($id) use($app){
            $produto = new Produto();
            $data['nome'] = $produto->getNome();
            $data['descricao'] = $produto->getDescricao();
            $data['valor'] = $produto->getValor();

            $result = $app['produtoService']->listProdutoById($id);

            return $app['twig']->render('visualizar.twig', ['produto' => $result]);
        })->bind('visualizar');

        //Rota para o formulário de insert
        $produtoController->get('/novo', function() use($app){
            $categorias = $app['categoriaService']->listCategorias();
            $tags = $app['tagService']->listTags();
            return $app['twig']->render('novo.twig',['categorias' => $categorias, 'tags' => $tags]);
        })->bind('novo');

        //Rota: após pegar dados do formulário insere no banco de dados
        $produtoController->post('/inserir', function(Request $request) use($app){
            $data = $request->request->all();
            $produto = new Produto();
            $produto->setNome($data['nome']);
            $produto->setValor($data['valor']);

            $result = $app['produtoService']->insertProduto($data);
            return $app['twig']->render('status_insert.twig', ['msg' => $result]);
        })->bind('inserir');

        //Rota: mensagem de sucesso ao inserir novo registro [utilizar no metodo redirect->generate]
        $produtoController->get('/sucesso', function () use ($app) {
            return $app['twig']->render('sucesso.twig', []);
        })->bind("sucesso");

        //Rota: formulário de alteração
        $produtoController->get('/alterar/{id}', function($id) use($app){
            $produto = new Produto();
            $data['nome'] = $produto->getNome();
            $data['descricao'] = $produto->getDescricao();
            $data['valor'] = $produto->getValor();
            $result = $app['produtoService']->listProdutoById($id);

            $categorias = $app['categoriaService']->listCategorias();
            $tags = $app['tagService']->listTags();

            return $app['twig']->render('alterar.twig', ['produto' => $result, 'categorias' => $categorias, 'tags' => $tags]);
        })->bind('alterar');

        //Rota para alterar registro
        $produtoController->post('/alterar', function(Request $request) use($app){
            $data = $request->request->all();
            $produto = new Produto();
            $produto->setNome($data['nome']);
            $produto->setDescricao($data['descricao']);
            $produto->setValor($data['valor']);
            $produto->setId($data['id']);

            $result = $app['produtoService']->alterarProduto($data);
            return $app['twig']->render('status_update.twig', ['msg' => $result, 'produto' => $produto]);
        })->bind('update');

        //Rota para excluir registro
        $produtoController->get('/delete/{id}', function($id) use($app){
            if($app['produtoService']->deleteProduto($id)){
                return $app->redirect($app['url_generator']->generate('index'));
            }else{
                $app->abort(500, "Erro ao excluir produto");
            }
        })->bind('excluir');

        //rota para paginacao
        $produtoController->get('/produtosPaginado/{pagina}', function($pagina) use($app){
            $produtos = $app['produtoService']->paginacao(5, $pagina);
            return $app['twig']->render('conteudo.twig', ['produtos' => $produtos, 'data' => $produtos->count()]);
        })->bind('listar_produtos_paginado');

        //rota para pesquisar um produto
        $produtoController->post('/pesquisar', function(Request $request) use($app){

            $data = $request->request->all();

            $produto = new Produto();
            $produto->setNome($data['nome']);

            $result = $app['produtoService']->pesquisarProduto($produto->getNome());

            if(empty($result)){
                return $app['twig']->render('404produto.twig', [
                    'mensagem' => 'Nenhum produto encontrado!',
                ]);
            }
            return $app['twig']->render('pesquisa_produtos.twig', ['produtos' => $result]);
        })->bind('pesquisar');

        return $produtoController;
    }
} 
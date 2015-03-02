<?php

namespace EJS\Produtos\Service;

use Doctrine\ORM\EntityManager;
use EJS\Produtos\Entity\Categoria;
use EJS\Produtos\Entity\Produto as ProdutoEntity;

class ProdutoService {

    private $em;

    function __construct($em) {
        $this->em = $em;
    }

    public function listProdutos()
    {
        $repository = $this->em->getRepository("EJS\Produtos\Entity\Produto");
        //$result = $repository->findAll();
        $result = $repository->getProdutosOrdenados();

        $produtos = array();
        foreach($result as $produto)
        {
            $p = array();
            $p['id'] = $produto->getId();
            $p['nome'] = $produto->getNome();
            $p['descricao'] = $produto->getDescricao();
            $p['valor'] = $produto->getValor();

            $produtos[] = $p;
        }
        return $produtos;
    }

    public function listProdutoById($id){
        $repository = $this->em->getRepository("EJS\Produtos\Entity\Produto");
        $result = $repository->find($id);

        if($result != null)
        {
            $produto = array();
            $produto['id'] = $result->getId();
            $produto['nome'] = $result->getNome();
            $produto['descricao'] = $result->getDescricao();
            $produto['valor'] = $result->getValor();
            return $produto;
        }
        else{
            return false;
        }
    }

    public function insertProduto($data){

        $produtoEntity = new ProdutoEntity();
        $produtoEntity->setNome($data['nome']);
        $produtoEntity->setDescricao($data['descricao']);
        $produtoEntity->setValor($data['valor']);

        if(!isset($data['categoria_produto']))
            return ["STATUS" => "Você deve informar uma categoria para o produto"];

        if(!isset($data['tags_produto']))
            return ["STATUS" => "Erro: Você deve informar as TAGs"];

        if(is_numeric($data['categoria_produto'])){
            $categoria = $this->em->getRepository("EJS\Produtos\Entity\Categoria")->findOneBy(['id' => $data['categoria_produto']]);
            $produtoEntity->setCategoria($categoria);
        }

        if(is_array($data['tags_produto'])){
            if(count($data['tags_produto'])){
                foreach($data['tags_produto'] as $tag){
                    $tag = $this->em->getReference("EJS\Produtos\Entity\Tag", $tag);
                    $produtoEntity->addTags($tag);
                }
            }
        }else{
            $tags = explode(',', $data['tags_produto']);
            if(count($data['tags_produto'])){
                foreach($tags as $tag){
                    $tag = $this->em->getReference("EJS\Produtos\Entity\Tag", $tag);
                    $produtoEntity->addTags($tag);
                }
            }
        }

        if(empty($data['nome']) or empty($data['descricao']) or empty($data['valor'])){
            return ["STATUS" => "Erro: Você deve informar todos os valores"];
        }elseif(!is_numeric($data['valor'])){
            return ["STATUS" => "O formato do campo Valor está incorreto. (Não use vírgula)"];
        }
        else{
            $this->em->persist($produtoEntity);
            $this->em->flush();
            return ["STATUS" => "Registro cadastrado com sucesso"];
        }
    }

    public function alterarProduto($data){

        $produto = new ProdutoEntity();
        $produto = $this->em->getReference("EJS\Produtos\Entity\Produto", $data['id']);

        $produto->setId($data['id']);
        $produto->setNome($data['nome']);
        $produto->setDescricao($data['descricao']);
        $produto->setValor($data['valor']);

        if(!isset($data['categoria_produto']))
            return ["STATUS" => "Você deve informar uma categoria para o produto"];

        if(!isset($data['tags_produto']))
            return ["STATUS" => "Erro: Você deve informar as TAGs"];

        if(is_numeric($data['categoria_produto'])){
            $categoria = $this->em->getRepository("EJS\Produtos\Entity\Categoria")->findOneBy(['id' => $data['categoria_produto']]);
            $produto->setCategoria($categoria);

        }

        if(is_array($data['tags_produto'])){
            if(count($data['tags_produto'])){
                foreach($data['tags_produto'] as $tag){
                    $tag = $this->em->getReference("EJS\Produtos\Entity\Tag", $tag);
                    $produto->addTags($tag);
                }
            }
        }else{
            $tags = explode(',', $data['tags_produto']);
            if(count($data['tags_produto'])){
                foreach($tags as $tag){
                    $tag = $this->em->getReference("EJS\Produtos\Entity\Tag", $tag);
                    $produto->addTags($tag);
                }
            }
        }

        if(empty($data['nome']) or empty($data['descricao']) or empty($data['valor'])){
            return ["STATUS" => "Erro: Você deve informar todos os valores"];
        }elseif(!is_numeric($data['valor'])){
            return ["STATUS" => "O formato do campo Valor está incorreto. (Não use vírgula)"];
        }
        else{
            //$this->em->persist($produto);
            $this->em->flush();
            return ["STATUS" => "Registro alterado com sucesso"];
        }
    }

    public function deleteProduto($id)
    {
        $produto = $this->em->getReference("EJS\Produtos\Entity\Produto", $id);
        $this->em->remove($produto);
        $this->em->flush();
        return true;
    }

    public function pesquisarProduto($nome){
        return $this->em->getRepository("EJS\Produtos\Entity\Produto")->pesquisarProdutos($nome);
    }

    public function paginacao($qtdePaginas, $paginaAtual){
        return $this->em->getRepository("EJS\Produtos\Entity\Produto")->paginarRegistros($qtdePaginas, $paginaAtual);
    }
}
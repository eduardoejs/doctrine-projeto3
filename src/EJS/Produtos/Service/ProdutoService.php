<?php

namespace EJS\Produtos\Service;

use Doctrine\ORM\EntityManager;
use EJS\Produtos\Entity\Categoria;
use EJS\Produtos\Entity\Produto as ProdutoEntity;
use EJS\Produtos\Entity\Produto;
use EJS\Produtos\Serializer\ProdutoSerializer;
use EJS\Produtos\Validator\ProdutoValidator;
use Psr\Log\InvalidArgumentException;

class ProdutoService {

    private $em;

    function __construct($em) {
        $this->em = $em;
    }

    public function listProdutos()
    {
        $repository = $this->em->getRepository("EJS\Produtos\Entity\Produto");
        $result = $repository->findAll();
        //$result = $repository->getProdutosOrdenados();

        $produtos = array();
        foreach($result as $produto)
        {
            $serializer = new ProdutoSerializer($produto);
            $produtos[] = $serializer->serialize();
        }
        return $produtos;
    }

    public function listProdutoById($id){
        $repository = $this->em->getRepository("EJS\Produtos\Entity\Produto");
        $result = $repository->find($id);

        if($result != null)
        {

            $serializer = new ProdutoSerializer($result);
            $produto = $serializer->serialize();

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

        $categoria = $this->em->getRepository("EJS\Produtos\Entity\Categoria")->findOneBy(['id' => $data['categoria_produto']]);
        $produtoEntity->setCategoria($categoria);

        if(isset($data['tags_produto'])){
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
        }

        $produtoEntity->setFile($data['file']);

        $validador = new ProdutoValidator($produtoEntity);
        $erros = $validador->validate();

        if(is_array($erros)){
            return ["ERROS" => $erros];
        }else{
            $this->em->persist($produtoEntity);
            $this->em->flush();
            return ["OK" => "Registro cadastrado com sucesso"];
        }

    }

    public function alterarProduto($data){

        $produto = new ProdutoEntity();
        $produto = $this->em->getReference("EJS\Produtos\Entity\Produto", $data['id']);

        $produto->setId($data['id']);
        $produto->setNome($data['nome']);
        $produto->setDescricao($data['descricao']);
        $produto->setValor($data['valor']);

        if(is_numeric($data['categoria_produto'])){
            $categoria = $this->em->getRepository("EJS\Produtos\Entity\Categoria")->findOneBy(['id' => $data['categoria_produto']]);
            $produto->setCategoria($categoria);

        }

        if(isset($data['tags_produto'])){
            if(is_array($data['tags_produto'])){
                if(count($data['tags_produto'])){
                    $produto->getTags()->clear();
                    foreach($data['tags_produto'] as $tag){
                        $tag = $this->em->getReference("EJS\Produtos\Entity\Tag", $tag);
                        $produto->addTags($tag);
                    }
                }
            }else{
                $tags = explode(',', $data['tags_produto']);
                if(count($data['tags_produto'])){
                    $produto->getTags()->clear();
                    foreach($tags as $tag){
                        $tag = $this->em->getReference("EJS\Produtos\Entity\Tag", $tag);
                        $produto->addTags($tag);
                    }
                }
            }
        }

        if($data['file'] != null){
            $produtoTemp = $this->em->getRepository("EJS\Produtos\Entity\Produto")->find($produto->getId());
            self::removeImage($produtoTemp);
            $produto->setFile($data['file']);
        }

        $validador = new ProdutoValidator($produto);
        $erros = $validador->validate();

        if(is_array($erros)){
            return ["ERROS" => $erros];
        }else{
            $this->em->persist($produto);
            $this->em->flush();
            return ["OK" => "Registro alterado com sucesso"];
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

    static public function uploadImage(Produto $produto){

        if(null === $produto->getFile()){
            return;
        }

        if(!in_array($produto->getFile()->getClientOriginalExtension(), $produto->getUploadAcceptedTypes())){
            throw new InvalidArgumentException("Tipo de arquivo inválido");
        }

        $filename = sha1($produto->getFile()->getClientOriginalName().date('Y-m-d H:i:s')).'.'.$produto->getFile()->getClientOriginalExtension();

        $produto->getFile()->move($produto->getUploadRootDir(), $filename);

        return $filename;

    }

    static public function removeImage(Produto $produto){

        if(null === $produto->getPath()){
            return;
        }

        if(file_exists($produto->getAbsolutePath()))
            unlink($produto->getAbsolutePath());

        return true;
    }
}
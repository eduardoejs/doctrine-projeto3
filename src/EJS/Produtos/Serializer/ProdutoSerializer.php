<?php
namespace EJS\Produtos\Serializer;

use EJS\Produtos\Entity\Categoria;
use EJS\Produtos\Entity\Produto;

class ProdutoSerializer {

    private $produto;
    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    public function serialize()
    {
        $produto['id'] = $this->produto->getId();
        $produto["nome"] = $this->produto->getNome();
        $produto["descricao"] = $this->produto->getDescricao();
        $produto["valor"] = $this->produto->getValor();

        if(!is_null($this->produto->getCategoria())) {
            $categoriaSerializer = new CategoriaSerializer($this->produto->getCategoria());
            $produto['categoria'] = $categoriaSerializer->serialize();
        }else{
            $produto['categoria'] = null;
        }

        $tags = $this->produto->getTags();
        foreach($tags as $tag){
            $tagSerialize = new TagSerializer($tag);
            $produto["tags"][] = $tagSerialize->serialize();
        }

        return $produto;
    }
}
<?php
namespace EJS\Produtos\Serializer;

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

        return $produto;
    }
}
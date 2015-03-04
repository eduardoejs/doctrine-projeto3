<?php


namespace EJS\Produtos\Serializer;

use EJS\Produtos\Entity\Categoria;

class CategoriaSerializer {

    private $categoria;
    public function __construct(Categoria $categoria)
    {
        $this->categoria = $categoria;
    }

    public function serialize()
    {
        $categoria['id'] = $this->categoria->getId();
        $categoria["nome_categoria"] = $this->categoria->getNomeCategoria();


        return $categoria;
    }

}
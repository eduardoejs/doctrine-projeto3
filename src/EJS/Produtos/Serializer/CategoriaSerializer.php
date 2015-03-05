<?php


namespace EJS\Produtos\Serializer;

use EJS\Produtos\Entity\Categoria;

class CategoriaSerializer {

    private $categoria;
    public function __construct(Categoria $categoria)
    {
        if(isset($categoria)){
            $this->categoria = $categoria;
        }
        else{
            $this->categoria = null;
        }
    }

    public function serialize()
    {
        $categoria = null;
        if(!is_null($this->categoria)){
            $categoria['id'] = $this->categoria->getId();
            $categoria["nome_categoria"] = $this->categoria->getNomeCategoria();
        }

        return $categoria;
    }

}
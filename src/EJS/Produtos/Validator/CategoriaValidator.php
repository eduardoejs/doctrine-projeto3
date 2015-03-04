<?php

namespace EJS\Produtos\Validator;


use EJS\Produtos\Entity\Categoria;

class CategoriaValidator {

    private $categoria;

    public function __construct(Categoria $categoria){
        $this->categoria = $categoria;
    }

    public function validate(){

        $erros = array();


        if($this->categoria->getNomeCategoria() == "")
            $erros[] = ["Campo Nome" => "O Nome da Categoria deve ser informado"];


        if(count($erros))
            return $erros;
        //else
          //  return $this->categoria;

    }
}
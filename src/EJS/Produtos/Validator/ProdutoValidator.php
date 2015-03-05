<?php

namespace EJS\Produtos\Validator;


use EJS\Produtos\Entity\Produto;

class ProdutoValidator {

    private $produto;

    public function __construct(Produto $produto){
        $this->produto = $produto;
    }

    public function validate(){

        $erros = array();
        //retornar um objeto em array

        if($this->produto->getNome() == "")
            $erros[] = ["Campo" => "O Nome do produto deve ser informado"];

        if($this->produto->getDescricao() == "")
            $erros[] = ["Campo" => "O Descricao do produto deve ser informado"];

        if(!is_numeric($this->produto->getValor()))
            $erros[] = ["Campo" => "O Valor do produto não está em um formato válido"];

        if(!is_numeric($this->produto->getCategoria()->getId()))
            $erros[] = ["Campo" => "A Categoria do produto não foi informada"];


        if(count($this->produto->getTags()->toArray())==0 or is_null($this->produto->getTags())){
            $erros[] = ["Campo" => "Nenhuma Tag foi informada"];

        }

        if(count($erros))
            return $erros;
        else
            return $this->produto;

    }
}
<?php

namespace EJS\Produtos\Validator;


use EJS\Produtos\Entity\Tag;

class TagValidator {

    private $tag;

    public function __construct(Tag $tag){
        $this->tag = $tag;
    }

    public function validate()
    {

        $erros = array();


        if ($this->tag->getNome() == "")
            $erros[] = ["Campo Nome" => "O Nome da Tag deve ser informado"];


        if (count($erros))
            return $erros;
        //else
        //  return $this->categoria;
    }
}
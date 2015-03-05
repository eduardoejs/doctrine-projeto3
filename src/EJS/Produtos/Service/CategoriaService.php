<?php

namespace EJS\Produtos\Service;

use Doctrine\ORM\EntityManager;
use EJS\Produtos\Entity\Categoria as CategoriaProdutos;
use EJS\Produtos\Serializer\CategoriaSerializer;
use EJS\Produtos\Validator\CategoriaValidator;

class CategoriaService {

    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function listCategorias(){
        $result = $this->em->getRepository('EJS\Produtos\Entity\Categoria')->findAll();

        $categorias = array();
        foreach($result as $categoria)
        {
            $serializer = new CategoriaSerializer($categoria);
            $categorias[] = $serializer->serialize();
        }
        return $categorias;
    }

    public function listCategoriaById($id){
        $result = $this->em->find('EJS\Produtos\Entity\Categoria', $id);

        if($result != null)
        {
            $categoria = array();

            $serializer = new CategoriaSerializer($result);
            $categoria = $serializer->serialize();

            return $categoria;
        }
        else{
            return false;
        }
    }

    public function insertCategoria($data){
        $categoria = new CategoriaProdutos();
        $categoria->setNomeCategoria($data['nome_categoria']);

        $validador = new CategoriaValidator($categoria);
        $erros = $validador->validate();

        if(is_array($erros)){
            return ["ERROS" => $erros];
        }else{
            $this->em->persist($categoria);
            $this->em->flush();
            return ["OK" => "Registro cadastrado com sucesso"];
        }
    }

    public function updateCategoria($data, $id){

        $categoria = $this->em->getReference('EJS\Produtos\Entity\Categoria', $id);
        $categoria->setNomeCategoria($data['nome_categoria']);

        $validador = new CategoriaValidator($categoria);
        $erros = $validador->validate();

        if(is_array($erros)){
            return ["ERROS" => $erros];
        }else{
            $this->em->persist($categoria);
            $this->em->flush();
            return ["OK" => "Registro alterado com sucesso"];
        }
    }

    public function deleteCategoria($id){
        $categoria = $this->em->getReference('EJS\Produtos\Entity\Categoria', $id);

        $this->em->remove($categoria);
        $this->em->flush();
        return $categoria;
    }
} 
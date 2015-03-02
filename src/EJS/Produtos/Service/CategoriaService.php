<?php

namespace EJS\Produtos\Service;

use Doctrine\ORM\EntityManager;
use EJS\Produtos\Entity\Categoria as CategoriaProdutos;

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
            $c = array();
            $c['id'] = $categoria->getId();
            $c['nome_categoria'] = $categoria->getNomeCategoria();

            $categorias[] = $c;
        }
        return $categorias;
    }

    public function listCategoriaById($id){
        $result = $this->em->find('EJS\Produtos\Entity\Categoria', $id);

        if($result != null)
        {
            $categoria = array();
            $categoria['id'] = $result->getId();
            $categoria['nome_categoria'] = $result->getNomeCategoria();

            return $categoria;
        }
        else{
            return false;
        }
    }

    public function insertCategoria($data){
        $categoria = new CategoriaProdutos();
        $categoria->setNomeCategoria($data['nome_categoria']);

        $this->em->persist($categoria);
        $this->em->flush();
        return $categoria;
    }

    public function updateCategoria($data, $id){
        $categoria = $this->em->getReference('EJS\Produtos\Entity\Categoria', $id);
        $categoria->setNomeCategoria($data['nome_categoria']);

        $this->em->persist($categoria);
        $this->em->flush();
        return $categoria;
    }

    public function deleteCategoria($id){
        $categoria = $this->em->getReference('EJS\Produtos\Entity\Categoria', $id);

        $this->em->remove($categoria);
        $this->em->flush();
        return $categoria;
    }
} 
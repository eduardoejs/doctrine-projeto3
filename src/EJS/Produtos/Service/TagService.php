<?php

namespace EJS\Produtos\Service;

use Doctrine\ORM\EntityManager;
use EJS\Produtos\Entity\Tag;
use EJS\Produtos\Serializer\TagSerializer;
use EJS\Produtos\Validator\TagValidator;

class TagService {

    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function listTags(){
        $result = $this->em->getRepository('EJS\Produtos\Entity\Tag')->findAll();

        $tags = array();
        foreach($result as $tag)
        {
            $serializer = new TagSerializer($tag);
            $tags[] = $serializer->serialize();
        }
        return $tags;
    }

    public function listTagById($id){
        $result = $this->em->find('EJS\Produtos\Entity\Tag', $id);

        if($result != null)
        {
            $tag = array();
            $serializer = new TagSerializer($result);
            $tag = $serializer->serialize();

            return $tag;
        }
        else{
            return false;
        }
    }

    public function insertTag($data){
        $tag = new Tag();
        $tag->setNome($data['nome_tag']);

        $validador = new TagValidator($tag);
        $erros = $validador->validate();

        if(is_array($erros)){
            return ["ERROS" => $erros];
        }else{
            $this->em->persist($tag);
            $this->em->flush();
            return ["OK" => "Registro cadastrado com sucesso"];
        }

    }

    public function updateTag($data, $id){

        $tag = $this->em->getReference('EJS\Produtos\Entity\Tag', $id);
        $tag->setNome($data['nome_tag']);

        $validador = new TagValidator($tag);
        $erros = $validador->validate();

        if(is_array($erros)){
            return ["ERROS" => $erros];
        }else{
            $this->em->persist($tag);
            $this->em->flush();
            return ["OK" => "Registro alterado com sucesso"];
        }
    }

    public function deleteTag($id){
        $tag = $this->em->getReference('EJS\Produtos\Entity\Tag', $id);

        $this->em->remove($tag);
        $this->em->flush();
        return $tag;
    }
} 
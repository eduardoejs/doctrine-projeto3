<?php

namespace EJS\Produtos\Service;

use Doctrine\ORM\EntityManager;
use EJS\Produtos\Entity\Tag;

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
            $t = array();
            $t['id'] = $tag->getId();
            $t['nome_tag'] = $tag->getNome();

            $tags[] = $t;
        }
        return $tags;
    }

    public function listTagById($id){
        $result = $this->em->find('EJS\Produtos\Entity\Tag', $id);

        if($result != null)
        {
            $tag = array();
            $tag['id'] = $result->getId();
            $tag['nome_tag'] = $result->getNome();

            return $tag;
        }
        else{
            return false;
        }
    }

    public function insertTag($data){
        $tag = new Tag();
        $tag->setNome($data['nome_tag']);

        $this->em->persist($tag);
        $this->em->flush();
        return $tag;
    }

    public function updateTag($data, $id){
        $tag = $this->em->getReference('EJS\Produtos\Entity\Tag', $id);
        $tag->setNome($data['nome_tag']);

        $this->em->persist($tag);
        $this->em->flush();
        return $tag;
    }

    public function deleteTag($id){
        $tag = $this->em->getReference('EJS\Produtos\Entity\Tag', $id);

        $this->em->remove($tag);
        $this->em->flush();
        return $tag;
    }
} 
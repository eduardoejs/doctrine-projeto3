<?php
namespace EJS\Produtos\Serializer;

use EJS\Produtos\Entity\Tag;

class TagSerializer {

    private $tag;
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function serialize()
    {
        $tag['id'] = $this->tag->getId();
        $tag["nome_tag"] = $this->tag->getNome();


        return $tag;
    }
}
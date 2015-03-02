<?php

namespace EJS\Produtos\Entity;

use Doctrine\ORM\Mapping as ORM;

/** *
 * @ORM\Entity(repositoryClass="EJS\Produtos\Entity\CategoriaRepository")
 * @ORM\Table(name="categorias")
 */
class Categoria {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomeCategoria;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $nomeCategoria
     */
    public function setNomeCategoria($nomeCategoria)
    {
        $this->nomeCategoria = $nomeCategoria;
    }

    /**
     * @return mixed
     */
    public function getNomeCategoria()
    {
        return $this->nomeCategoria;
    }

} 
<?php

namespace EJS\Produtos\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** *
 * @ORM\Entity(repositoryClass="EJS\Produtos\Entity\ProdutoRepository")
 * @ORM\Table(name="produtos")
 */
class Produto {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descricao;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valor;

    /**
     *  @ORM\ManyToOne(targetEntity="EJS\Produtos\Entity\Categoria")
     *  @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="EJS\Produtos\Entity\Tag")
     * @ORM\JoinTable(name="produtos_tags",
     *      joinColumns={@ORM\JoinColumn(name="produto_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $tags;

    function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $tags
     */
    public function addTags($tags)
    {
        $this->tags->add($tags);
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }


} 
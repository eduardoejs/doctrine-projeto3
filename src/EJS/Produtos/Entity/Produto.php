<?php

namespace EJS\Produtos\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EJS\Produtos\Service\ProdutoService;

/**
 * @ORM\Entity(repositoryClass="EJS\Produtos\Entity\ProdutoRepository")
 * @ORM\Table(name="produtos")
 * @ORM\HasLifecycleCallbacks
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    private $file;

    function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createPath(){
        if(!is_null($this->file)){
            $this->path = ProdutoService::uploadImage($this);
        }
    }

    /**
     * @ORM\PreRemove
     */
    public function removePath(){
        return ProdutoService::removeImage($this);
    }

    public function getPath(){
        return $this->path;
    }

    public function setFile(UploadedFile $file){

        $this->file = $file;

        if(!null !== $this->path){
            //imagem não existe, então coloco uma imagem padrão
            $this->path = $this->getUploadRootDir().'no-image.jpg';
        }else{
            //imagem já existe, vamos alterar, por isso apaga o caminho
            $this->path = null;
        }
        $this->path = null;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile(){
        return $this->file;
    }

    public function getAbsolutePath(){
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath(){
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir(){
        return __DIR__.'/../../../../public/'.$this->getUploadDir();
    }

    public function getUploadDir(){
        return 'uploads/images';
    }

    public function getUploadAcceptedTypes(){
        return array('jpg','jpeg','png', 'gif');
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
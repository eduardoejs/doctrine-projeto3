<?php

namespace EJS\Produtos\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CategoriaRepository extends EntityRepository {

    public function paginarRegistros($qtdePaginas, $paginaAtual){
        $dql = "SELECT c FROM EJS\Produtos\Entity\Categoria c";
        $resultQuery = $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setFirstResult($qtdePaginas * ($paginaAtual-1))
            ->setMaxResults($qtdePaginas);
        $pagina = new Paginator($resultQuery, $fetchJoinCollection = true);

        return $pagina;
    }

} 
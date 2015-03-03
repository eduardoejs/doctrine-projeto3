<?php

require_once __DIR__ . '/../config/bootstrap.php';

use EJS\Produtos\Controller\Frontend\ProdutoController;
use EJS\Produtos\Controller\Frontend\CategoriaController;
use EJS\Produtos\Controller\Frontend\TagController;
use EJS\Produtos\Controller\API\ProdutoAPI;
use EJS\Produtos\Controller\API\CategoriaAPI;
use EJS\Produtos\Controller\API\TagAPI;
use EJS\Produtos\Service\TagService;
use EJS\Produtos\Service\CategoriaService;
use EJS\Produtos\Service\ProdutoService;

//**********************************
//******* Container de Serviços
//**********************************
$app['produtoService'] = function() use($em){
    $produtoService = new ProdutoService($em);
    return $produtoService;
};

$app['categoriaService'] = function() use($em){
    $categoriaService = new CategoriaService($em);
    return $categoriaService;
};

$app['tagService'] = function() use($em){
    $tagService = new TagService($em);
    return $tagService;
};

//Frontend
$app->mount('/', new ProdutoController());
$app->mount('/categorias', new CategoriaController());
$app->mount('/tags', new TagController());

//APIs REST
$app->mount('/api/produto', new ProdutoAPI());
$app->mount('/api/categoria', new CategoriaAPI());
$app->mount('/api/tag', new TagAPI());

$app->run();
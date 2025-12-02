<?php
header("Content-Type: Application/JSON");

require_once "config/index.php";
require_once "lib/Auth.php";
require_once "lib/Rotas.php";

$rotas = new Rotas();
$rotas->get('/categorias','CategoriaController@listar');
$rotas->get('/categorias/{id}','CategoriaController@detalhar');
$rotas->post('/categorias','CategoriaController@criar');
$rotas->put('/categorias/{id}','CategoriaController@editar');
$rotas->delete('/categorias/{id}','CategoriaController@remover');

$rotas->post('/login', 'LoginController@login', false);

echo json_encode($rotas->executar());
?>
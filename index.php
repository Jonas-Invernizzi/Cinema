<?php
header("Content-Type: Application/JSON");

require_once "config/index.php";
require_once "models/Auth.php";
require_once "models/Rotas.php";

$rotas = new Rotas();
$rotas->get('/comprar','PoltronaController@comprar');
$rotas->get('/poltronas','PoltronaController@listar', false);
$rotas->post('/login', 'LoginController@login', false);

echo json_encode($rotas->executar());
?>
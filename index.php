<?php
ob_start();
header("Content-Type: Application/JSON");

require_once "config/index.php";
require_once "models/Auth.php";
require_once "models/Rotas.php";

$rotas = new Rotas();
$rotas->post('/comprar','PoltronaController@comprar');
$rotas->get('/poltronas','PoltronaController@listar');
$rotas->post('/login', 'LoginController@login',false);

echo json_encode($rotas->executar());
?>
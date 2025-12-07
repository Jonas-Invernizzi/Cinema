<?php
$key = "PROGRAMACAO_WEB_2";

if (file_exists("vendor/autoload.php")) {
    require_once "vendor/autoload.php";
}

require_once "lib/class.Banco.php";

try {
    $pdo = Banco::getConexao();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro de conexão com o banco"]);
    exit;
}
?>
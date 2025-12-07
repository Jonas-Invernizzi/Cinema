<?php
require_once "lib/class.PoltronaDAO.php";

class PoltronaController {

    function listar(){
        global $pdo;
        $dao = new PoltronaDAO($pdo);
        return $dao->listar();
    }

    function comprar() {
        global $pdo;
        
        $dados = json_decode(file_get_contents('php://input'));
        
        if ($dados === null || !isset($dados->id_poltrona) || !isset($dados->id_usuario)) {
            http_response_code(400);
            return ["error" => "Dados inválidos."];
        }

        $dao = new PoltronaDAO($pdo);
        
        if ($dao->comprar($dados->id_poltrona, $dados->id_usuario)) {
            return ["mensagem" => "Compra realizada!"];
        }
        
        http_response_code(409);
        return ["error" => "Poltrona indisponível."];
    }
}
?>
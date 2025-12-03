<?php

require_once "models/class.Poltrona.php";
require_once "lib/class.PoltronaDAO.php";

class PoltronaController {
    private $dao;

    function __construct() { 
        $this->dao = new PoltronaDAO(); 
    }

    function listar(){
        return $this->dao->buscarTodos();
    }

    function comprar($id) {
        $dados = json_decode(file_get_contents('php://input'));
        
        if ($dados === null) {
            throw new Exception("Dados de entrada JSON inválidos.");
        }

        $p = new Poltrona();
        $p->setUsuarioId(isset($dados->usuario_id) && $dados->usuario_id ? $dados->usuario_id : null);
        $p->setStatus($dados->status);
        
        return $this->dao->adquirir($id, $p);
    }
}

?>
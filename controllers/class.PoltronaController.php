<?php

require_once "models/class.Poltrona.php";
require_once "lib/class.PoltronaDAO.php";
require_once "interface.Controller.php";

class PoltronaController implements Controller {
    private $dao;

    function __construct() { 
        $this->dao = new PoltronaDAO(); 
    }

    function getTodos(){
        return $this->dao->buscarTodos();
    }

    function getPorId($id) {
        return $this->dao->buscarPorId($id);
    }
    
    function criar() {
        $p = new Poltrona();
        
        $p->setFileira($_POST['fileira']);
        $p->setColuna($_POST['coluna']);
        $p->setUsuarioId(isset($_POST['usuario_id']) && $_POST['usuario_id'] !== '' ? $_POST['usuario_id'] : null); 
        $p->setStatus(isset($_POST['status']) ? $_POST['status'] : "Disponível");

        return $this->dao->inserir($p);
    }

    function editar($id) {
        $dados = json_decode(file_get_contents('php://input'));
        
        if ($dados === null) {
            throw new Exception("Dados de entrada JSON inválidos.");
        }

        $p = new Poltrona();
        
        $p->setFileira($dados->fileira);
        $p->setColuna($dados->coluna);
        $p->setUsuarioId(isset($dados->usuario_id) && $dados->usuario_id ? $dados->usuario_id : null);
        $p->setStatus($dados->status);
        
        return $this->dao->editar($id, $p);
    }

    function apagar($id) {
        return $this->dao->apagar($id);
    }
}

?>
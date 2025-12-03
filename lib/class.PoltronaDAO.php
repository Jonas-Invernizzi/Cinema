<?php
require_once "class.Banco.php";
require_once "models/class.Poltrona.php";
require_once "models/class.Usuario.php";

class PoltronaDAO {
    private $pdo;

    function __construct() { 
        $this->pdo = Banco::getConexao(); 
    }

    function buscarTodos() {
        $sql = "
        SELECT p.id, p.fileira, p.coluna, p.usuario_id, p.status, u.email AS usuario_nome
    FROM 
        poltronas p 
    LEFT JOIN 
        usuarios u ON p.usuario_id = u.id
";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Poltrona::class);
        $poltronas = $stmt->fetchAll();

        return $poltronas ?: [];
    }

    function adquirir($id, $poltrona) {
        $p = $this->buscarPorId($id);
        if (!$p) 
            throw new Exception("Poltrona não encontrada!");

        $sql = "UPDATE poltrona SET usuario_id=:usuario_id, status=:status WHERE id=:id";
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':status', $poltrona->getStatus());
        $query->bindValue(':usuario_id', $poltrona->getUsuarioId());
        $query->bindValue(':id', $id);
        if (!$query->execute())
            throw new Exception("Erro ao atualizar registro.");

        $p->setStatus($poltrona->getStatus());
        $p->setUsuarioId($poltrona->getUsuarioId());
        return $p;
    }
}
?>
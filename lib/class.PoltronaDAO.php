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
            SELECT p.id, p.fileira, p.coluna, p.usuario_id,  p.status,  u.nome as usuario_nome
            FROM poltronas p 
            LEFT JOIN usuarios u 
            ON (p.usuario_id = u.id)
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, Poltrona::class);
        $poltronas = $stmt->fetchAll();

        return $poltronas ?: [];
    }

    function buscarPorId($id) {
        $sql = "
            SELECT 
                id, fileira, coluna, usuario_id, status 
            FROM 
                poltronas 
            WHERE 
                id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, Poltrona::class);
        $poltrona = $stmt->fetch();

        return $poltrona ?: null;
    }
}
?>
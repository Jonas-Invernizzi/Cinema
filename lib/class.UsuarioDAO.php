<?php
require_once "lib/class.Banco.php";
require_once "models/class.Usuario.php";

class UsuarioDAO {
    private $pdo;

    function __construct() { $this->pdo = Banco::getConexao(); }

    function buscarPorId($id) {
        $sql = "SELECT id, email FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }
}

?>
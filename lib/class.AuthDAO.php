<?php
require_once "lib/class.Banco.php";
require_once "models/class.Usuario.php";

class AuthDAO {
    private $pdo;

    function __construct() { $this->pdo = Banco::getConexao(); }

    function login($email, $password) {
        $sql = "SELECT id, email, password FROM usuarios WHERE email = :email and password = :password";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':password' => $password]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }
}

?>
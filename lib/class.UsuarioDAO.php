<?php
require_once "models/class.Usuario.php";

class UsuarioDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function cadastrar(Usuario $usuario) {
        $sql = "INSERT INTO usuarios (email, senha) VALUES (:email, :senha)";
        $stmt = $this->pdo->prepare($sql);

        $email = $usuario->getEmail();
        $senha = password_hash($usuario->getPassword(), PASSWORD_DEFAULT);

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $senha);

        if ($stmt->execute()) {
            $usuario->setId($this->pdo->lastInsertId());
            return $usuario;
        }
        return false;
    }

    public function login($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($senha, $dados['senha'])) {
                $u = new Usuario();
                $u->setId($dados['id']);
                $u->setEmail($dados['email']);
                $u->setPassword($dados['senha']);
                return $u;
            }
        }
        return false;
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            $u = new Usuario();
            $u->setId($dados['id']);
            $u->setEmail($dados['email']);
            return $u;
        }
        return null;
    }
}
?>
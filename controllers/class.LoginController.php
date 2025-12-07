<?php
use Firebase\JWT\JWT;

require_once "lib/class.UsuarioDAO.php";

class LoginController {

    function login(){
        global $key, $pdo;

        $dados = json_decode(file_get_contents("php://input"));

        if (!isset($dados->email) || !isset($dados->password)) {
            return ['error' => 'Dados incompletos'];
        }

        $dao = new UsuarioDAO($pdo);
        $usuario = $dao->login($dados->email, $dados->password);

        if ($usuario) {
            $payload = [
                'iss'=> 'http://localhost',
                'iat' => time(),
                'exp' => time() + 3600,
                'userId' => $usuario->getId()
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            return ['token' => $jwt];
        }

        return ['error' => 'Usuário ou senha inválidos!'];
    }
}
?>
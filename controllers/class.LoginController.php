<?php
require "vendor/autoload.php";
use Firebase\JWT\JWT;

require_once "dao/class.AuthDAO.php";

class LoginController {
    private $dao;

    function __construct(){
        $this->dao = new AuthDAO();
    }

    function login(){
        global $key;

        $dados = json_decode(file_get_contents("php://input"));

        $usuario = $this->dao->login($dados->email, $dados->password);


        if ($usuario) {
            $payload = [
                'iss'=> 'http://localhost',
                'iat' => time(),
                'exp' => time() + 1 * 60 * 60 
            ];

            $payload['userId'] = $usuario->getId();

            $jwt = JWT::encode($payload, $key, 'HS256');

            return ['token' => $jwt];
        }

        return ['error' => 'Usuário não encontrado!'];
    }
}

?>
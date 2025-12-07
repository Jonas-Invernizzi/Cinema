<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once "lib/class.UsuarioDAO.php";

class Auth {
    static function check(){
        global $key, $pdo;

        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return false;
        }

        $authorization = $headers['Authorization'];
        $parts = explode(" ", $authorization);
        $token = trim($parts[1]);

        try {
            $resultado = JWT::decode( 
                $token, new Key($key, 'HS256')
            );

            $dao = new UsuarioDAO($pdo);
            $usuario = $dao->buscarPorId($resultado->userId);

            return !!$usuario;

        }catch(Exception $e){
            return false;
        }
    }
}
?>
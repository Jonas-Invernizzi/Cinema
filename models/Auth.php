<?php
require "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once "dao/class.UsuarioDAO.php";

class Auth {
    static function check(){
        global $key;

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

            $dao = new UsuarioDAO();
            $usuario = $dao->buscarPorId($resultado->userId);

            return !!$usuario;

        }catch(Exception $e){
            http_response_code(403);
            echo json_encode(['error'=>$e->getMessage()]);
            exit;
        }
    }
}

?>
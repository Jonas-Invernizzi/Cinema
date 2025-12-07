<?php
class Banco {
    private static $pdo;

    static function getConexao(){
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:dbname=cinema;host=localhost;charset=utf8mb4", "root", "");
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                die("Erro de Conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }    
}
?>
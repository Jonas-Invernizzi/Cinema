<?php
require_once "models/class.Poltrona.php";

class PoltronaDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function listar() {
        $sql = "SELECT * FROM poltronas ORDER BY fileira, coluna";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $lista = [];
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Poltrona();
            $p->setId($dados['id']);
            $p->setFileira($dados['fileira']);
            $p->setColuna($dados['coluna']);
            $p->setUsuarioId($dados['usuario_id']);
            $p->setStatus($dados['status']);
            $lista[] = $p;
        }
        return $lista;
    }

    public function comprar($idPoltrona, $idUsuario) {
        $sql = "UPDATE poltronas SET usuario_id = :uid, status = 'Vendido' 
                WHERE id = :pid AND status = 'Disponivel'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':uid', $idUsuario);
        $stmt->bindValue(':pid', $idPoltrona);
        
        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}
?>
<?php
class Poltrona implements JsonSerializable {
    private $id;
    private $fileira;
    private $coluna;
    private $usuario_id;

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setFileira($fileira) { $this->fileira = $fileira; }
    public function getFileira() { return $this->fileira; }

    public function setColuna($coluna) { $this->coluna = $coluna; }
    public function getColuna() { return $this->coluna; }

    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    public function getUsuarioId() { return $this->usuario_id; }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'fileira' => $this->fileira,
            'coluna' => $this->coluna,
            'usuario_id' => $this->usuario_id,
        ];
    }
}
?>
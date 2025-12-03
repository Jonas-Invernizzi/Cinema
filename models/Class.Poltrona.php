<?php
class Poltrona implements JsonSerializable {
    private $id;
    private $fileira;
    private $coluna;
    private $usuario_id;
    private $status;
    
    function setFileira($fileira) { $this->fileira = $fileira; }
    function setColuna($coluna) { $this->coluna = $coluna; }
    function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    function setStatus($status) { $this->status = $status; }
    
    function getFileira() { return $this-> fileira; }
    function getColuna() { return $this-> coluna; }
    function getUsuarioId() { return $this-> usuario_id; }
    function getStatus() { return $this-> status; }
    
    function jsonSerialize(){
        return [
            'id'=> $this->id, 
            'fileira' => $this->fileira,
            'coluna' =>$this->coluna,
            'usuario_id' =>$this->usuario_id,
            'status' =>$this->status,
        ];
    }
}
?>
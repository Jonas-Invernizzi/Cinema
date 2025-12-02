<?php
class Categoria implements JsonSerializable {
    private $id;
    private $fileira;
    private $coluna;
    private $usuario_id;
    
    function setFileira($fileira) { $this->fileira = $fileira; }
    function setColuna($coluna) { $this->coluna = $coluna; }
    function setUsuarioId($Usuario_id) { $this->usuario_id = $usuario_id; }
    
    function getFileira() { return $this-> fileira; }
    function getColuna() { return $this-> coluna; }
    function getUsuarioId() { return $this-> usuario_id; }
    
    function jsonSerialize(){
        return [
            'id'=> $this->id, 
            'fileira' => $this->fileira,
            'coluna' =>$this->coluna,
            'usuario_id' =>$this->usuario_id,
        ];
    }
}
?>
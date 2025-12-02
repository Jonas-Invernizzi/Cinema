<?php
class Usuario implements JsonSerializable {
    private $id;
    private $email;
    private $password;
    
    function getId() { return $this->id; } 
    function setEmail($o) { $this->email = $o; }
    function getEmail() { return $this-> email; }
    function setPassword($o) { $this->password = $o; }
    function getPassword() { return $this-> password; }
    
    function jsonSerialize(){
        return [
            'id'=> $this->id, 
            'email' => $this->email
        ];
    }
}
?>
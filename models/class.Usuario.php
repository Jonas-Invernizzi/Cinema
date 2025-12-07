<?php
class Usuario implements JsonSerializable {
    private $id;
    private $email;
    private $password;
    
    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }
    
    public function setEmail($email) { $this->email = $email; }
    public function getEmail() { return $this->email; }
    
    public function setPassword($password) { $this->password = $password; }
    public function getPassword() { return $this->password; }
    
    public function jsonSerialize(): mixed {
        return [
            'id'=> $this->id, 
            'email' => $this->email
        ];
    }
}
?>
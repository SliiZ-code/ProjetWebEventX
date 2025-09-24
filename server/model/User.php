<?php
class User {
    private $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }
    
    public function authenticate($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT id, email, mdp, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['mdp'])) {
                return [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur authentification: " . $e->getMessage());
            return false;
        }
    }
}
?>
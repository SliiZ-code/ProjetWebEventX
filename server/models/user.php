<?php
class User {
    public $id;
    public $email;
    public $password_hash;
    public $first_name;
    public $last_name;
    public $description;
    public $profile_photo;

    public function __construct($id, $email, $password_hash, $first_name = null, $last_name = null, $description = null, $profile_photo = null) {
        $this->id = $id;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->description = $description;
        $this->profile_photo = $profile_photo;
    }
    # delete, getAll, create, update, getOne

    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new User($row['id'], $row['email'], $row['password_hash'], $row['first_name'], $row['last_name'], $row['description'], $row['profile_photo']) : null;
    }

    public static function getOne($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new User($row['id'], $row['email'], $row['password_hash'], $row['first_name'], $row['last_name'], $row['description'], $row['profile_photo']) : null;
    }

    public static function getAll() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($row) {
            return new User($row['id'], $row['email'], $row['password_hash'], $row['first_name'], $row['last_name'], $row['description'], $row['profile_photo']);
        }, $row);
    }

    public static function create($email, $password) {
        global $pdo;
        if (self::findByEmail($email)) {
            throw new Exception("Email already registered");
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
        if ($stmt->execute([$email, $password_hash])) {
            return new User($pdo->lastInsertId(), $email, $password_hash);
        } else {
            throw new Exception("Failed to create user");
        }
    }

    public function update($fields) {
        global $pdo;
        $setParts = [];
        $values = [];
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $this->id;
        $setString = implode(", ", $setParts);
        $stmt = $pdo->prepare("UPDATE users SET $setString WHERE id = ?");
        return $stmt->execute($values);
    }

    #public function verifyPassword($password) {
    #    return password
}
?>
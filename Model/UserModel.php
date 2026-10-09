<?php

require_once __DIR__ . '/db.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare(
            'SELECT id, last_name, first_name, email, password FROM users WHERE email = ? LIMIT 1'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $user;
    }
}

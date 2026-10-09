<?php
// ══════════════════════════════════════════
// COOK WITH SOUMI — config/db.php
// Connexion MySQLi
// ══════════════════════════════════════════

define('DB_HOST', 'localhost');
define('DB_NAME', 'cook_with_soumi');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB()
{
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME
        );

        if ($conn->connect_error) {
            die('Erreur de connexion : ' . $conn->connect_error);
        }

        $conn->set_charset('utf8mb4');
    }

    return $conn;
}

/**
 * Fermer la connexion
 */
function closeDB()
{
    $conn = getDB();

    if ($conn) {
        $conn->close();
    }
}

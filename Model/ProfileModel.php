<?php

// Importe le fichier db.php.
//
// Ce fichier contient normalement la fonction getDB()
// qui permet d'établir la connexion avec la base de données.
require_once(__DIR__ . '/db.php');


/**
 * Classe ProfileModel
 *
 * Cette classe est responsable de la récupération
 * des informations du profil d'un utilisateur.
 *
 * Le Model communique directement avec la base de données.
 */
class ProfileModel
{
    // Contient la connexion à la base de données.
    //
    // "private" signifie que cette variable
    // est uniquement accessible à l'intérieur de la classe.
    private $db;


    /**
     * Constructeur de la classe.
     *
     * Il est automatiquement exécuté lorsqu'on crée
     * un objet ProfileModel.
     */
    public function __construct()
    {
        // Appelle la fonction getDB()
        // afin d'obtenir une connexion à la base de données.
        //
        // La connexion est ensuite stockée dans $this->db.
        $this->db = getDB();
    }


    /**
     * Récupère les informations d'un utilisateur
     * grâce à son identifiant.
     *
     * @param int $id Identifiant de l'utilisateur
     * @return array|null Informations de l'utilisateur
     *                    ou null si aucun utilisateur n'est trouvé.
     */
    public function getUserById($id)
    {
        // Prépare la requête SQL.
        //
        // On récupère uniquement les informations
        // nécessaires pour afficher le profil :
        //
        // - id
        // - last_name
        // - first_name
        // - email
        //
        // Le "?" sera remplacé par l'identifiant
        // de l'utilisateur.
        $stmt = $this->db->prepare(
            "SELECT id, last_name, first_name, email
             FROM Users
             WHERE id = ?"
        );


        // Associe la variable $id au "?"
        // présent dans la requête SQL.
        //
        // "i" signifie que $id est un entier (integer).
        //
        // L'utilisation d'une requête préparée permet
        // notamment de se protéger contre les injections SQL.
        $stmt->bind_param("i", $id);


        // Exécute la requête SQL.
        $stmt->execute();


        // Récupère le résultat de la requête.
        $result = $stmt->get_result();


        // Récupère la première ligne du résultat
        // sous forme de tableau associatif.
        //
        // Par exemple :
        //
        // [
        //     'id' => 5,
        //     'last_name' => 'Dupont',
        //     'first_name' => 'Jean',
        //     'email' => 'jean@email.com'
        // ]
        //
        // Si aucun utilisateur n'est trouvé,
        // fetch_assoc() retournera null.
        $user = $result->fetch_assoc();


        // Ferme la requête préparée.
        //
        // Cela permet de libérer les ressources
        // utilisées par la requête.
        $stmt->close();


        // Retourne les informations de l'utilisateur
        // au Controller.
        return $user;
    }
}

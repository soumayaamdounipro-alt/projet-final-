<?php

// Importe le modèle ProfileModel.
// Le modèle contient les fonctions permettant d'accéder
// aux informations de l'utilisateur dans la base de données.
require_once __DIR__ . '/../Model/ProfileModel.php';


/**
 * Classe ProfileController
 *
 * Ce contrôleur gère la page "Mon Profil".
 *
 * Son rôle est notamment :
 * - de vérifier que l'utilisateur est connecté ;
 * - de récupérer ses informations ;
 * - d'envoyer ces informations à la vue.
 */
class ProfileController
{
    /**
     * Affiche la page du profil de l'utilisateur.
     */
    public function index()
    {
        // Vérifie si l'utilisateur possède un identifiant
        // dans la session.
        //
        // $_SESSION['user_id'] contient normalement l'ID
        // de l'utilisateur actuellement connecté.
        //
        // empty() retourne true si la variable est absente,
        // vide ou contient une valeur considérée comme vide.
        if (empty($_SESSION['user_id'])) {

            // Si aucun utilisateur n'est connecté,
            // on redirige vers la page de connexion.
            //
            // URL correspond à une constante contenant
            // l'adresse de base du site.
            header('Location: ' . URL . '?page=login');

            // Arrête immédiatement l'exécution du script
            // après la redirection.
            exit;
        }


        // Création d'un objet ProfileModel.
        //
        // Le modèle sera utilisé pour récupérer
        // les informations de l'utilisateur dans la base de données.
        $model = new ProfileModel();


        // Récupère l'identifiant de l'utilisateur
        // stocké dans la session.
        //
        // Puis appelle la méthode getUserById()
        // pour récupérer ses informations depuis la base de données.
        $user = $model->getUserById($_SESSION['user_id']);


        // Vérifie si aucun utilisateur n'a été trouvé.
        //
        // Cela peut arriver par exemple si :
        // - l'utilisateur a été supprimé de la base de données ;
        // - l'ID présent dans la session n'est plus valide.
        if (!$user) {

            // Détruit complètement la session.
            //
            // Cela permet notamment de supprimer
            // $_SESSION['user_id'].
            session_destroy();


            // Redirige l'utilisateur vers la page de connexion.
            header('Location: ' . URL . '?page=login');


            // Arrête l'exécution du script.
            exit;
        }


        // Définit le titre de la page.
        //
        // Cette variable pourra être utilisée
        // dans la vue profile.php.
        $pageTitle = 'Mon Profil';


        // Charge la vue qui affiche le profil.
        //
        // À ce moment-là, les variables :
        // - $user
        // - $pageTitle
        //
        // sont disponibles dans profile.php.
        require_once __DIR__ . '/../Vue/profile.php';
    }
}


// Création d'un objet ProfileController.
$controller = new ProfileController();


// Appelle la méthode index() pour exécuter
// le traitement de la page du profil.
$controller->index();

<?php

//session pour utilisateur sur chaque page
session_start();

//appel requete sql pour fonctions
require_once '/app/request/categories.php';

//verif si utilisateur a droit admin sinon redirige vers login
if(empty($_SESSION['LOGGED_USER']) || 
!in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])){

    $_SESSION['messages']['error'] = "Vous n'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

$categorie = findOneCategorieById(isset($_POST['id']) ? $_POST['id'] : 0);

if ($categorie) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        if(deleteCategorie($categorie['id'])) {
            if ($categorie['imageName'] && file_exists("/app/upload/categories/$categorie[imageName]")) {
                unlink("/app/upload/categories/$categorie[imageName]");
            }

            $_SESSION['message']['success'] = "Catégorie supprimée avec succès";
            
        } else {
            $_SESSION['messages']['error'] = "Une erreur est survenue";
        }
    } else {
        $_SESSION['messages']['error'] = "Token CSRF invalide";
    }

} else {
    $_SESSION['messages']['error'] = "Catégorie non trouvée";
}

http_response_code(302);
header("Location: /admin/categories");
exit();
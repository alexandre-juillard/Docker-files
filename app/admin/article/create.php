<?php

//session start//
session_start();

//connexion base de données/import fichier function article//
require_once '/app/env/variables.php';
require_once '/app/request/article.php';

//verif droit utilisateur//
if(empty($_SESSION['LOGGED_USER']) || 
!in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])){

    $_SESSION['messages']['error'] = "Vous n\'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

//verifi si les champs sont remplis//
if(
    !empty($_POST['title']) &&
    !empty($_POST['description'])
){
    //nettoyage données//
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? 1 : 0; //pas besoin d'écrire true car renvoie la valeur de isset qui est true

    //verifier si titre est unique
if(!findOneArticleByTitle($title)) { //creer fonction findArticleById

    if(createArticle($title, $description, $enable)) {  //creer fonction create Article
        $_SESSION['messages']['succes'] = 'Article ajouté avec succès';
        //envoie en base données
        http_response_code(302);
        header("Location: /admin/articles");
        exit();

        } else {
                $errorMessage = "Une erreur est survenue, veuillez réessayer";
        }    
    } else {
            $errorMessage = "Le titre existe déjà";
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = 'Veuillez remplir tous les champs obligatoires';
    }
?>

<!-- formulaire html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation d'article | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>
<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Creation des articles</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form mt-2">
            <?php if (isset($errorMessage)) :?>
                <div class="alert alert-danger">
                    <?= $errorMessage; ?>
                </div>
            <?php endif; ?>
            <div class="group-input">
                <label for="title">Titre: </label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="group-input">
                <label for="description">Description: </label>
                <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </div>
            <div class="group-input checkbox">
                <input type="checkbox" name="enable" id="enable">
                <label for="enable">Actif</label>
            </div>
            <button type="submit" class="btn btn-primary">Créer article</button>
            </form>
        </section>
    </main>
    
</body>
</html>


<?php

//garde session du user connecté active sur chaque page
session_start();

//appel variables et requetes sql pour fonctions
require_once '/app/env/variables.php';
require_once '/app/request/categories.php';

//verif si utilisateur a droit admin, sinon redirige vers page login
if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {

    $_SESSION['messages']['error'] = "Vous n'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

//verifi si les champs sont remplis//
if (
    !empty($_POST['title']) &&
    !empty($_FILES['image']['name']) //les images de type file sont dans superglobal FILES
) {

    //nettoyage données//
    $title = strip_tags($_POST['title']);
    $imageName = uploadCategorieImage($_FILES['image']);
    //verifier si titre existe deja
    if (!findOneCategorieByTitle($title)) {

        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
            
            
            if (createCategorie($title, $imageName)) {
                $_SESSION['messages']['success'] = 'Catégorie ajoutée avec succès';
                //envoie en base données
                http_response_code(302);
                header("Location: /admin/categories");
                exit();
            } else {
                $errorMessage = "Une erreur est survenue, veuillez réessayer";
            }
        } else {
            $errorMessage = 'Image non valide';
        }
    } else {
        $errorMessage = 'Titre déjà utilisé';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') { //evite message erreur au chargement
    $errorMessage = 'Veuillez remplir tous les champs obligatoires';
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation de catégories | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Creation des catégories</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form mt-2" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="title">Titre: </label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="group-input">
                    <label for="image">Image: </label>
                    <input type="file" name="image" id="image">
                </div>
                <button type="submit" class="btn btn-primary">Créer catégorie</button>
            </form>
        </section>
    </main>

</body>

</html>
<?php

//session start//
session_start();

//connexion base de données/import fichier function article//
require_once '/app/env/variables.php';
require_once '/app/request/article.php';
require_once '/app/request/categories.php';

//verif droit utilisateur//
if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {

    $_SESSION['messages']['error'] = "Vous n\'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

//verifi si les champs sont remplis//
if (
    !empty($_POST['title']) &&
    !empty($_POST['description']) &&
    !empty($_POST['categories'])
) {

    //nettoyage données//
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? 1 : 0; //pas besoin d'écrire true car renvoie la valeur de isset qui est true
    $categorie_id = filter_input(INPUT_POST, 'categories', FILTER_VALIDATE_INT);
    if ($categorie_id) {
        //verifier si titre est unique
        if (!findOneArticleByTitle($title)) { //creer fonction findArticleById

            if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
                $imageName = uploadArticleImage($_FILES['image']);
            }

            if (createArticle($title, $description, $enable, $_SESSION['LOGGED_USER']['id'], $categorie_id, isset($imageName) ? $imageName : null)) {  //creer fonction create Article en bd
                $_SESSION['messages']['success'] = 'Article ajouté avec succès';
                //envoie en base données
                http_response_code(302);
                header("Location: /admin/article");
                exit();
            } else {
                $errorMessage = "Une erreur est survenue, veuillez réessayer";
                // var_dump($_POST);
            }
        } else {
            $errorMessage = "Le titre existe déjà";
        }
    } else {
        $errorMessage = "Sélectionner une catégorie";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    <label for="categories">Catégorie: </label>
                    <select name="categories" id="categories">
                        <option value="" disabled selected>--Choisir une catégorie--</option>
                        <?php foreach (findAllCategories() as $categorie) : ?>
                            <option value="<?= $categorie['id']; ?>"><?= "$categorie[title]"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="group-input">
                    <label for="description">Description: </label>
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                </div>
                <div class="group-input">
                    <label for="image">Image: </label>
                    <input type="file" name="image" id="image">
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
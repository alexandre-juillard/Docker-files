<?php

//garde session du user connecté active sur chaque page
session_start();

//appel variables et requetes sql pour fonctions
require_once '/app/env/variables.php';
require_once '/app/request/categories.php';

if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {

    $_SESSION['messages']['error'] = "Vous n'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

$categorie = findOneCategorieById(isset($_GET['id']) ? $_GET['id'] : 0);

if (!$categorie) {
    $_SESSION['messages']['error'] = 'Catégorie non trouvée';
    header('Location: /admin/categories');
    exit();
}

//verif de soumission du formulaire
if (!empty($_POST['title'])) {
    $title = strip_tags($_POST['title']);

    if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
        $imageName = uploadCategorieImage($_FILES['image'], $categorie['imageName']);
    }

    $oldTitle = $categorie['title'];

    if ($oldTitle === $title || !findOneCategorieByTitle($title)) {

        if (updateCategorie($categorie['id'], $title, isset($imageName) ? $imageName : null)) {
            $_SESSION['messages']['success'] = "Catégorie modifiée avec succès";

            http_response_code(302);
            header("Location: /admin/categories");

            exit();
        } else {
            $errorMessage = 'Une erreur est survenue';
        }
    } else {
        $errorMessage = 'Titre déjà existant';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = 'Veuillez remplir tous les champs obligatoires';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Catégories | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/notif.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Modifier une catégorie</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" method="POST" class="form" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="title">Titre: </label>
                    <input type="text" name="title" id="title" required value="<?= $categorie['title']; ?>">
                </div>
                <div class="group-input">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                    <img src="/upload/categories/<?= $categorie['imageName']; ?>" alt="" loading="lazy">
                </div>
                <button type="submit" class="btn btn-primary">Valider les modifications</button>
            </form>
        </section>
    </main>

</body>

</html>
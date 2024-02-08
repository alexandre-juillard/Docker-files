<?php

session_start();

require_once '/app/env/variables.php';
require_once '/app/request/article.php';
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

$article = findOneArticleById(isset($_GET['id']) ? $_GET['id'] : 0);

if (!$article) {
    $_SESSION['messages']['error'] = 'Article non trouvé';
    header('Location: /admin/article');
    exit();
}

//verif de soumission du formulaire
if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])
) {
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? 1 : 0;
    $categorie_id = filter_input(INPUT_POST, 'categories', FILTER_VALIDATE_INT);

    if ($categorie_id) {
        $oldTitle = $article['title'];

        if ($oldTitle === $title || !findOneArticleByTitle($title)) {
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
                $imageName = uploadArticleImage($_FILES['image'], $article['imageName']);
            }

            if (updateArticle($article['id'], $title, $description, $enable, $categorie_id, isset($imageName) ? $imageName : null)) {
                $_SESSION['messages']['success'] = "Article modifié avec succès";

                http_response_code(302);
                header("Location: /admin/article/index.php");

                exit();
            } else {
                $errorMessage = 'Une erreur est survenue';
            }
        } else {
            $errorMessage = 'Titre déjà existant';
        }
    } else {
        $errorMessage = "La catégorie n'est pas valide";
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
    <title>Modification Articles | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/notif.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Modifier un article</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" method="POST" class="form" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="title">Titre: </label>
                    <input type="text" name="title" id="title" required value="<?= $article['title']; ?>">
                </div>
                <div class="group-input">
                    <label for="categories"></label>
                    <select name="categories" id="categories">
                        <option value="" disabled selected>--Choisir une catégorie--</option>
                        <?php foreach (findAllCategories() as $categorie) : ?>
                            <option value="<?= $categorie['id']; ?>" <?= $categorie['id'] === $article['categorie_id'] ? "selected" : null; ?>><?= "$categorie[title]"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="group-input">
                    <label for="description">Description: </label>
                    <textarea name="description" id="description" cols="70" rows="10" required><?= $article['description']; ?></textarea>
                </div>
                <div class="group-input">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                    <?php if ($article['imageName']) : ?>
                        <img src="/upload/articles/<?= $article['imageName']; ?>" alt="" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="enable" id="enable" <?= $article['enable'] ? 'checked' : null; ?>>
                    <label for="enable">Actif</label>

                </div>
                <button type="submit" class="btn btn-primary">Valider les modifications</button>
            </form>
        </section>
    </main>

</body>

</html>
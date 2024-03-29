<?php

session_start();

require_once '/app/env/variables.php';
require_once '/app/request/article.php';


if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {

    $_SESSION['messages']['error'] = "Vous n'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}
//genere un token avec caractères aleatoires stocké dans super global SESSION
$_SESSION['token'] = bin2hex(random_bytes(50));

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <?php require_once '/app/layout/notif.php'; ?>
            <h1 class="text-center">Admin des articles</h1>
            <a href="/admin/article/create.php" class="btn btn-primary">Créer un article</a>
            <div class="card-list">
                <?php
                $articles = paginateArticle(3, isset($_GET['page']) ? $_GET['page'] : 1); 
                foreach ($articles['data'] as $article) : ?>
                    <div class="card">
                        <?php if ($article['imageName']) : ?>
                            <img src="/upload/articles/<?= $article['imageName']; ?>" alt="" loading="lazy">
                        <?php endif; ?>
                        <h2 class="card-header"><?= $article['title']; ?></h2>
                        <p><strong>Description:</strong><?= substr($article['description'], 0, 25) . '...'; ?></p>
                        <em><strong>Date de création: </strong><?= convertDateArticle($article['createdAt'], 'd/m/Y'); ?></em>
                        <p><strong>Disponible: </strong><?= $article['enable']; ?></p>
                        <p><em><strong>Auteur: </strong><?= $article['firstName'], $article['lastName']; ?></em></p>
                        <p><em><strong>Catégorie: </strong><?= $article['categTitle']; ?></em></p>
                        <div class="card-btn">
                            <a href="/admin/article/update.php?id=<?= $article['id']; ?>" class="btn btn-primary">Editer</a>
                            <form action="/admin/article/delete.php" method="POST" onsubmit="return confirm('Etes vous sur de vouloir supprimer cet article?')">
                                <input type="hidden" name="id" value="<?= $article['id']; ?>">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <nav class="pagination">
                <ul class="pagination-links">
                    <?php for($i=1; $i <= $articles['nbrPage']; $i++):?>
                        <li class="pagination-link"><a href="?page=<?= $i;?>">Page <?= $i ;?></a></li>
                    <?php endfor;?>
                </ul>
            </nav>
        </section>
    </main>
</body>
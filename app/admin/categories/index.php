<?php 

//garde session du user connecté active sur chaque page
session_start();

//appel variables et requetes sql pour fonctions
require_once '/app/env/variables.php';
require_once '/app/request/categories.php';

//verif si utilisateur a droit admin, sinon redirige vers page login
if(empty($_SESSION['LOGGED_USER']) || 
!in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])){

    $_SESSION['messages']['error'] = "Vous n'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

//genere un token avec caractères aleatoires stocké dans super global SESSION (pour suppression)
$_SESSION['token'] = bin2hex(random_bytes(50));


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Catégories | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
        <?php require_once '/app/layout/notif.php'; ?>
            <h1 class="text-center">Admin des categories</h1>
            <a href="/admin/categories/create.php" class="btn btn-primary">Créer une categorie</a>
            <div class="card-list">
                <?php foreach(findAllCategories() as $categorie) : ?>
                    <div class="card">
                        <?php if($categorie['imageName']): ?>
                            <img src="/upload/categories/<?= $categorie['imageName']; ?>" alt="" loading="lazy">
                        <?php endif; ?>
                        <h2 class="card-header"><?= $categorie['title']; ?></h2>                       
                        <div class="card-btn">
                            <a href="/admin/categories/update.php?id=<?= $categorie['id'];?>" class="btn btn-primary">Editer</a>
                            <form action="/admin/categories/delete.php" method="POST" onsubmit="return confirm('Etes vous sur de vouloir supprimer cet categorie?')">
                                <input type="hidden" name="id" value="<?= $categorie['id']; ?>">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
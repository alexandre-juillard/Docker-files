<?php

session_start();

require_once '/app/env/variables.php';
require_once '/app/request/users.php';

if(empty($_SESSION['LOGGED_USER']) || 
!in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])){

    $_SESSION['messages']['error'] = "Vous n'avez pas les droits pour cette page";

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

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
            <h1 class="text-center">Admin des utilisateurs</h1>
            <div class="card-list">
                <?php foreach(findAllUsers() as $user) : ?>
                    <div class="card">
                        <h2 class="card-header"><?= "$user[firstName] $user[lastName]"; ?></h2>
                        <p><strong>Email:</strong><?= $user['email']; ?></p>
                        <div class="card-btn">
                            <a href="/admin/users/update.php?id=<?= $user['id'];?>" class="btn btn-primary">Editer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
<?php
session_start();
require_once '/app/env/variables.php';
require_once '/app/request/users.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
    <link rel="stylesheet" href="<?= $cssPath; ?>index.css">
</head>

<body>
    <?php var_dump(findAllUsers()); ?>
    <?php require_once './layout/header.php'; ?>
    <main>
        <h1>Hello world</h1>
        <form action="/contact.php" method="POST" enctype="multipart/form-data">
            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" id="prenom" placeholder="John" required>
            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" placeholder="Doe">
            <label for="message">Votre message:</label>
            <textarea name="message" id="message" cols="20" rows="5"></textarea>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </main>
</body>

</html>
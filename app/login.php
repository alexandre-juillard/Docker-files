<?php 

session_start(); //creation nouvelle session php

require_once '/app/env/variables.php'; 

$users = [
    [
        'email' => 'alex@test.com',
        'password' => 'Test1234!',
    ]
];
//verifie que les données ne sont pas vides
if(!empty($_POST['email']) && !empty($_POST['password'])){
    foreach($users as $user){ //parcourt la liste des users

         //verifie que l'email existe dans users et password correspond a son pass
        if(in_array($_POST['email'], $user) && 
            $_POST['password'] ===$user['password']){
            $_SESSION['LOGGED_USER'] = [ //si vrai, stock email de l'user connecté
                'email' => $user['email'],
            ];
            http_response_code(302);
            header("Location: /"); //redirige le client vers une autre url
            exit(); //arret execution du scrip
        }   
    }
}
else if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errorMessage ="Veuillez renseigner les champs obligatoires";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>
<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class ="text-center mt-2">Connexion</h1>
        <form action="/login.php" method="POST" class="form">
            <?php if(isset($errorMessage)): ?>
                <div class="alert alert-danger">
                    <?= $errorMessage; ?>
                </div>
            <?php endif; ?>
            <div class="group-input">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" placeholder="john@exemple.com" required>
            </div>
            <div class="group-input">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" placeholder="S3CR3T" required>
            </div>
            <button type="submit" class="btn btn-primary">Connexion</button>
        </form>
        </section>
        
    </main>
    
</body>
</html>
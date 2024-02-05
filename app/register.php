<?php

session_start();
require_once '/app/env/variables.php';
require_once '/app/request/users.php';

if( //verif que les champs sont biens remplis
    !empty($_POST['firstname']) &&
    !empty($_POST['lastname']) &&
    !empty($_POST['email']) &&
    !empty($_POST['password'])
){ 
    //nettoyage des données
    $firstName = strip_tags($_POST['firstname']); //recupere que les chaines
    $lastName = strip_tags($_POST['lastname']); //recupere que les chaines
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //verif si email est propre
    $password = password_hash($_POST['password'], PASSWORD_ARGON2I); // hash le password avec algo
    
    //gérer les erreurs utilisateur
    if($email) {
        //email ok, verif si il existe
        if(!findOneUserByEmail($email)) {
            //on crée l'utilisateur
            if (createUser($firstName, $lastName, $email, $password)){
                //on redirige vers page de connexion
                http_response_code(302);
                header("Location: /login.php");
                exit();
            }
            else{
                $errorMessage = "Une erreur est survenue, veuillez réessayer";
            }
        }
        else{
            $errorMessage = "L'email est déjà utilisé par un autre compte";
        }
    }
    else{
        $errorMessage = 'Veuiller rentrer un email valide';
    }
}
else if ($_SERVER['REQUEST_METHOD'] ==='POST') {
    $errorMessage = 'Veuiller remplir tous les champs obligatoires';
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>
<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Inscription</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form">
            <?php if (isset($errorMessage)) :?>
                <div class="alert alert-danger">
                    <?= $errorMessage; ?>
                </div>
            <?php endif; ?>
                <div class="group-input">
                <label for="firstname">Prénom: </label>
                <input type="text" name="firstname" id="firstname" placeholder="Bobby" required>
            </div>
                <div class="group-input">
                <label for="lastname">Nom: </label>
                <input type="text" name="lastname" id="lastname" placeholder="Wilson" required>
            </div>
                <div class="group-input">
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" placeholder="bobby@exemple.com" required>
            </div>
                <div class="group-input">
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" placeholder="S3CR3T" required>
            </div>
            <button type="submit" class="btn btn-primary">S'incrire</button>
            </form>
        </section>
    </main>
    
</body>
</html>
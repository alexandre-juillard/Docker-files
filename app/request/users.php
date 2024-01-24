<?php

require_once '/app/config/mysql.php';

function findAllUsers(): array{
    global $db;  //rendre la variable db globale pur l'utiliser
    $sqlStatement = $db->prepare("SELECT * FROM users");  //preparer une requete SQL
    $sqlStatement->execute(); //execute la requet sql

    return $sqlStatement->fetchAll(); //renvoie le resultat de l'execution sous forme de tableau associatif
}
/**
 * function to find user by email adress
 *
 * @param string $email
 * @return ?array
 */
function findOneUserByEmail(string $email) :bool|array{
    global $db;
    $sqlStatement = $db->prepare("
    SELECT firstName, lastName, email, password FROM users WHERE email = :email");
    $sqlStatement->execute(['email' => $email],);

    return $sqlStatement->fetch();
}
/**
 * function to create new user
 *
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 * @param string $password
 * @return boolean
 */
function createUser(string $firstName, string $lastName, string $email, string $password) :bool {
    global $db;
    try{
        $sqlStatement = $db->prepare("INSERT INTO users(firstName, lastName, email, password) 
        VALUES(:firstName, :lastName, :email, :password)");
        $sqlStatement->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $password,
        ]);
    }
    catch(PDOException $error) {
        return false;
    }
    return true;
} 
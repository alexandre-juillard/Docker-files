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
    SELECT id, firstName, lastName, email, password, roles FROM users WHERE email = :email");
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
/**
 * Undocumented function
 *
 * @param integer $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 * @param ?array $roles
 * @return boolean
 */
function updateUser(int $id, string $firstName, string $lastName, string $email, ?array $roles) :bool {
    global $db;
    try{
        $sqlStatement = $db->prepare("UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email, roles = :roles WHERE id = :id");
        $sqlStatement->execute([
            'id' => $id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email, 
            'roles' => $roles ? json_encode($roles) : null,
        ]);
    }
    catch(PDOException $error){
        return false;
    }
    return true;
}
/**
 * Undocumented function
 *
 * @param integer $id
 * @return boolean
 */
function deleteUser(int $id): bool {
    global $db;
    try{
        $sqlStatement = $db->prepare("DELETE FROM users WHERE id = :id");
        $sqlStatement->execute([
            'id' => $id,
        ]);

    }
    catch(PDOException $error){
        return false;
    }
    return true;
}
/**
 * function to find user by id
 *
 * @param integer $id
 * @return boolean|array
 */
    function findOneUserById(int $id) :bool|array{
        global $db;
        $sqlStatement = $db->prepare("SELECT * FROM users WHERE id = :id");
        $sqlStatement ->execute([
            'id' => $id,
        ]);
        return $sqlStatement->fetch();
    } 
<?php

require_once '/app/config/mysql.php';

/**
 * Undocumented function
 *
 * @return array
 */
function findAllArticles(): array {
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM article");  //preparer une requete SQL
    $sqlStatement->execute(); //execute la requet sql

    return $sqlStatement->fetchAll();
}

/**
 * Undocumented function
 *
 * @param string $title
 * @return boolean|array
 */
function findOneArticleByTitle(string $title): bool|array{
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM article WHERE title = :title");
    $sqlStatement->execute([
        'title' => $title,
    ]);
    return $sqlStatement->fetch();
} 

/**
 * Undocumented function
 *
 * @param integer $id
 * @return boolean|array
 */
function findOneArticleById(int $id) :bool|array {
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM article WHERE id = :id");
    $sqlStatement->execute([
        'id'=>$id,
    ]);
    return $sqlStatement->fetch();
}

/**
 * Undocumented function
 *
 * @param string $title
 * @param string $description
 * @param int $enable
 * @return boolean
 */
function createArticle(string $title, string $description, int $enable): bool {
    global $db;
    try{
        $sqlStatement = $db->prepare("INSERT INTO article(title, description, enable) 
        VALUES(:title, :description, :enable)");
        $sqlStatement->execute([
            'title' => $title,
            'description' => $description,
            'enable' => $enable
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
 * @param string $title
 * @param string $description
 * @return boolean
 */
function updateArticle(int $id, string $title, string $description, int $enable): bool {
 global $db;
 try{
    $sqlStatement = $db->prepare("UPDATE article SET title = :title, description = :description, enable = :enable WHERE id = :id");
    $sqlStatement->execute([
        'id' => $id,
        'title' => $title,
        'description' => $description,
        'enable' => $enable,
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
 * @param string $date
 * @param string $format
 * @return string
 */
function convertDateArticle(string $date, string $format): string {
    return (new DateTime($date))->format($format);
}
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
function findOneArticleByTitle(string $title) :bool|array{
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM article WHERE title = :title");
    $sqlStatement ->execute([
        'title' => $title,
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
function createArticle(string $title, string $description, int $enable) :bool {
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
<?php

require_once '/app/config/mysql.php';

/**
 * Undocumented function
 *
 * @return array
 */
function findAllArticles(): array
{
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM article");  //preparer une requete SQL
    $sqlStatement->execute(); //execute la requet sql

    return $sqlStatement->fetchAll();
}

function findAllArticlesWithAuthor(): array
{
    global $db;
    $query = "SELECT a.id, a.title, a.description, a.createdAt, a.enable, a.imageName,
    u.firstName, u.lastName , c.title AS categTitle
    FROM article a 
    JOIN users u ON a.auteur_id = u.id
    LEFT JOIN categories c ON a.categorie_id = c.id";
//left join pour récuperer tous les articles meme ceux sans categorie
    $sqlStatement = $db->prepare($query);
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

/**
 * Undocumented function
 *
 * @param string $title
 * @return boolean|array
 */
function findOneArticleByTitle(string $title): bool|array
{
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
function findOneArticleById(int $id): bool|array
{
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM article WHERE id = :id");
    $sqlStatement->execute([
        'id' => $id,
    ]);
    return $sqlStatement->fetch();
}

/**
 * Undocumented function
 *
 * @param string $title
 * @param string $description
 * @param int $enable
 * @param string $imageName
 * @return boolean
 */
function createArticle(string $title, string $description, int $enable, int $auteur_id, int $categorie_id, ?string $imageName): bool
{
    global $db;
    try {
        $params = [
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
            'auteur_id' => $auteur_id,
            'categorie_id' => $categorie_id,
        ];

        if ($imageName) {
            $query = "INSERT INTO article(title, description, enable, imageName, auteur_id, categorie_id) VALUE(:title, :description, :enable, :imageName, :auteur_id, :categorie_id)";
            $params['imageName'] = $imageName;
        } else {
            $query = "INSERT INTO article(title, description, enable, auteur_id, categorie_id) VALUE(:title, :description, :enable, :auteur_id, :categorie_id)";
        }
        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        // die($error->getMessage());
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
function updateArticle(int $id, string $title, string $description, int $enable, int $categorie_id, ?string $imageName): bool
{
    global $db;
    try {
        $query = "UPDATE article SET title = :title, description = :description, enable = :enable, categorie_id = :categorie_id";
        $params = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
            'categorie_id' => $categorie_id,
        ];

        if ($imageName) {
            $query .= ", imageName = :imageName";
            $params['imageName'] = $imageName;
        }

        $query .= " WHERE id = :id";
        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
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
function convertDateArticle(string $date, string $format): string
{
    return (new DateTime($date))->format($format);
}

function uploadArticleImage(array $image, ?string $oldImageName = null): bool|string
{
    if ($image['size'] < 16000000) {
        $fileInfo = pathinfo($image['name']);
        $extension = $fileInfo['extension'];
        $extensionAllowed = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];

        if (in_array($extension, $extensionAllowed)) {
            $fileName = $fileInfo['filename'] //reprend nom pur du fichier
                . '_' . //concatenation
                (new DateTime())->format('Y-m-d_H:i:s') //ajoute date du serveur instant T
                . '.' . $extension; //concatenation de l extension
            move_uploaded_file($image['tmp_name'], "/app/upload/articles/$fileName");

            if ($oldImageName && file_exists("/app/upload/articles/$oldImageName")) {
                unlink("/app/upload/articles/$oldImageName");
            }

            return $fileName;
        };
    };
    return false;
}

function deleteArticle(int $id): bool
{
    global $db;
    try {
        $sqlStatement = $db->prepare("DELETE FROM article WHERE id= :id");
        $sqlStatement->execute([
            'id' => $id,
        ]);
    } catch (PDOException $error) {
        return false;
    }
    return true;
}
<?php

require_once '/app/config/mysql.php';

/**
 * Undocumented function
 *
 * @return array
 */
function findAllCategories(): array {
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM categories");  //preparer une requete SQL
    $sqlStatement->execute(); //execute la requet sql

    return $sqlStatement->fetchAll();
}

/**
 * Undocumented function
 *
 * @param integer $id
 * @return boolean|array
 */
function findOneCategorieById(int $id) :bool|array {
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM categories WHERE id = :id");
    $sqlStatement->execute([
        'id'=>$id,
    ]);
    return $sqlStatement->fetch();
}

/**
 * Undocumented function
 *
 * @param string $title
 * @return boolean|array
 */
function findOneCategorieByTitle(string $title): bool|array {
    global $db;
    $sqlStatement = $db->prepare("SELECT * FROM categories WHERE title = :title");
    $sqlStatement->execute([
        'title' => $title,
    ]);
    return $sqlStatement->fetch();
}

function uploadCategorieImage(array $image, ?string $oldImageName = null): bool|string {
    if($image['size'] < 16000000) {
        $fileInfo = pathinfo($image['name']);
        $extension= $fileInfo['extension'];
        $extensionAllowed = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];
    
        if(in_array($extension, $extensionAllowed)) {
            $fileName = $fileInfo['filename'] //reprend nom pur du fichier
            . '_' . //concatenation
            (new DateTime())->format('Y-m-d_H:i:s') //ajoute date du serveur instant T
            .'.'. $extension; //concatenation de l extension
            move_uploaded_file($image['tmp_name'], "/app/upload/categories/$fileName");

            if($oldImageName && file_exists("/app/upload/categories/$oldImageName")) {
                unlink("/app/upload/categories/$oldImageName");
            }

            return $fileName;
        };
    };
    return false;
}


/**
 * Undocumented function
 *
 * @param string $title
 * @param string $imageName
 * @return boolean
 */
function createCategorie(string $title, string $imageName): bool {
    global $db;
    try{
        $sqlStatement = $db->prepare("INSERT INTO categories(title, imageName) VALUE (:title, :imageName)");
        $sqlStatement->execute([
            'title' => $title,
            'imageName' => $imageName,
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
 * @param string $title
 * @param string|null $imageName
 * @return boolean
 */
function updateCategorie(int $id, string $title, ?string $imageName): bool {
    global $db;
    try{
        $query = "UPDATE categories SET title = :title";
        $params = [
            'id' => $id,
            'title' => $title,
        ];
        if($imageName){
            $query .= ", imageName = :imageName";
            $params['imageName'] = $imageName;
        };

        $query .= " WHERE id = :id";

        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);

    }
    catch(PDOException $error){
        return false;
    }
    return true;

}

function deleteCategorie(int $id): bool {
    global $db;
    try{
        $sqlStatement = $db->prepare("DELETE FROM categories WHERE id= :id");
        $sqlStatement->execute([
            'id' => $id,
        ]);

    }
    catch(PDOException $error){
        return false;
}
return true;
}
<?php

try {
    $db = new PDO(
     "mysql:host=dataBase;dbname=data_site;charset=utf8mb4",
      'root',
      null,
      [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => 0,]
);
}
catch(PDOException $error) {
    echo $error ->getMessage();
    die();
}

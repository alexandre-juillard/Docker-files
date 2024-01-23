<?php

// $prenom = "Pierre";
// $nom = "Bertrand";

// $nomComplet = $prenom . " " . $nom;
// $nomComplet = "$prenom $nom";

// echo $nomComplet;

// $num1 = 10;
// $num2 =  20;

// $resultat = $num1 + $num2;

// echo $resultat;

// $num1 = 10;

// $num1 += 20;

// $age = 11;

// if ($age >= 18) {
//     echo "Vous êtes majeur";
// } else if ($age >= 12) {
//     echo 'Vous êtes ado';
// } else {
//     echo "Vous êtes mineur";
// }

// echo $age >= 18 ? 'Vous êtes majeur' : 'Vous êtes mineur';

// phpinfo();

// $isAutorise = true;
// $isProprietaire = true;

// if (!$isAutorise && $isProprietaire) {
//     echo 'Autorisé';
// } else {
//     echo 'Non autorisé';
// }

$age = 26;

// if ($age >= 18) {
//     echo '<h1>Vous êtes majeur</h1>';
// } else {
//     echo '<h1>Vous êtes mineur</h1>';
// }

// $users = ['Pierre', 'Paul', 'Jacques'];

// echo $users[0];

// foreach ($users as $user) {
//     echo $user;
// }

// $user1 = ['Pierre', 'Bertrand', 26];
// $user2 = ['Paul', 'Dupond', 46];
// $user3  = ['Jacques', 'Dupont', 19];

// $users =  [$user1, $user2, $user3];

//echo $users[1][1];

// $user1 = [
//     'prenom' => 'Pierre',
//     'nom' => 'Bertrand',
//     'age' => 26,
// ];

// echo array_key_exists('prenom', $user1);

// echo in_array('Pierre', $user1);

// echo array_search('Pierre',  $user1);

$users = [
    [
        "prenom" => "Pierre",
        "nom" => 'Bertrand',
        "age" => 24,
        "actif" => true,
    ],
    [
        "prenom" => "Paul",
        "nom" => 'Dupont',
        "age" => 33,
        "actif" => false,
    ]
];

// foreach
foreach ($users as $user) {
    if ($user['actif']) {
        echo  "$user[prenom] $user[nom]";
    }
}

// function bonjour(): string
// {
//     return 'Hello';
// }

// echo bonjour();

function addition(float $val1, float $val2): float
{
    return $val1 + $val2;
}

echo addition(10);

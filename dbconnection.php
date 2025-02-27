<?php
function dbconnection() {
    // Paramètres de connexion
    $host = "localhost";
    $dbname = "webbd";
    $username = "root";
    $password = "";

    try {
        // Connexion à la base de données avec encodage UTF-8
        $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        
        // Définir le mode d'erreur PDO pour générer des exceptions
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $con;
    } catch (PDOException $e) {
        // Retourner une erreur en format JSON
        echo json_encode([
            "status" => "failed",
            "message" => "Erreur de connexion à la base de données"
        ]);
        exit(); // Arrêter l'exécution du script
    }
}
?>

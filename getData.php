<?php
// Connexion à la base de données
include 'dbconnection.php'; // Assurez-vous que ce fichier contient votre logique de connexion à la base de données
$con = dbconnection(); // Connexion via PDO


try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit();
}

// Vérifier si l'email est passé en tant que paramètre dans l'URL
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Préparer la requête pour récupérer le nom de l'utilisateur
    $query = "SELECT name FROM client WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Renvoie le nom de l'utilisateur en format JSON
        echo json_encode(['name' => $user['name']]);
    } else {
        // Si l'utilisateur n'est pas trouvé, renvoyer un message d'erreur
        echo json_encode(['error' => 'Utilisateur non trouvé']);
    }
} else {
    // Si aucun email n'est fourni, renvoyer un message d'erreur
    echo json_encode(['error' => 'Email non fourni']);
}

// Fermer la connexion à la base de données
$con = null; // Fermeture de la connexion PDO
?>
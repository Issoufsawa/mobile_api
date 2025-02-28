<?php
// Inclure la connexion à la base de données
include 'dbconnection.php'; // Inclure le fichier de connexion

// Appel de la fonction de connexion à la base de données
$pdo = dbconnection(); // Connexion PDO à la base de données

// Vérifier si le numéro de compte est passé en tant que paramètre dans l'URL
if (isset($_GET['num_cpte_cli'])) {
    $num_cpte_cli = $_GET['num_cpte_cli'];

    // Préparer la requête avec une jointure entre la table clients et la table courants
    $query = "SELECT c.num_cpte_cli, cl.nom_cli
              FROM courants c
              INNER JOIN clients cl ON c.clients_id = cl.id
              WHERE c.num_cpte_cli = :num_cpte_cli"; // Ajout de la condition WHERE pour filtrer par num_cpte_cli
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':num_cpte_cli', $num_cpte_cli);
    $stmt->execute();

    // Vérifier si des résultats sont trouvés
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Renvoie les informations de l'utilisateur en format JSON
        echo json_encode([
            'num_cpte_cli' => $user['num_cpte_cli'], 
            'nom_cli' => $user['nom_cli'] // Ajout du nom du client
        ]);
    } else {
        // Si le numéro de compte n'est pas trouvé, renvoyer un message d'erreur
        echo json_encode(['error' => 'Utilisateur non trouvé']);
    }
} else {
    // Si aucun numéro de compte n'est fourni, renvoyer un message d'erreur
    echo json_encode(['error' => 'Numéro de compte non fourni']);
}

// Fermer la connexion à la base de données
$pdo = null; // Fermeture de la connexion PDO
?>

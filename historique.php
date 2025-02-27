<?php
// Inclure le fichier de connexion à la base de données
include 'dbconnection.php'; // Assurez-vous que ce fichier contient la fonction dbconnection()

// Appeler la fonction de connexion à la base de données
$pdo = dbconnection(); // Connexion PDO à la base de données

// Vérifier si le paramètre 'num_cpte_cli' est présent dans la requête GET
if (isset($_GET['num_cpte_cli'])) {
    $num_cpte_cli = $_GET['num_cpte_cli'];

    // Préparer la requête SQL avec une jointure et filtrer par num_cpte_cli
    $query = "
        SELECT
            c.num_cpte_cli,
            c.type_cpte_cli,
            c.solde_cpte_cli,
            m.code_mvt,
            m.type_mvt,
            m.mtnt_dep_mvt,
            m.createdat
        FROM
            mouvements m
        LEFT JOIN
            courants c ON m.courants_id = c.id
        WHERE
            c.num_cpte_cli = :num_cpte_cli
    ";

    // Préparer la requête
    $stmt = $pdo->prepare($query);

    // Lier le paramètre num_cpte_cli à la requête
    $stmt->bindParam(':num_cpte_cli', $num_cpte_cli, PDO::PARAM_STR);

    // Exécuter la requête
    $stmt->execute();

    // Vérifier si des résultats sont retournés
    if ($stmt->rowCount() > 0) {
        // Récupérer les résultats sous forme de tableau associatif
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les résultats au format JSON
        echo json_encode($results);
    } else {
        // Si aucun résultat n'est trouvé, retourner un message d'erreur
        echo json_encode(['error' => 'Aucune donnée trouvée pour ce numéro de compte']);
    }
} else {
    // Si aucun numéro de compte n'est fourni, renvoyer un message d'erreur
    echo json_encode(['error' => 'Numéro de compte non fourni']);
}

// Fermer la connexion à la base de données
$pdo = null;
?>

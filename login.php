<?php
// Connexion à la base de données
include 'dbconnection.php'; // Assurez-vous que ce fichier contient votre logique de connexion à la base de données
$con = dbconnection();


// Vérifiez si la méthode HTTP est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données envoyées par Flutter au format JSON
    $data = json_decode(file_get_contents("php://input"));

    // Vérification que les champs email et mot de passe sont présents
    $email = isset($data->email) ? $data->email : '';
    $password = isset($data->password) ? $data->password : '';

    // Validation des champs
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Veuillez fournir un email et un mot de passe."]);
        exit();
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Format d'email invalide"]);
        exit();
    }

    // Requête pour récupérer l'utilisateur par email
    $stmt = $con->prepare("SELECT * FROM client WHERE email = ?");
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Erreur de préparation de la requête: " . $con->error]);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifiez si l'utilisateur existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Comparaison du mot de passe avec password_verify (si le mot de passe est haché)
        if (password_verify($password, $user['password'])) {
            // Connexion réussie
            echo json_encode([
                "success" => true,
                "message" => "Connexion réussie",
                "data" => [
                    "id" => $user['id'],
                    "email" => $user['email'],
                    "name" => $user['name'],
                    "password" => $user['password'],
                    "password1" => $password,     
                ]
            ]);
          

        } else {
            // Mot de passe incorrect
            echo json_encode(["success" => false, "message" => "Mot de passe incorrect"]);
            
        }
    } else {
        // Email non trouvé
        echo json_encode(["success" => false, "message" => "Email non trouvé"]);
    }

    // Fermer la déclaration préparée
    $stmt->close();
} else {
    // Méthode HTTP non autorisée
    echo json_encode(["success" => false, "message" => "Méthode HTTP non autorisée"]);
}

// Fermer la connexion à la base de données
$con->close();
?>

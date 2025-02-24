<?php
include 'dbconnection.php';
header('Content-Type: application/json; charset=UTF-8');

// Connexion à la base de données
$con = dbconnection();

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'failed', 'message' => 'Invalid request method']);
    exit;
}

// Vérifier que toutes les données sont présentes
if (!isset($_POST['name'], $_POST['prename'], $_POST['email'], $_POST['password'])) {
    echo json_encode(['status' => 'failed', 'message' => 'All fields are required']);
    exit;
}

// Nettoyage des données
$name = trim($_POST['name']);
$prename = trim($_POST['prename']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];

if (!$email) {
    echo json_encode(['status' => 'failed', 'message' => 'Invalid email format']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['status' => 'failed', 'message' => 'Password must be at least 6 characters']);
    exit;
}

// Vérifier si l'email existe déjà
$queryCheck = "SELECT COUNT(*) FROM client WHERE email = :email";
$stmtCheck = $con->prepare($queryCheck);
$stmtCheck->bindParam(':email', $_POST['email']);
$stmtCheck->execute();
if ($stmtCheck->fetchColumn() > 0) {
    echo json_encode(['status' => 'failed', 'message' => 'Email already exists']);
    exit;
}

// Hachage du mot de passe
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insertion des données
$queryInsert = "INSERT INTO client (name, prename, email, password) VALUES (:name, :prename, :email, :password)";

try {
    $stmt = $con->prepare($queryInsert);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':prename', $prename);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
    } else {
        echo json_encode(['status' => 'failed', 'message' => 'Data not inserted']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log les erreurs
    echo json_encode(['status' => 'failed', 'message' => 'An error occurred, please try again later']);
}
?>
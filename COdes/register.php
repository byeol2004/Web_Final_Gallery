
<?php
include('db.php');

// Add CORS headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');  // Ensure the content type is JSON

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
            $username = $conn->real_escape_string($data['username']);
            $email = $conn->real_escape_string($data['email']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Registration successful'
                ]);
            } else {
                // Return SQL error in JSON
                echo json_encode([
                    'success' => false,
                    'message' => 'Error: ' . $conn->error
                ]);
            }
        } else {
            // Return missing fields error
            echo json_encode([
                'success' => false,
                'message' => 'Invalid input. Please fill all the required fields.'
            ]);
        }
    } else {
        // Invalid request method
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method.'
        ]);
    }
} catch (Exception $e) {
    // In case of exception, return a JSON error message
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

// Disable display errors to avoid HTML output in JSON response
ini_set('display_errors', 0);
ini_set('log_errors', 1);


?>


<?php
// CORS headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *");  // This allows all origins, but you can restrict it to specific domains if needed
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  // Allows POST, GET, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Allow specific headers like Content-Type

include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['username']) && isset($data['password'])) {
        $username = $conn->real_escape_string($data['username']);
        $password = $data['password'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo json_encode([
                'success' => true,
                'message' => 'Login successful'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Please provide both username and password.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>

<?php
// Improved delete-asset.php script
header('Content-Type: application/json');

// Database connection
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "titan2";

try {
    // Create PDO connection with error handling
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Send error response if connection fails
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}

// Handle different request methods
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || 
    (isset($_POST['_method']) && strtoupper($_POST['_method']) === 'DELETE')) {
    try {
        // Validate input
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if ($id === false || $id === null) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'error' => 'Invalid asset ID'
            ]);
            exit;
        }

        // Begin transaction for more robust deletion
        $conn->beginTransaction();

        // Optional: Delete related records first if needed
        // Example: Delete associated records in other tables
        // $stmt = $conn->prepare("DELETE FROM related_table WHERE AssetID = ?");
        // $stmt->execute([$id]);

        // Delete the asset
        $stmt = $conn->prepare("DELETE FROM assets WHERE AssetID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Check if deletion was successful
        if ($stmt->rowCount() > 0) {
            // Commit the transaction
            $conn->commit();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Asset deleted successfully'
            ]);
        } else {
            // Rollback the transaction
            $conn->rollBack();
            
            http_response_code(404);
            echo json_encode([
                'success' => false, 
                'error' => 'Asset not found'
            ]);
        }
    } catch(PDOException $e) {
        // Rollback the transaction in case of error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

        // Send error response
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'error' => 'Delete failed: ' . $e->getMessage()
        ]);
    }
} else {
    // Handle incorrect request method
    http_response_code(405);
    echo json_encode([
        'success' => false, 
        'error' => 'Method Not Allowed'
    ]);
}
?>
<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to save results']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO mood_results (user_id, overall_score, feeling_score, sleep_score, energy_score, stress_score, social_score, recommendations) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $data['overallScore'],
            $data['feelingScore'],
            $data['sleepScore'],
            $data['energyScore'],
            $data['stressScore'],
            $data['socialScore'],
            $data['recommendations']
        ]);

        echo json_encode(['success' => true, 'message' => 'Results saved successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to save results']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 
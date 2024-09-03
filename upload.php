<?php
header('Content-Type: application/json');

$uploadDir = 'uploaded_photos/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['image'])) {
    $imageData = $data['image'];
    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    $fileName = uniqid() . '.jpg';
    $filePath = $uploadDir . $fileName;

    if (file_put_contents($filePath, $imageData)) {
        list($width, $height) = getimagesize($filePath);

        $response = [
            'url' => 'https://YourUrl/' . $filePath,
            'width' => $width,
            'height' => $height
        ];
        echo json_encode($response);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save the photo.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
}
?>

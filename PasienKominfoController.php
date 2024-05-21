<?php

// Check if 'no_rm' is set in the POST data
if (isset($_POST['no_rm'])) {
    // Extract 'no_rm' value from POST data
    $no_rm = $_POST['no_rm'];

    // URL tujuan
    $url = 'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien';

    // Data POST
    $data = array(
        'username' => '3301010509940003',
        'password' => 'banyumas',
        'no_rm' => $no_rm, // Use the dynamic 'no_rm' value
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    // Check if the response is valid JSON
    $jsonData = json_decode($response);

    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
        // If the response is not valid JSON, handle the error accordingly
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid JSON response']);
    } else {
        // If the response is valid JSON, return it
        header('Content-Type: application/json');
        echo $response;
    }
} else {
    // If 'no_rm' is not set, return an error response
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No "no_rm" parameter provided']);
}

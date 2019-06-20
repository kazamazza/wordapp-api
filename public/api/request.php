<?php

require_once("DatabaseHelper.php");
require_once("WebContentHelper.php");

/**
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 * **/
header('Content-Type: application/json');
$response = ["message" => "success"];

//$url = $_GET['link'];
$url = 'https://en.wikipedia.org/wiki/Mobile_operating_system';
$db = DatabaseHelper::instance();

// Check connection
if ($db->conn->connect_error) {
    $response = ["message" => "failed to connect to db"];
    die("Connection failed: " . $db->conn->connect_error);
} else {
   // echo "connected to database";
}

$url_exists = check_url($url,$db);

function check_url($url,$db)  {
    $url_check = "SELECT url_id FROM urls WHERE name='$url'";
    $result_url = $db->conn->query($url_check);
    $num_urls = mysqli_num_rows($result_url);

    return $num_urls;
}

if ($url_exists == 0) {
    $query = "INSERT INTO urls (name) VALUES ('$url')";
    if ($db->conn->query($query) === true) {

        $last_id = $db->conn->insert_id;

    }
    $content_helper = new WebContentHelper();
    $returned_content = $content_helper->get_data($url);
    $words = $content_helper->get_words($returned_content);
    $array = explode(' ',$words);
    $lower = array_map('strtolower',$array);

    $new_array  = array_count_values($lower);
    $stmt = $db->conn->prepare("INSERT INTO words (word, num, url_id) VALUES (?, ?, ?)");

foreach ($new_array as $key => $value) {
    if (!empty($key)) {
        $stmt->bind_param("sdd", $key, $value, $last_id);
        $stmt->execute();
        }
    }
    $stmt->close();
    $db->conn->close();
}
else {
    $response = ["message" => "url already crawled"];
}

echo json_encode($response);
exit;

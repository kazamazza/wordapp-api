<?php

require_once("DatabaseHelper.php");

header('Content-Type: application/json');

$db = DatabaseHelper::instance();

$type = $_GET['type'];

switch ($type) {
    case 'every10': {
        $sql = "SELECT * 
    FROM ( 
    SELECT 
        @row := @row +1 AS rownum, word 
    FROM ( 
        SELECT @row :=0) r, words 
    ) ranked 
    WHERE rownum % 10 = 1";
        $result =  $db->conn->query($sql);
        $values = mysqli_fetch_array($result);
        $rows = [];

        while($row = $result->fetch_array())
        {
            $entry["word"] = $row["word"];
            $entry["num"] = "";
            $rows[] = $entry;
        }

        $response = ["results" => $rows,"total" => "0"];
        echo json_encode($response);
        break;
    }
    case '10': {
        $sql = "SELECT word FROM words LIMIT 9, 1";
        $result =  $db->conn->query($sql);
        $values = mysqli_fetch_array($result);
        $response = ["word" => $values['word']];
        echo json_encode($response);
        break;
    }

    case 'all': {
        $sql = "SELECT SUM(num) AS total FROM words";
        $result =  $db->conn->query($sql);
        $values = mysqli_fetch_array($result);
        $total = $values['total'];

        $sql = "SELECT word,num FROM words ORDER BY num DESC";
        $result =  $db->conn->query($sql);
        $values = mysqli_fetch_array($result);
        $rows = [];
        while($row = $result->fetch_array())
        {
            $entry["word"] = $row["word"];
            $entry["num"] =  $row["num"];
            $rows[] = $entry;
        }
        $response = ["results" => $rows,"total" => $total];

        echo json_encode($response);

        break;
    }
        $result->close();
}

$db->conn->close();


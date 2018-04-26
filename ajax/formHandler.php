<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Request.php');

$myDatabase = new Database();

$getParam = $_GET['param'];
$postParam = $_POST['param'];
$querys = array();
if (isset($getParam['code'])) {
    switch ($getParam['code']) {
        case 'get_seances':
            $sql = 'SELECT cast(cast(s.start_time as time) as char(5)) AS start_time, f.name, s.id 
              FROM seance s 
              LEFT JOIN film f  
              ON s.film_id = f.id
              WHERE DATE(s.start_time) = "' . $getParam['date'] . '";';
            $myRequest = new Request($myDatabase);
            $rows = $myRequest->select($sql);
            echo(json_encode($rows));
            break;
        case 'get_seance_info':
            $sql = 'SELECT (s.max_tickets_count - COUNT(t.id)) AS remaining_tickets, s.price
                FROM seance s
                LEFT JOIN ticket t
                ON t.seance_id = s.id
                WHERE s.id = "' . $getParam['id'] . '";';
            $myReport = new Request($myDatabase);
            $rows = $myReport->select($sql);
            echo(json_encode($rows));
            break;
    }
}
if (isset($postParam['code'])) {
    switch ($postParam['code']) {
        case 'buy':
            $sql = "INSERT INTO ticket (seance_id) VALUES ({$postParam['seance_id']});";
            $myRequest = new Request($myDatabase);
            for ($i = 0; $i < $postParam['count']; $i++) {
                $rows = $myRequest->insert($sql);
            }
            echo(json_encode($rows));
            break;
    }
}
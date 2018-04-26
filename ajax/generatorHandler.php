<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Request.php');

$postParam = $_POST['param'];
if (isset($postParam['code'])) {
    switch ($postParam['code']) {
        case 'addFilm':
            $insertFilmScript = "INSERT INTO `film` (name, duration) VALUES ('{$postParam['name']}', '{$postParam['duration']}');";
            $myDatabase = new Database();
            $myRequest = new Request($myDatabase);
            $rows = $myRequest->insert($insertFilmScript);
            echo(json_encode($insertFilmScript));
            break;
        case 'generate':
            generateShedule();
            generateTickets();
            echo('success');
            break;
    }
}

function generateShedule()
{
    $now = date("Y-m-d H:i:s");
    $lastFilmStartTime = date("Y-m") . "-01 12:00:00";
    $end = date("Y-m-d H:i:s", strtotime("+10 day", strtotime($now)));
    $myDatabase = new Database();
    $myRequest = new Request($myDatabase);
    $films = $myRequest->select('SELECT * FROM film;');
    $filmsCount = count($films);
    $insertSeanceScript = "INSERT INTO `seance` (film_id, start_time, price, max_tickets_count) 
                      VALUES ('{$films[0]['id']}', '{$lastFilmStartTime}', '500', '10')";
    while ($lastFilmStartTime < $end) {
        $filmNumber = rand(0, $filmsCount - 1);
        $film = $films[$filmNumber];
        $break = rand(-5, 40);
        $maxTicketsCount = rand(0, 10);
        $price = rand(100, 800);
        $lastFilmEndTime = date("Y-m-d H:i:s", strtotime("+{$film['duration']} minutes", strtotime($lastFilmStartTime)));
        $currentFilmStartTime = date("Y-m-d H:i:s", strtotime("+{$break} minutes", strtotime($lastFilmEndTime)));
        $insertSeanceScriptPart = ",('{$film['id']}', '{$currentFilmStartTime}', '{$price}', '{$maxTicketsCount}')";
        $insertSeanceScript .= $insertSeanceScriptPart;
        $lastFilmStartTime = $currentFilmStartTime;
    }
    $myRequest->insert($insertSeanceScript);
}

function generateTickets(){
    $myDatabase = new Database();
    $myRequest = new Request($myDatabase);
    $seances = $myRequest->select('SELECT id, max_tickets_count FROM seance;');
    $insertTicketScript = "INSERT INTO `ticket` (seance_id) VALUES ('{$seances[0]['id']}')";
    foreach($seances as $seance) {
        $solvedTickets = rand(0, $seance['max_tickets_count']);
        for ($i = 1; $i < $solvedTickets; $i++) {
            $insertTicketScript .= ", ('{$seance['id']}')";
        }
    }
    $myRequest->insert($insertTicketScript);
}
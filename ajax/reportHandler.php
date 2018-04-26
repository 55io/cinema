<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Request.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/Report.php');

const REPORTS = [1 => ["description" =>
    "Отчет показывает ошибки в расписании (фильмы накладываются друг на друга), отсортированные по возрастанию времени.
Выводить надо колонки «фильм 1», «время начала», «длительность», «фильм 2», «время начала», «длительность»."
    , "sql" =>
        "SELECT s1.name as first_film_name, s1.start_time as first_film_start_time, s1.duration as first_film_duration,
s2.name as second_film_name, s2.start_time as second_film_start_time, s2.duration as second_film_duration
  FROM (SELECT 
        f.name, f.duration, s.start_time
        FROM seance s 
        LEFT JOIN film f 
        ON s.film_id = f.id  
  ) AS s1 ,
    (SELECT 
        f.name, f.duration, s.start_tim
        FROM seance s 
        LEFT JOIN film f 
        ON s.film_id = f.id  
  ) AS s2
  WHERE s2.start_time < (s1.start_time + INTERVAL s1.duration MINUTE)
  AND s2.start_time > s1.start_time
  ORDER BY s1.start_time ASC"],
    2 => ["description" =>
        "Отчет показывает перерывы больше или равные 30 минут между фильмами, выводятся по уменьшению длительности перерыва.
Выводить надо колонки «фильм 1», «время начала», «длительность», «время начала второго фильма», «длительность перерыва»."
        , "sql" => "SELECT name, start_time, duration, next_film_start_time,
@i := TIMEDIFF(next_film_start_time, start_time + INTERVAL duration MINUTE) AS film_interval
FROM (SELECT  shedule.id, shedule.name, shedule.duration, shedule.start_time,
   LEAD(shedule.start_time) OVER(ORDER BY start_time) next_film_start_time
   FROM (SELECT s.id, s.start_time, f.name, f.duration
        FROM seance s
        LEFT JOIN film f 
        ON s.film_id = f.id  
        ORDER BY s.start_time) as shedule
   ORDER BY shedule.start_time
) AS film_list_with_next
WHERE TIMEDIFF(next_film_start_time, start_time + INTERVAL duration MINUTE) >= '00:30:00'
ORDER BY film_interval DESC "],
    3 => ["description" => "Отчет показывает список фильмов, для каждого указано общее число посетителей за все время, среднее число зрителей за сеанс 
		и общая сумма сбора по каждому, отсортированные по убыванию прибыли.
		Внизу таблицы должна быть строчка «итого», содержащая данные по всем фильмам сразу."
        , "sql" => "SELECT
  IF(ISNULL(f.name), 'Total', f.name) name,
  COUNT(*) AS total_all_time,
  COUNT(*) / COUNT(DISTINCT b.seance_id) AS avg_buyed,
  SUM(s.price) AS total_price
FROM seance s
JOIN ticket b ON s.id = b.seance_id
LEFT JOIN film f  ON s.film_id = f.id
GROUP BY f.name WITH ROLLUP"],
    4 => ["description" => "Отчет показывает число посетителей и кассовые сборы, 
		сгруппированные по времени начала фильма: с 9 до 15, с 15 до 18, с 18 до 21, с 21 до 00:00.
		(то есть сколько посетителей пришло с 9 до 15 часов, сколько с 15 до 18 и т.д.)."

        , "sql" => "SELECT CASE 
WHEN HOUR(s.start_time) BETWEEN 9 AND 14 THEN '9-15'
WHEN HOUR(s.start_time) BETWEEN 15 AND 17 THEN '15-18'
WHEN HOUR(s.start_time) BETWEEN 18 AND 20 THEN '18-21'
WHEN HOUR(s.start_time) BETWEEN 21 AND 23 THEN '18-00'
END as time_interrval,
  COUNT(*) AS total_all_time,
  SUM(s.price) AS total_price
FROM seance s
JOIN ticket b ON s.id = b.seance_id
GROUP BY time_interrval"]];

$myDatabase = new Database();
$param = $_GET['param'];

if (isset($param)) {
    $myReport = new Report($myDatabase, REPORTS[$param['reportNumber']]['description'], REPORTS[$param['reportNumber']]['sql']);
    $rows = $myReport->generate();
    echo(json_encode($rows));
}
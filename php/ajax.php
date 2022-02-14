<?php

if (count($_POST) == 0) {
    die();
}

$fn = $_POST["fn"];
$fn();

function fetchInfo()
{
    require_once('../vendor/autoload.php');
    $client = new \GuzzleHttp\Client();

    $city = $_POST["city"];

    $response = $client->request('GET', 'https://api.foursquare.com/v3/places/search?near=' . $city . "&limit=50", [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'fsq3LkeOtBb+z+THR78luhNwqS2uTUQQVCWLr0VvKqwOe2U=',
        ],
    ]);

    echo $response->getBody();

    //echo $response;
}

function fetchWeather()
{
    $city = $_POST["city"];

    $url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=bf5435aa4bb3711487a438d65379ea5e";
    $response = file_get_contents($url);

    echo $response;
}

<?php
function randomAPI()
{
    $data = file_get_contents("config.json");
    $json = json_decode($data);
    $payload = array('jsonrpc' => '2.0',
                    'method' => 'generateIntegers',
                    'params' => array('apiKey' => $json->random_key,
                                        'n' => 1,
                                        'min' => 0,
                                        'max' => 1000,
                                        'replacement' => true),
                    'id' => 42);
    $payload = json_encode($payload);
    $ch = curl_init('https://api.random.org/json-rpc/2/invoke');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $start_time = microtime(true);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $latency = number_format(microtime(true) - $start_time, 10);
    $result = json_decode($result);
    $info = 'Random, ' . $latency . ', ' . $httpcode;
    file_put_contents('logs.txt', $info.PHP_EOL , FILE_APPEND | LOCK_EX);
    if(array_key_exists("error", $result))
        return array('Error ' . $result->error->code, $info);
    else
        return array($result->result->random->data[0], $info);
}

function photosAPI()
{
    $start_time = microtime(true);
    $ch = curl_init('http://www.splashbase.co/api/v1/images/random');
    #$data = file_get_contents("config.json");
    #$json = json_decode($data);
    #$ch = curl_init('https://api.unsplash.com/photos/random?client_id=' . $json->unsplash_client_id);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $latency = number_format(microtime(true) - $start_time, 10);
    $info = 'Photos, ' . $latency . ', ' . $httpcode;
    $data = json_decode($result);
    file_put_contents('logs.txt', $info.PHP_EOL , FILE_APPEND | LOCK_EX);
    if($data != null)
    #    return array($data->urls->small, $info);
        return array($data->url, $info);
    return array('https://http.cat/404', $info);
}

function telegramAPI($photo, $number)
{
    $data = file_get_contents("config.json");
    $json = json_decode($data);
    $start_time = microtime(true);
    $ch = curl_init('https://api.telegram.org/bot' . $json->telegram_key . '/sendMessage?chat_id=' . $json->telegram_chat_id . '&text=Numarul%20' . $number . '%20are%20poza%20' . $photo . '.');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $latency = number_format(microtime(true) - $start_time, 10);
    $data = json_decode($result);
    $info = 'Telegram, ' . $latency . ', ' . $httpcode;
    file_put_contents('logs.txt', $info.PHP_EOL , FILE_APPEND | LOCK_EX);
    return $info;
}
?>
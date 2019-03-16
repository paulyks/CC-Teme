<?php
$data = file_get_contents("config.json");
$config = json_decode($data);

$connection = mysqli_connect($config->db_server,$config->db_name,$config->db_password,$config->db_name);
if(!$connection) 
    die("<br>Connection failed");
    
function executeQuery($query) {
    global $connection;
    $result = mysqli_query($connection,$query);
    if(!$result)
    {
        http_response_code(500); //Internal error
        die();
    }
}
function executeQueryWithResult($query) {
    global $connection;
    mysqli_set_charset($connection,"utf8");
    $result = mysqli_query($connection,$query);
    if(!$result)
    {
        http_response_code(500); //Internal error
        die();
    }
    return $result;
}
?>
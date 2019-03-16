<?php
include 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/'));
$putfp = fopen('php://input', 'r');
$put = '';
while($data = fread($putfp, 1024))
    $put .= $data;
fclose($putfp);
$put = explode("=", $put);

switch($method) 
{
    case "GET":
        switch(count($request))
        {
            case 2:
                switch($request[0])
                {
                    case "users":
                        getUser($request[1]);
                        break;
                    case "activities":
                        getActivity($request[1]);
                        break;
                    default:
                        http_response_code(400); //Bad request
                        die();
                }
                break;
            case 3:
                getActivities($request[0], $request[1], $request[2]);
                break;
            case 4:
                getActivities($request[0], $request[1], $request[2], $request[3]);
                break;
            default:
                http_response_code(400); //Bad request;
                die();
        }
        break;
    case "POST":
        switch(count($request))
        {
            case 1:
                addUser($request[0]);
                break;
            case 2:
                addActivity($request[0], $request[1]);
                break;
            default:
                http_response_code(400); //Bad request
                die();
        }
        break;
    case "PUT":
        switch(count($request))
        {
            case 2:
                switch(count($put))
                {
                    case 2: //Name=data
                        editUser($request[0], $request[1], $put);
                        break;
                    case 4: //Name=data&Descriere=data&Date=data
                        editActivity($request[0], $request[1], $put);
                        break;
                    default:
                        http_response_code(400); //Bad request
                        die();
                }
                break;
            default:
                http_response_code(400); //Bad request
                die();
        }
        break;
    case "DELETE":
        switch(count($request))
        {
            case 2:
                switch($request[0])
                {
                    case "activities":
                        deleteActivity($request[1]);
                        break;
                    case "users":
                        deleteUser($request[1]);
                        break;
                    default:
                        http_response_code(400);
                        die();
                }
                break;
            default:
                http_response_code(400); //Bad request
                die();
        }
        break;
    default:
        http_response_code(400); //Bad request
        die();  
}
?>
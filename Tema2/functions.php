<?php include "db.php"?>
<?php

function deleteUser($id)
{
    if($id != (int)$id)
    {
        http_response_code(400); //Bad request
        die();
    }
    $result = executeQueryWithResult("SELECT 1 FROM `users` WHERE `ID` = $id");
    if(!($row = $result->fetch_assoc()))
    {
        http_response_code(404); //Not found
        die();
    }
    executeQuery("DELETE FROM `users` WHERE `ID` = $id");
    executeQuery("DELETE FROM `calendar` WHERE `Activitate` IN (SELECT `ID` FROM `activities` WHERE `Organizator` = $id)");
    executeQuery("DELETE FROM `activities` WHERE `Organizator` = $id");
}

function deleteActivity($id)
{
    if($id != (int)$id)
    {
        http_response_code(400); //Bad request
        die();
    }
    $result = executeQueryWithResult("SELECT 1 FROM `activities` WHERE `ID` = $id");
    if(!($row = $result->fetch_assoc()))
    {
        http_response_code(404); //Not found
        die();
    }
    executeQuery("DELETE FROM `activities` WHERE `ID` = $id");
    executeQuery("DELETE FROM `calendar` WHERE `Activitate` = $id");
}

function editActivity($activities, $id, $putdata)
{
    $name = explode("&", $putdata[1])[0];
    $descriere = explode("&", $putdata[2])[0];
    $date = $putdata[3];
    if($id != (int)$id || $putdata[0] != "Name" || explode("&", $putdata[1])[1] != "Descriere" || explode("&", $putdata[2])[1] != "Date")
    {
        http_response_code(400); //Bad request
        die();
    }
    if(strlen($name) < 3 || strlen($descriere) < 5 )
    {
        echo "Name or description is too short";
        http_response_code(415); //Unsupported media type
        die();
    }
    $date = urldecode($date);
    if(!is_int(strtotime($date)))
    {
        echo "Time format is incorrect";
        http_response_code(415); //Unsupported media type
        die();
    }
    $result = executeQueryWithResult("SELECT 1 FROM `activities` WHERE `Name` = '$name' AND `Descriere` = '$descriere'");
    if(($row = $result->fetch_assoc()))
    {
        http_response_code(409); //Conflict
        die();
    }
    $result = executeQueryWithResult("SELECT 1 FROM `activities` WHERE `ID` = $id");
    if(!($row = $result->fetch_assoc()))
    {
        http_response_code(404); //Not found
        die();
    }
    executeQuery("UPDATE `activities` SET `Name` = '$name', `Descriere` = '$descriere' WHERE `ID` = $id");
    executeQuery("UPDATE `calendar` SET `Date` = '$date' WHERE `Activitate` = $id");
}

function editUser($users, $id, $putdata)
{
    if($id != (int)$id || $users != "users" || $putdata[0] != "Name")
    {
        http_response_code(400); //Bad request
        die();
    }
    $name = $putdata[1];
    if(strlen($name) < 3)
    {
        echo "Name should be longer than 3 characters.";
        http_response_code(415); //Unsupported media type
        die();
    }
    $result = executeQueryWithResult("SELECT 1 FROM `users` WHERE `Name` = '$name'");
    if(($row = $result->fetch_row()))
    {
        http_response_code(409); //Conflict
        die();
    }
    executeQuery("UPDATE `users` SET `Name`='$name' WHERE `ID` = $id");
    http_response_code(202); //No content
    die();
}

function addActivity($activities, $id)
{
    if($id != (int)$id || $activities != "activities" || !isset($_POST["Name"]) || 
        !isset($_POST["Descriere"]) || !isset($_POST["Date"]))
    {
        http_response_code(400); //Bad request
        die();
    }
    $result = executeQueryWithResult("SELECT 1 FROM `users` WHERE `ID` = $id");
    if(!($row = $result->fetch_row()))
    {
        http_response_code(404); //Not found
        die();
    }
    if(!is_int(strtotime($_POST["Date"])))
    {
        echo "Time format is incorrect";
        http_response_code(415); //Unsupported media type
        die();
    }
    $name = $_POST['Name'];
    $descriere = $_POST['Descriere'];
    $date = $_POST['Date'];
    $result = executeQueryWithResult("SELECT ID FROM `activities` 
        WHERE `Organizator` = $id AND `Name` = '$name' AND `Descriere` = '$descriere'");
    if(($row = $result->fetch_row()))
    {
        http_response_code(409); //Conflict
        die();
    }
    executeQuery("INSERT INTO `activities`(`ID`, `Name`, `Descriere`, `Organizator`) 
    VALUES(NULL, '$name', '$descriere', $id)");
    $result = executeQueryWithResult("SELECT ID FROM `activities` 
        WHERE `Organizator` = $id AND `Name` = '$name' AND `Descriere` = '$descriere'");
    $row = $result->fetch_assoc();
    $activity_id = $row['ID'];
    executeQuery("INSERT INTO `calendar`(`ID`, `Date`, `Activitate`)
        VALUES(NULL, '$date', $activity_id)");
    http_response_code(201); //Created
}

function addUser($users)
{
    if(!isset($_POST["Name"]) || $users != "users")
    {
        http_response_code(404); //Bad request
        die();
    }
    $name = $_POST["Name"];
    $result = executeQueryWithResult("SELECT 1 FROM `users` WHERE `Name` = '$name'");
    if($row = $result->fetch_row())
    {
        http_response_code(409); //Conflict
        die();
    }
    executeQuery("INSERT INTO `users`(`ID`, `Name`) VALUES(NULL, '$name')");
    http_response_code(201); //Created
}

function getActivities($users, $id, $activities, $list = "")
{
    $users = urlencode($users);
    $id = urlencode($id);
    $activities = urlencode($activities);
    if($id != (int)$id || $users != "users" || $activities != "activities")
    {
        http_response_code(400); //Bad request
        die();
    }
    if($list == "")
        $result = executeQueryWithResult("SELECT `ID`, `Name`, `Descriere`, `Organizator` 
                                            FROM `activities` WHERE `activities`.Organizator = $id");
    else
    {
        if($list == "list")
            $result = executeQueryWithResult("SELECT `activities`.`ID`, `activities`.`Name`, `activities`.`Descriere`, 
                                            `activities`.`Organizator`, `calendar`.`Date` 
                                            FROM `activities`, `calendar`, `users` 
                                            WHERE `activities`.`Organizator` = `users`.`ID` 
                                            AND `activities`.`ID` = `calendar`.`Activitate` 
                                            AND `users`.`ID` = $id"); 
        else
        {
            http_response_code(400); //Bad request
            die();
        }
    }
    $json_data = array();
    while($row = $result->fetch_assoc())
    {
        $json_array['ID'] = $row['ID'];
        $json_array['Name'] = $row['Name'];
        $json_array['Descriere'] = $row['Descriere'];
        $json_array['Organizator'] = $row['Organizator'];
        if(array_key_exists('Date', $row))
            $json_array['Date'] = $row['Date'];
        array_push($json_data, $json_array);
    }
    if(count($json_data) == 0)
    {
        http_response_code(204); //No Content
        die();
    }
    echo json_encode($json_data);
}

function getActivity($id)
{
    if($id != (int)$id)
    {
        http_response_code(400); //Bad request
        die();
    }
    $result = executeQueryWithResult("SELECT `activities`.`Name`, `activities`.`Descriere`, 
                                    `activities`.`Organizator`, `calendar`.`Date` 
                                    FROM `activities`, `calendar` 
                                    WHERE `activities`.`ID` = `calendar`.`Activitate` AND 
                                    `activities`.`ID` = $id");
    $json_data = array();
    while($row = $result->fetch_assoc())
    {
        $json_array['Name'] = $row['Name'];
        $json_array['Descriere'] = $row['Descriere'];
        $json_array['Organizator'] = $row['Organizator'];
        $json_array['Date'] = $row['Date'];
        array_push($json_data, $json_array);
    }
    if(count($json_data) == 0)
    {
        http_response_code(404); //Not found
        die();
    }
    echo json_encode($json_data);
}

function getUser($id)
{
    if($id != (int)$id)
    {
        http_response_code(400); //Bad request
        die();
    }
    $result = executeQueryWithResult("SELECT `Name` FROM `users` WHERE `users`.`ID` = $id");
    $json_data = array();
    while($row = $result->fetch_assoc())
    {
        $json_array['Name'] = $row['Name'];
        array_push($json_data, $json_array);
    }
    if(count($json_data) == 0)
    {
        http_response_code(404); //Not found
        die();
    }
    echo json_encode($json_data);
}
?>
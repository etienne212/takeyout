<?php

    session_start();
    //TODO: uncomment the login check and also decide if I want to use session tokens or do SQL later on
    /*if (!isset($_SESSION['id']))
    {
        exit ("Error: Not logged in!");
    }

    $user = array(
        'ID' => $_SESSION['id'],
        'Name' => $_SESSION['name'],
        'PicURL' => $_SESSION['picUrl'],
    );*/

    //TODO:remove this later
    $id = 'N-NcRMZupE';

    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "J^mpStrt";
    $dbDatabase = "takeyout";

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM user WHERE id = '$id'";
    if ($result=mysqli_query($conn,$sql))
    {
        while ($row = mysqli_fetch_array($result))
        {
            $user = array(
                'name' => $row['name'],
                'headline' => $row['headline'],
                'city' => $row['city'],
                'country' => $row['country'],
                'picFull' => $row['picFull'],
                'bio' => $row['bio']
            );
        }
    }
    $jsonstring = json_encode($user);
    echo $jsonstring;

?>
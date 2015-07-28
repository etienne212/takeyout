<?php

    session_start();

    $id = $_SESSION["ID"];

    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "yourpasswordhere";
    $dbDatabase = "test";

    date_default_timezone_set("America/Tijuana");

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM Trip WHERE owner = '$id'";
    if ($result = mysqli_query($conn, $sql))
    {
        if (mysqli_num_rows($result))
        {
            while($row = mysqli_fetch_array ($result))
            {
                // Make sure that the planned trip does not overlap with anything currently planned trip
                if (!(($start < $row['start'] && $end < $row['start']) || ($start > $row['end'] && $end > $row['end'])))
                {
                    die("Error: cannot schedule trip due to overlap");
                }
            }
        }
    }

    $sql = "INSERT INTO Trip (id, country, city, owner, start, end)
                VALUES (NULL, '$country', '$city', '$id', '$start', '$end')";

    if (mysqli_query($conn, $sql))
    {
        echo "Successfully inserted";
    }
    else
    {
        echo "Error updating record: " . mysqli_error($conn);
    }

    $conn->close();

?>
<?php

parse_str($_SERVER['QUERY_STRING']);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://www.linkedin.com/uas/oauth2/accessToken");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "grant_type=authorization_code&" . "code=" . $code . "&" . "redirect_uri=http%3A%2F%2Fkawaiikrew.net%2Fwww%2Fphp%2Fauthcallback.php&" . "client_id=75d2ob10meoc3a&" . "client_secret=zQ5SUiuhMRDG0tpk");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

$result = json_decode($server_output, true);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL,'https://api.linkedin.com/v1/people/~:(id,location,formatted-name,industry,summary,specialties,positions,headline,picture-urls::(original),picture-url,interests,languages,skills,date-of-birth)?oauth2_access_token=' . $result['access_token'] . '&format=json');
//$curl = curl_init('https://api.linkedin.com/v1/people/~:(id,location,formatted-name,industry,summary,specialties,positions,headline,picture-urls::(original),picture-url,interests,languages,skills,date-of-birth)?oauth2_access_token=' . $result['access_token'] . '&format=json');
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

$result = curl_exec($curl);
$decoded = json_decode($result, true);

curl_close ($curl);

$dbHost = 'localhost';
$dbUser = "root";
$dbPass = "J^mpStrt";
$dbDatabase = "takeyout";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

echo $result;

if (isset($decoded['id']))
{
    $id = $decoded['id'];
}
if (isset($decoded['formattedName']))
{
    $name = $decoded['formattedName'];
}
if (isset($decoded['headline']))
{
    $headline = $decoded['headline'];
}
if (isset($decoded['pictureUrl']))
{
    $picThumbnail = $decoded['pictureUrl'];
}
if (isset($decoded['pictureUrls']))
{
    $urls = $decoded['pictureUrls'];
    $values = $urls['values'];
    $picFull = $values[0];
}
if (isset($decoded['industry']))
{
    $industry = $decoded['industry'];
}
$sql = "INSERT INTO user (id, name, headline, industry, city, country, picThumbnail, picFull) VALUES ('$id', '$name', '$headline', '$industry', NULL, NULL, '$picThumbnail', '$picFull')";
if ($conn->query($sql) === TRUE)
{
    //header("Location: ../chatwindow.html");
} else
{
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
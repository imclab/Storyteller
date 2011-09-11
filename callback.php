<?php
require("opendb.php");

$video = $_POST['video'];
$flv = $video['flv_url'];
$mp4 = $video['mp4_url'];
$small = $video['small_thumbnail_url'];
$medium = $video['medium_thumbnail_url'];
$large = $video['large_thumbnail_url'];

$query = "INSERT INTO stories VALUES ('', '$flv', '$mp4', '$small', '$medium', '$large')";
$result = mysql_query($query);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

?>

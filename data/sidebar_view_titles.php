<?php

$data = array();

$subtitle_arr = array();
array_push($subtitle_arr, "Subtitle 1_1");
array_push($subtitle_arr, "Subtitle 1_2");
array_push($subtitle_arr, "Subtitle 1_3");
$row = array();
$row['main_title'] = "Main title 1";
$row['sub_titles'] = $subtitle_arr;
array_push($data, $row);

//$subtitle_arr = array();
//array_push($subtitle_arr, "Subtitle 2_1");
//array_push($subtitle_arr, "Subtitle 2_2");
//array_push($subtitle_arr, "Subtitle 2_3");
//$row = array();
//$row['main_title'] = "Main title 2";
//$row['sub_titles'] = $subtitle_arr;
//array_push($data, $row);

echo json_encode($data);
?>
<?php

$data = array();
$data['titles'] = array();

$subtitle_arr = array();
array_push($subtitle_arr, "Subtitle 1");
array_push($subtitle_arr, "Subtitle 2");
array_push($subtitle_arr, "Subtitle 3");
$row = array();
$row['main_title'] = "Main title 1";
$row['sub_titles'] = $subtitle_arr;
array_push($data['titles'], $row);

$subtitle_arr = array();
array_push($subtitle_arr, "Subtitle 1");
array_push($subtitle_arr, "Subtitle 2");
array_push($subtitle_arr, "Subtitle 3");
$row = array();
$row['main_title'] = "Main title 2";
$row['sub_titles'] = $subtitle_arr;
array_push($data['titles'], $row);

echo json_encode($data);
?>
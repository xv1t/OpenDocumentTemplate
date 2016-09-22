<?php

require '../OpenDocumentTemplate.php';

$oo = new OpenDocumentTemplate();


$json_string = file_get_contents('continents.json');
$json_string = str_replace(
        array("\n", "\t"), array('', ''), $json_string);

$data = json_decode(
        $json_string, true);

$oo->open($argv[1], $argv[2], $data, array(
    'hide_draws' => array(
        'ImageNULL'
    )
));

<?php

require '../../OpenDocumentTemplate.php';

$od = new OpenDocumentTemplate();

$od->open('pictures.ods', 'pictures-out.ods', 'pictures.json', array(
    'with_image_dir' => 'img',
    'extract_content' => true
));

//print_r($od->meta);
//print_r($od->schema);

<?php

require '../../OpenDocumentTemplate.php';

$od = new OpenDocumentTemplate();

$od->open('pictures.ods', 'pictures-out.ods', 'pictures.json', array(
    'with_image_dir' => 'img'
));

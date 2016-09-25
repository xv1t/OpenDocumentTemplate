<?php

require '../../OpenDocumentTemplate.php';

$oo = new OpenDocumentTemplate();

$oo->open('continents.ods', 'continents-out.ods', 'continents.json', array(
    'hide_draws' => array(
        'ImageNULL'
    )
));

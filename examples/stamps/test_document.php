<?php

require '../../OpenDocumentTemplate.php';

$od = new OpenDocumentTemplate();

$od->open('document_bill.ods', 'document_bill-out.ods', 'documents.json', array(
    'with_image_dir' => 'img',
    'extract_content' => true
));

//print_r($od->meta);
//print_r($od->schema);

print_r($od->used_images);

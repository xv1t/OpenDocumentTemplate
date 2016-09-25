<?php

require '../../OpenDocumentTemplate.php';

$od = new OpenDocumentTemplate();

$od->open('document_bill.ods', 'document_bill-out.ods', 'documents.json', array(
    'with_image_dir' => 'img',
    'extract_content' => true,
    'dom_stay' => true
));

//print_r($od->meta);
$sheet = $od->dom->getElementsByTagName('table')->item(0);
$xpath = new DOMXPath($od->dom);

$items = $xpath->query("table:table-row/table:table-cell[@table:number-rows-spanned]", $sheet);

//print_r($items[0]);
//print_r($od->data);
//print_r($od->schema['named-range']);

//print_r($od->used_images);

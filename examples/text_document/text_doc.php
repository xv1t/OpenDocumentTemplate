<?php

require '../../OpenDocumentTemplate.php';
require './data.php';

$od = new OpenDocumentTemplate();

echo "Build report...\n";
$od->open('text_doc-template.odt', 'text_doc-out.odt', $data, array(
   // 'with_image_dir' => 'img',
  //  'extract_content' => true,
    'dom_stay' => true
));

$text = $od->dom->getElementsByTagName('text')->item(0);

$para = $text->getElementsByTagName('p');
print_r($para);

foreach ($para as $p){
    $textContent = $p->textContent;
    $childNodes = $p->childNodes->length;
    print_r(compact('textContent', 'childNodes'));
}


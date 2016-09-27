<?php

require '../../OpenDocumentTemplate.php';

$od = new OpenDocumentTemplate();

$zip = new ZipArchive;
$zip->open('deepdata.json.zip');
echo "unzip deepdata.json\n";
$data = json_decode( $zip->getFromName('deepdata.json'), true );

$zip->close();

echo "Build report...\n";
$od->open('deepdata-template.ods', 'deepdata-out.ods', $data, array(
   // 'with_image_dir' => 'img',
  //  'extract_content' => true,
//    'dom_stay' => true
));


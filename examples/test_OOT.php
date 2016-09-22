<?php

require './OpenDocumentTemplate.php';


//class
#print_r ( file_get_contents(  $argv[1] ) );
$oo = new OpenDocumentTemplate();

// <editor-fold defaultstate="collapsed" desc="comment">
// </editor-fold>


        /*
  $river = array(
  'current_date' => date('c'),
  'River' => array(
  'name' => 'Volga',
  'length' => 9856
  ),
  'RiverIslands' => array(
  'Maps' => array(
  'title' => 'Jiguli'
  )
  ),
  0 => array(
  'virtual_sum' => 14398.45
  )

  );
  $string = '[x.y.z.1.2.3.4445] [current_date] [0.virtual_sum]The river [River.name] length is [River.length]. [RiverIslands.Maps.title]';

  $oo->parse_string($string, $river);
 */

$data = array(
    'Document' => array(
        'city' => 'Kinel',
        'address' => 'c. Kinel, str. Freedom, 256',
        'priority' => 65,
        'sign' => 'Mentor Juvanshy',
        'name' => 'Contract',
        'date' => '2016-09-16',
        'number' => 'A123/65',
        'total' => '12321.99'
    ),
    'DocumentData' => array(
        array(
            'DocumentDatum' => array(
                'count' => 30,
                'total' => 3234.32
            ),
            'Good' => array(
                'name' => 'Book of Heroes',
                'price' => 24
            )
        )
    )
);
//var_dump( $oo->parse_string_is_once_param('[DocumentDatum.count]') );

$json_string = file_get_contents('continents.json');
$json_string = str_replace(
        array("\n", "\t"), array('', ''), $json_string);


$data = json_decode(
        $json_string, true);

//print_r(compact('data'));
//exit;

$oo->open($argv[1], $argv[2], $data, array(
    'hide_draws' => array(
        'ImageNULL'
    )
));
//print_r ( compact('oo') );

//print_r( $argv[1] );
#$oo->read( $argv[1] );;

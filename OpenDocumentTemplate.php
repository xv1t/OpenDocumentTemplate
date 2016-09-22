<?php
/*
 * 
 * Class for generate files from templates
 * OpenDocument (LibreOffice, OpenOffice):
 *    1. ODS
 *    2. ODT (cooming soon)
 */

class OpenDocumentTemplate {
    var $dom;
    var $schema = array();
    var $data = array();
    var $parse_keys = array();
    var $last_id = 1;
    var $meta;
    var $parse = array(
        'before' => '[',
        'after' => ']',
        'separator' => '.'
    );
    var $mimetype; //of current open file
    
    /**
     * Open a template file, read him, and write result to a out_file
     * @param string $template_file<p>
     * Path to source ods or odt file
     * </p>
     * @param string $out_file<p>
     * Path to result file
     * </p>
     * @param array $data<p>
     * Structured array
     * </p>
     * @param array $options<p>
     * Options
     * </p>
     */
    function open($template_file, $out_file, $data, $options = array()) {
        if (empty($this->dom)) {
            $this->dom = new DOMDocument;
        }
        $zip = new ZipArchive;
        $zip->open($template_file);
        $this->mimetype = $zip->getFromName('mimetype');

        $this->dom->loadXML($zip->getFromName('meta.xml'));
        $this->read_meta();

        //prepare data
        $data += array(
            'now' => date('Y-m-d'),
            'now_datetime' => date('Y-m-d H:i:i'),
            'now_time' => date('H:i:s'),
            'template_file' => $template_file,
            'out_file' => $out_file
        );

        $this->dom->loadXML($zip->getFromName('content.xml'));
        $zip->close();
        
        $this->data = $data;

        switch ($this->mimetype) {
            case 'application/vnd.oasis.opendocument.spreadsheet':
                $this->ods_analyze();
                $this->ods_analyze_data();

                /*
                 * Manipulate images, hide or visible
                 */

                if (!empty($options['hide_draws'])) {
                    $this->ods_hide_draws($options['hide_draws']);
                }
                $this->ods_hide_draw_conditions();

                break;
            case 'application/vnd.oasis.opendocument.text':
                //coming_soon
                break;
        }

        if (file_exists($out_file)) {
            //delete destination file if exists
            unlink($out_file);
        }

        //copy template to destinatoin
        copy($template_file, $out_file);
        $zip->open($out_file);

        $content = $this->dom->saveXml();

        //file_put_contents($out_file . '-content.xml', $content);

        $zip->addFromString('content.xml', $content);

        //styles.xml
        $this->dom->loadXML($zip->getFromName('styles.xml'));
        $zip->addFromString('styles.xml', $this->render_styles());

        $zip->close();
    }
    
   function read_meta() {
        $this->meta = array();
        foreach ($this->dom->getElementsByTagName('user-defined') as $ud) {
            $pair = array(
                'name' => $ud->getAttribute('meta:name'),
                'value' => $ud->nodeValue,
                'func' => 'unknown'
                    )
            ;

            if (strpos($pair['name'], 'SUM(') === 0) {
                $pair['func'] = 'sum';
                list($one, $two) = explode('SUM(', $pair['name']);
                $prm_str = explode(')', $two);
                $params1 = explode(',', $prm_str[0]);
                $pair['params'] = array();
                foreach ($params1 as $prm) {
                    $param = ltrim(rtrim($prm));
                    if ($param) {
                        $pair['params'][] = $param;
                    }
                }
            }

            $this->meta[$ud->getAttribute('meta:name')] = $pair;
        }
    }

    function render_styles() {
        foreach (
                $this->dom
                ->getElementsByTagName('master-styles')->item(0)
                ->getElementsByTagName('p') as $p) {
            $text = $p->nodeValue;

            if ($this->string_has_params($text)) {
                $p->nodeValue = $this->parse_string($text, $this->data);
            }
        }

        return $this->dom->saveXml();
    }    

    function ods_hide_draws($hide_draws) {
        $draws = $this->dom->getElementsByTagName('frame');
        foreach ($draws as $draw) {
            if (in_array($draw->getAttribute('draw:name'), $hide_draws)) {
                $draw->parentNode->removeChild($draw);
            }
        }
    }

    /*
      analyze all draws and read descr conditions by data
     */
    function ods_hide_draw_conditions() {
        $draws = $this->dom->getElementsByTagName('frame');
        foreach ($draws as $draw) {
            $hide_image = false;

            $title = $draw->getElementsByTagName('title');

            if ($title->length > 0 && $title->item(0)->nodeValue == 'conditions') {

                $desc = $draw->getElementsByTagName('desc');
                if ($desc->length > 0 && $desc->item(0)->nodeValue) {
                    $conditions = $desc->item(0)->nodeValue;

                    $data = $this->data;
                    $hide_image = eval("return " . $conditions . ";");
                }
            }

            if ($hide_image) {
                $draw->parentNode->removeChild($draw);
            }
        }
    }

    function shell($commands = array()) {
        exec(join('; ', $commands), $out);
        return $out;
    }

    function ods_sheet($sheet_name) {

        $list = $this->dom->getElementsByTagName('table:table');

        foreach ($list as $sheet) {

            if (
                    $sheet->getAttribute('table:name') == $sheet_name ||
                    $sheet->getAttribute('name') == $sheet_name ||
                    $sheet->getAttributeNS('table', 'name') == $sheet_name
            ) {
                return $sheet;
            }
        }
        return false;
    }

    function ods_sheet_add($sheet_name) {

        //create temporary sheet
        $sheet = $this->dom->createElement('table:table');
        $sheet->setAttribute('table:name', $sheet_name);
        $sheet->setAttribute('name', $sheet_name);

        $this->dom
                ->getElementsByTagName('spreadsheet')
                ->item(0)
                ->appendChild($sheet);

        return $sheet;
    }

    function ods_debug($title, $value = '') {

        $row = $this->dom->createElement('table:table-row');

        $p1 = $this->dom->createElement('text:p');
        $p2 = $this->dom->createElement('text:p');
        $p1->nodeValue = $title;
        $p2->nodeValue = $value;

        $cell1 = $this->dom->createElement('table:table-cell');
        $cell2 = $this->dom->createElement('table:table-cell');

        $cell1->appendChild($p1);
        $cell2->appendChild($p2);

        $row->appendChild($cell1);
        $row->appendChild($cell2);

        $this->ods_sheet('DEBUG')->appendChild($row);
    }

    function ods_analyze() {
        //Read named ranges
        $this->schema = array();

        $nlist = array();

        foreach ($this->dom->getElementsByTagName('named-range') as $named) {
            $range_id = 'range' . $this->last_id++;

            $named->setAttribute('id', $range_id);

            $range = compact('range_id') + array(
                'name' =>
                $named->getAttribute('table:name'),
                'id' => $range_id,
                'cell-range-address' =>
                $named->getAttribute('table:cell-range-address'),
                'range-usable-as' =>
                explode(' ', $named->getAttribute('table:range-usable-as')),
            );

            list($sh, $start, $end) = explode(
                    '.$', str_replace(
                            ':', '', $range['cell-range-address'])
            );

            list($tmp, $range['start']) = explode('$', $start);
            list($tmp, $range['end']) = explode('$', $end);
            list($tmp, $range['sheet']) = explode('$', $sh);

            $range['length'] = $range['end'] - $range['start'] + 1;

            $range['children'] = array();
            $range['parent'] = null;
            $range['template_rows'] = array(); //Array of row elements
            $nlist[$range['start']] = $range;
        }

        //Sort ranges
        ksort($nlist);

        foreach ($nlist as $nkey => $n) {
            $this->schema['named-range'][$n['name']] = $n;
        }
        
        //Enumerate rows given row-repeated$this->schema['named-range']
        $rows = $this->dom
                ->getElementsByTagName('table')->item(0)
                ->getElementsByTagName('table-row');
        $number = 1;
        $this->schema['rows'] = array();
        foreach ($rows as $row) {

            $repeated = $row->getAttribute('table:number-rows-repeated')
                    ? : 1;

            $from = $number;
            $row_id = "row" . $number;
            $to = $number + $repeated - 1;

            //mark row elements
            $row->setAttribute('from', $from);
            $row->setAttribute('to', $to);
            $row->setAttribute('id', $row_id);
            
            $row_ranges = $this->row_ranges($number);

            if ($row_ranges) {
                //analyze ranges with parent<>child
                $level = count($row_ranges);

                $last_row_range = end($row_ranges);

                $row->setAttribute('range_name', $last_row_range);
                $row->setAttribute('range_level', $level);

                if ($from == $this->schema['named-range'][$last_row_range]['start']) {
                    $row->setAttribute('range_start', $last_row_range);
                }

                $end_ranges = array();

                foreach ($this->schema['named-range'] as $tmprange) {
                    if ($from == $tmprange['end']) {
                        $end_ranges[] = $tmprange['name'];
                    }
                }

                if ($end_ranges) {
                    $row->setAttribute('range_end', join(',', $end_ranges));
                }

                //Insert all used ranges 
                $row->setAttribute('ranges', join(',', $row_ranges));

                $this->schema['named-range'][$last_row_range]['level'] = $level;
                if ($level > 1) {
                    $range_parent = $row_ranges[$level - 2];
                    $this->schema['named-range']
                            [$last_row_range]
                            ['parent'] = $range_parent;

                    $row->setAttribute('range_parent', $range_parent);
                    $this->schema['named-range']
                            [$range_parent]
                            ['children']
                            [$last_row_range] = $last_row_range;

                } else {

                    $this->schema['named-range'][$last_row_range]['parent'] = null;
                }


                foreach ($this->schema['named-range'] as $tmprange) {
                    if (in_array($tmprange['name'], $row_ranges)) {
                        $this->schema['named-range']
                                [$tmprange['name']]
                                ['template_rows']
                                [] = $row;
                        //$this->dom->saveXml($row);
                    }
                }
            }
            //cells
            $cells = $row->getElementsByTagName('table-cell');
            $this->schema['rows'][] = compact('from', 'repeated', 'to', 'row_id') +
                    array(
                        'ranges' => $row_ranges,
                        'cells_count' => $cells->length,
            );

            //read cells

            $this->ods_render_row($row, $this->data);


            $number += $repeated;
        }
    }

    /*
     * Render all cell by cell
     */
    function ods_render_row($row, $data = array()) {
        $cells = $row->getElementsByTagName('table-cell');

        foreach ($cells as $cell) {
            $value_type = $cell
                    ->getAttribute('office:value-type');

            //get text data
            $p1 = $cell
                    ->getElementsByTagName('p'); 

            foreach ($p1 as $p) {

                $orig_cell_text = $p->nodeValue;

                $data_val = false;

                if (!empty($orig_cell_text)) {
                    if ($this->string_has_params($orig_cell_text)) {

                        if ($this->parse_string_is_once_param($orig_cell_text)) {
                            $param_key = $this->parse_string_extract_param($orig_cell_text);

                            if ($this->parse_param_exists($param_key, $data)) {

                                $data_val = $this->parse_param_value(
                                        $param_key, $data
                                );
                                $this->ods_cell_set_val($cell, $p, $data_val, array());
                            }
                        } else {
                            $p->nodeValue = $this->parse_string($orig_cell_text, $data);
                        }
                    }
                }
            }
        }
        return $row;
    }

    function ods_cell_set_val($cell, $p, $val, $options = array()) {
        //check a data type and change a cell type with from

        if (is_numeric($val)) {
            $string_val = (string) $val;
            $p->nodeValue = str_replace('.', ',', $string_val);
            $cell->setAttribute('office:value-type', 'float');
            $cell->setAttribute('calcext:value-type', 'float');
            $cell->setAttribute('office:value', $val);
        } else {
            $p->nodeValue = $val;
        }
    }

    function ods_tmp_sheet_empty() {
        foreach ($this->tmp_sheet->getElementsByTagName('table-row') as $row) {
            $row->parentNode->removeChild($row);
        }
    }

    function ods_aggregate_data($range_name, $data) {
        $result = array();


        foreach ($this->meta as $name => $options) {
            //sums
            if ($options['func'] == 'sum' && $options['value'] == $range_name) {

                if (array_key_exists($range_name, $data)) {

                    $result[$name] = 0;
                    foreach ($data[$range_name] as $datum) {

                        $data_key = $options['params'][0];

                        if ($this->parse_param_exists($options['params'][0], $datum)) {
                            $val = $this->parse_param_value($options['params'][0], $datum);
                            if (is_numeric($val)) {
                                $result[$name] += $val;
                            }
                        }
                    }
                }
            }
        }
        if ($result) {
           // print_r(compact('result', 'range_name', 'data'));
        }
        return $result;
    }

    //Recursive function!!!!
    function ods_render_range($range_name, &$data) {

        $range = $this->schema['named-range'][$range_name];
        $result_render_rows = array();
        
        if (array_key_exists($range_name, $data) && is_array($data[$range_name])) {
            //render objects, cycling data
            foreach ($data[$range_name] as $i => $datum) { 
                //Aggregate values from children
                if (!empty($range['children'])) {

                    foreach ($range['children'] as $children_range) {
                        $data[$range_name][$i]["COUNT($children_range)"] = 0;
                        if (array_key_exists($children_range, $datum)) {
                            $data[$range_name][$i]["COUNT($children_range)"] = count($datum[$children_range]);
                        }
                        //stat from document_options, functions
                        $data[$range_name][$i] += $this->ods_aggregate_data($children_range, $data[$range_name][$i]);
                    }
                }
                
                //initialize rendered rows of each datum object
                $data[$range_name][$i]['__rows__'] = array();

                //local [position], for each data object only in the range 
                $data[$range_name][$i]['position'] = $i + 1;

                foreach ($this->schema['named-range'][$range_name]['template_rows'] as  $static_row) {
                    $row = $static_row->cloneNode(true);
                    /*
                     * Check if range is other
                     */
                    if ($row->getAttribute('range_name') == $range_name &&
                            !$row->hasAttribute('done')
                    ) {
                        //Mark own rows as done
                        $row->setAttribute('done', 'true');

                        //Replace rendered row object in the source rows
                        $this->ods_render_row(
                            $row, 
                            $data[$range_name][$i]
                         );                                
                    }
                    
                    $data[$range_name][$i]['__rows__'][] = $row;
              
                }//foreach rows
                //render children
                
                /*********** Populate render children arrays to local array */
                if (!empty($range['children'])) {
                    
                    $data[$range_name][$i]['__children_rows__'] = array();
                    
                    $children_results = array();
                    $children_area = array();
                    foreach ($range['children'] as $children_range) {
                        $children_area[$children_range] = array(
                            'start' => null,
                            'end' => null
                        );
                        $data[$range_name][$i]['__children_rows__'][ $children_range ] = array();
                        if (array_key_exists($children_range, $datum)) {
                            
                            $data[$range_name][$i]['__children_rows__'][ $children_range ] = 
                                $this->ods_render_range(
                                    $children_range, $data[$range_name][$i]
                            );
                        }
                        
                        //empty children template area
                        //mark children area
                        foreach ($data[$range_name][$i]['__rows__'] as $j => $row){
                            if ( 
                                $row->getAttribute('range_name') == $children_range &&
                                $row->hasAttribute('range_start') 
                                    
                                    ){
                                $children_area[$children_range]['start'] = $j ;
                                    }
                            if ( 
                                    $row->hasAttribute('range_end')
                                    && in_array($children_range, explode(',',$row->getAttribute('range_end') )  )
                                    ){
                                $children_area[$children_range]['end'] = $j ;
                                $children_area[$children_range]['length'] = 
                                        $children_area[$children_range]['end'] - 
                                        $children_area[$children_range]['start'] +1;
                                
                            }
                        }
                        
                        if (    isset($children_area[$children_range]['start']) &&
                                isset($children_area[$children_range]['end']) 
                                
                                ){
                                    array_splice(
                                            $data[$range_name][$i]['__rows__'], 
                                            $children_area[$children_range]['start'],
                                            $children_area[$children_range]['length'],
                                            $data[$range_name][$i]['__children_rows__'][ $children_range ]
                                            );
                            
                        }
                    }

                    //insert children render rows in the source array $rows
                    //merge
                    //mark children area

                }//childrens
                $result_render_rows = array_merge($result_render_rows, $data[$range_name][$i]['__rows__']);
            } //foreach data
            /*
             * render
             */
        } //if
        return $result_render_rows;
    }

    function ods_analyze_data() {
        //Start for top level ranges
        $results_level1 = array();
        foreach ($this->schema['named-range'] as $range) {
            $range_name = $range['name'];
            switch ($range['level']) {
                case 1:
                    if (!empty($this->data[$range_name])) {
                        // get all rows elements on this range
                        //foreach($this->data[ $range_name ] as $data_level1){
                        //row cycle
                        $results_level1[$range_name]['rows'] = $this->ods_render_range($range_name, $this->data);
                    }
                    break;
            }
        }
        if ($results_level1){
            $sheet = $this->dom
                    ->getElementsByTagName('table')
                    ->item(0);
            
            foreach ($results_level1 as $range_name => $result){
                
                //delete template on the sheet
                $in_range = false;
                foreach ($sheet->getElementsByTagName('table-row') as $row){
                    if ($row->hasAttribute('range_name')
                            && $row->getAttribute('range_name') == $range_name
                            && $row->hasAttribute('range_start')
                            
                            ){
                        $results_level1[ $range_name ]['start'] = $row;
                        $in_range = true;
                            }
                            
                     if ($in_range){
                         $row->setAttribute('remove_me_please', 'yes');
                     }
                            
                    if ($row->hasAttribute('range_name')
                            
                            && $row->hasAttribute('range_end')
                            && in_array($range_name, explode(',', $row->getAttribute('range_end')) )
                            ){
                        $results_level1[ $range_name ]['end'] = $row;
                        $in_range = false;
                            }                            
                            
                }
                
                //insert data after end                
                foreach ( $results_level1[$range_name]['rows'] as $row ){
                    $results_level1[ $range_name ]['start']
                            ->parentNode
                            ->insertBefore($row, $results_level1[ $range_name ]['start']);
                }
                
            }
            
            //clear to_empty rows
            $remove_rows = array();
            foreach ($sheet->getElementsByTagName('table-row') as $row){
                if ($row->hasAttribute('remove_me_please')){
                    $remove_rows[] = $row;
                }
            }
            
            foreach ($remove_rows as $row){
                $row->parentNode->removeChild($row);
            }
            
        }
    }

    //return true if $this->params[before] and [after] exists
    function string_has_params($string) {
        if (strpos($string, $this->parse['before']) !== false) {
            if (strpos($string, $this->parse['after']) !== false) {
                return strpos($string, $this->parse['after']) >
                        strpos($string, $this->parse['before']);
            }
        }

        return false;
    }

    /*
      parse_string_extract_param('[Model.name]'); // 'Model.name'
     */
    function parse_string_extract_param($string) {
        if ($this->parse_string_is_once_param($string)) {
            return
                    substr(
                    $string, +strlen($this->parse['before']), -strlen($this->parse['after'])
            );
        }
        return false;
    }

    /*
      if string is '[Model.name]' then true
      if string = 'Welcome to [City.name]' then false
     */
    function parse_string_is_once_param($string) {
        if (
                substr($string, 0, strlen($this->parse['before'])) == $this->parse['before'] &&
                substr($string, -strlen($this->parse['after']), strlen($this->parse['after'])) == $this->parse['after']) {
            return
                    substr_count($string, $this->parse['before']) == 1 &&
                    substr_count($string, $this->parse['after']) == 1;
        }

        return false;
    }

    /*
      $param_key = 'Model.name';
      $data = array(
        'Model' => array(
        'name' => 'Sok',
        'disabled' => false,
        'stored' => null
      )
      );
      parse_param_value('Model.name', $data); // 'Sok'
      parse_param_value('Model.too_key', $data); //null
     */

    function parse_param_value($param_key, $data) {
        if ($this->parse_param_exists($param_key, $data)) {
            $chains = $this->parse_param_chain($param_key);

            switch (count($chains)) {
                case 1:
                    return $data[$param_key];
                    break;
                case 2:
                    return
                            $data
                            [$chains[0]]
                            [$chains[1]];
                    break;
                case 3:
                    return
                            $data
                            [$chains[0]]
                            [$chains[1]]
                            [$chains[2]];
                    break;
                case 4:
                    return
                            $data
                            [$chains[0]]
                            [$chains[1]]
                            [$chains[2]]
                            [$chains[3]];
                    break;
            }
        } else {
            
        }

        return null;
    }

    /*
      Check a key exists
      parse_param_exists('Model.Model2.not_key', $data); //return false
      parse_param_exists('Model.Model2.valid_key', $data); //return true
     */
    function parse_param_exists($param_key, $data, $debug = false) {


        if ($debug) {
            echo __FUNCTION__ . " ";
            print_r(compact('param_key', 'data'));
        }
        $chain = $this->parse_param_chain($param_key);
        switch (count($chain)) {
            case 1:
                return array_key_exists($param_key, $data);
                break;
            case 2:
                return
                        array_key_exists($chain[0], $data) &&
                        array_key_exists($chain[1], $data[$chain[0]]);
                break;
            case 3:
                return
                        array_key_exists($chain[0], $data) &&
                        array_key_exists($chain[1], $data[$chain[0]]) &&
                        array_key_exists(
                                $chain[2], $data[$chain[0]][$chain[1]]);
                break;
            case 4:
                return
                        array_key_exists($chain[0], $data) &&
                        array_key_exists($chain[1], $data[$chain[0]]) &&
                        array_key_exists(
                                $chain[2], $data[$chain[0]][$chain[1]]) &&
                        array_key_exists(
                                $chain[3], $data[$chain[0]][$chain[1]][$chain[2]]);
                break;
        }
        return false;
    }

    function parse_param_chain($param_key) {

        //check reserved words-functions
        if (strpos($param_key, '(') !== false) {
            return array($param_key);
        }

        return explode($this->parse['separator'], $param_key);
    }

    //Get string into params array
    function parse_string($string, $data = array(), $options = array()) {

        if (!$this->string_has_params($string)) {
            return $string;
        }

        //check if string is once param
        if ($this->parse_string_is_once_param($string)) {
            $param_key = $this->parse_string_extract_param($string);
            //print_r(compact('param_key'));
            if ($this->parse_param_exists($param_key, $data)) {
                return $this->parse_param_value($param_key, $data);
            }
            return $string; //param_key not exists, return string as is
        }

        $string1 = str_replace(
                array(
            $this->parse['before'],
            $this->parse['after']), array(
            '!-=0=-!' . $this->parse['before'],
            $this->parse['after'] . '!-=0=-!'), $string);

        $parsed = explode('!-=0=-!', $string1);
        $finded = array();
        foreach ($parsed as $ix => $item) {
            if ($item) {
                if ($this->parse_string_is_once_param($item)) {
                    $param_key = $this->parse_string_extract_param($item);

                    if ($this->parse_param_exists($param_key, $data)) {
                        $parsed[$ix] = $this->parse_param_value($param_key, $data);
                    }
                }
            }
        }
        return join($parsed);
    }

    private function row_ranges($row_number) {
        $ranges = array();
        foreach ($this->schema['named-range'] as $range) {
            if ($this->in_range($row_number, $range['name'])) {
                $ranges[] = $range['name'];
            }
        }
        return $ranges? : false;
    }

    private function in_range($row_number, $range_name) {

        $range = $this->schema['named-range'][$range_name];
        if ($row_number >= $range['start'] &&
                $row_number <= $range['end']
        ) {
            return true;
        }
        return false;
    }

}

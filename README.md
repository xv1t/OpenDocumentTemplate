# OpenDocumentTemplate.php
>Creating reports from your templates files

Recommended software for create template
* LibreOffice
* OpenOffice

Your done report files was correct opened in
* LibreOffice
* OpenOffice
* MS Office >=2010

## Fast manual
1. Create template ods file
2. Put elements
3. Mark ranges
4. Load data from database
5. Render data through template file info your new ods file
6. Open in the LibreOffice Calc or other and enjoy


## Requirements
Php extensions
* zip
* xml

Php version >=5.3

Recommended sowfware for templating: LibreOffice 5

##Install
Put file `OpenDocumentTemplate.php` into your project, and use it
```php
<?php
include_once "OpenDocumentTemplate.php";

//create object

$template = new OpenDocumentTemplate();
```

#First simple report

## Prepare your data
Data is array in php
```php
<?php
$data = array(
    'Report' => array(
        'name' => 'Test Report',
        'date' => '2016-09-25',
        'author' => 'Me'
    )
);
```

## Design template file
Open the LibreOffice Calc. Create new spreedsheet;

add a 3 cells for next contents:

A   | B |   C
----|---|----
[Report.name] | [Report.date] | [Report.author]

Save it with name `sample_report.ods`.

## Render template with data
```php
<?php
$template->open('sample_report.ods', 'sample_report-out.ods', $data)
```
And open new file `sample_report-out.ods` and you see in table:

A   | B |   C
----|---|----
Test Report | 2016-09-25 | Me

## Add second dimension
Add a key `Cities` for the list of objects
```php
<?php
$data = array(
    'Report' => array(/* main Report data */),
    'Cities' => array(
        array(/* city data */),
        array(/* city data */),
        array(/* city data */),
        array(/* city data */),
    )
);
```

All `Cities` object must be have an identical list of the fields. In our example: `name`, `streets`, `population`
```php
<?php

//Sample of one City object
array(
    'City' => array(
        'name' => 'Albatros',
        'streets' => 165,
        'population' => 1300000
    )
);
```

And  all data
```php
$data = array(
    'Report' => array(
        'name' => 'Test Report',
        'date' => '2016-09-25',
        'author' => 'Me'
    ),
    'Cities' => array(
        array( //first object
            'City' => array(
                'name' => 'Albatros',
                'streets' => 165,
                'population' => 1300000
            )
        ),
        array( //next object
            'City' => array(
                'name' => 'Turtuga',
                'streets' => 132,
                'population' => 750000
            )
        ),
        array( //next object
            'City' => array(
                'name' => 'Palmtown',
                'streets' => 18,
                'population' => 10000
            )
        ),
    )
);
```
## Add a other object as linear dimesion 
Add inforamation about the mayorof the each city
```php
<?php

$data = array(
    'Report' => array(/*...*/),
    'Cities' => array(
        array(
            'City' => array(/*...*/),
            'Mayor' => array(
                'name' => 'John Do',
                'old' => 47
            ),
        ),
        array(
            'City' => array(/*...*/),
            'Mayor' => array(
                'name' => 'Mary Ann',
                'old' => 32
            ),
        ),
        array(
            'City' => array(/*...*/),
            'Mayor' => array(
                'name' => 'Mike Tee',
                'old' => 29
            ),
        ),
    )
);
```

## Add third dimesions
```php
<?php
$data = array(
    'Report' => array(/*...*/),
    'Cities' => array(
        array(
            'City'  => array(/*...*/),
            'Mayor' => array(/*...*/),
            'Squares' => array(
                array(/*...*/),
                array(/*...*/),
                array(/*...*/),
            )
        ),
        array(
            'City'  => array(/*...*/),
            'Mayor' => array(/*...*/),
            'Squares' => array(
                array(/*...*/),
                array(/*...*/),
                array(/*...*/),
            )
        ),
        array(
            'City'  => array(/*...*/),
            'Mayor' => array(/*...*/),
            'Squares' => array(
                array(/*...*/),
                array(/*...*/),
                array(/*...*/),
            )
        ),
       
    )
);
```

# Examples



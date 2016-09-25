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
include_once "OpenDocumentTemplate.php";

//create object

$template = new OpenDocumentTemplate();
```

## Prepare your data
Data is array in php
```php
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
$template->open('sample_report.ods', 'sample_report-out.ods', $data)
```
And open new file `sample_report-out.ods` and you see in table:

A   | B |   C
----|---|----
Test Report | 2016-09-25 | Me




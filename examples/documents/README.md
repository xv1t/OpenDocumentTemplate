# Documents example

# 1. Prepare image files
All files put in one directory, in out example in `img`

Image | size     | variants | dummy image   | image name
------|----------|----------|:--------------------:
Logo  | 1024×100 |   3      | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_empty.jpg"> | [Supplier.logo]
Stamp | 400×400  |   3      | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_empty.png" width="200"> | [Document.staml]
Sign  | 685×350  |   3      | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_empty.png" width="200"> | [Document.sign]

If you planing use different images, firstly you make a dummy version of images




## Stamps
stamp_libre_office_calc.png|stamp_open_doc_template.png|stamp_ubuntu.png
:------:|:-----:|:-----:
<img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_libre_office_calc.png" width="200"> | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_open_doc_template.png" width="200"> | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_ubuntu.png" width="200">

## Logos
Picture | File
------|-----
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_libre_office.jpg) |logo_libre_office.jpg
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_open_doc_template.jpg) |logo_open_doc_template.jpg
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_ubuntu.jpg) | logo_ubuntu.jpg

## Signs
 sign_libre_office_calc.png | sign_open_doc_template.png | sign_ubuntu.png
--------|------|-----|-----
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_libre_office_calc.png) |![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_open_doc_template.png) |![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_ubuntu.png)

# 2. Prepare data array
Our `$data` in this example is a array and it have following keys:
```php
$data = array(
    'Document' => /*...*/,
    'Supplier' => /*...*/,
    'Buyer'    => /*...*/
);
```
## Linear object notation.
Keys `Document`, `Supplier`, `Buyer` - is a linear object keys
```php
$data = array(
    'Document' => array(
        'name' => 'Bill',
        'number' => '123/A9',
        'date' => '2016-09-23',
        'manager' => ''
    ),
    'Supplier' => array(
        'name' => '',
        ''
    ),
    'Buyer'
);
```

## Next deep level data
The key `Goods` is contain a list of objects.
```php

```

# 3. Design template
Design template file `document_bill.ods`
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src.jpg)

## Named range of items
Row `10` is a named range by name `Goods`.

Range option 

- [x] Repeat row
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src_named.jpg)

Set properties for define aggregate `SUM()` functions:
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src_properties.jpg)

## Aggregate function `SUM()`

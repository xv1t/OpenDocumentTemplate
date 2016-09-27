# Documents example

# 1. Prepare image files
All files put in one directory, in out example in `img`

Image | size     |  dummy image   | image name
------|----------|:---------:|:--------------------:|----
Logo  | 1024×100 |  <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_empty.jpg"> | [Supplier.logo]
Stamp | 400×400  |  <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_empty.png" width="200"> | [Document.stamp]
Sign  | 685×350  |  <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_empty.png" width="200"> | [Document.sign]

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

## Image folder
All image put in the once folder `img`

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
        'manager' => '',
        'stamp' => 'stamp_open_doc_template.png',
        'sign' => 'sign_open_doc_template.png'
    ),
    'Supplier' => array(
        'logo' => 'logo_libre_office.jpg',
        'name' => "Open source shot test",
        'address' => "The city of the country, 123-23FZ",
        'email' => 'mail@example.free'
    ),
    'Buyer' => array(
        'name' => 'Typical customer',
        'address' => 'My country',
        'email' => 'mail@sample.com'
    ),
);
```

## Next deep level data
Add a key `Goods` is contain a list of objects.
```php
$data = array(
    'Document' => /*...*/,
    'Supplier' => /*...*/,
    'Buyer'    => /*...*/
    'Goods' => array( //key for list of goods objects
        array(/*...*/),
        array(/*...*/),
        array(/*...*/),
        array(/*...*/),
        array(/*...*/),
        array(/*...*/),
        array(/*...*/),
    )
);
```
And each `Good` object fill as
```php
array(
    'Goods' => array(
        array('Good' => array(/*...*/)),
        array('Good' => array(/*...*/)),
        array('Good' => array(/*...*/)),
        array('Good' => array(/*...*/)),
        array('Good' => array(/*...*/)),
        array('Good' => array(/*...*/)),
        array('Good' => array(/*...*/)),
    )
)
```
Add all field for each `Good` contain a kes: `name`, `cost`, `count`
```php
$data = array(
    'Document' => array(
        'name' => 'Bill',
        'number' => '123/A9',
        'date' => '2016-09-23',
        'manager' => '',
        'stamp' => 'stamp_open_doc_template.png',
        'sign' => 'sign_open_doc_template.png'
    ),
    'Supplier' => array(
        'logo' => 'logo_libre_office.jpg',
        'name' => "Open source shot test",
        'address' => "The city of the country, 123-23FZ",
        'email' => 'mail@example.free'
    ),
    'Buyer' => array(
        'name' => 'Typical customer',
        'address' => 'My country',
        'email' => 'mail@sample.com'
    ),
    'Goods' => array( //This list of data next dimension
        array(
            'Good' => array(
                'name' => 'Cofee',
                'cost' => 6.45,
                'count' => 4,
            )
        ),
        array(
            'Good' => array(
                'name' => 'Disk',
                'cost' => 0.17,
                'count' => 3,
            )
        ),
        array(
            'Good' => array(
                'name' => 'Book',
                'cost' => 2.30,
                'count' => 3,
            )
        ),
        array(
            'Good' => array(
                'name' => 'USB Flash',
                'cost' => 19,
                'count' => 2,
            )
        ),
        array(
            'Good' => array(
                'name' => 'Floppy disk',
                'cost' => 1.01,
                'count' => 10,
            )
        ),
        array(
            'Good' => array(
                'name' => 'Manual PDF',
                'cost' => 9,
                'count' => 1,
            )
        ),
        array(
            'Good' => array(
                'name' => 'Hat',
                'cost' => 17.34,
                'count' => 7
            )
        ),
        array(
            'Good' => array(
                'name' => 'Pen',
                'cost' => 0.87,
                'count' => 26
            )
        ),
        array(
            'Good' => array(
                'name' => 'Keyboard AB',
                'cost' => 16.04,
                'count' => 8
            )
        ),
    )
);
```

# 3. Design template
Design template file `document_bill.ods`
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src.jpg)

## Named range of items
Row `10` is a named range by name `Goods`.

Range option 

- [x] Repeat row
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src_named.jpg)


## Images
All images need `Anchor` to `cell`. If select image, then be visible a anchor icon on the cell

<img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src_img_anchor.png" width="250">

## Image names
If you want dinamic change image source, then set name to field name of current object,

Logo                | Stamp   | Sign
-----------------|--------|---------
[Supplier.logo] | [Document.stamp] | [Document.sign]

```php
$data = array(
    'Document' => array(
        /*...*/
        'stamp' => 'stamp_open_doc_template.png', //stamp image, image name: [Document.stamp]
        'sign' => 'sign_open_doc_template.png'    //sign image, image name : [Document.sign]
    ),
    'Supplier' => array(
        'logo' => 'logo_libre_office.jpg',        //logo image name, image name: [Supplier.logo]
        /*...*/
    ),
    'Buyer' => array(/*..*/),
    'Goods' => array(/*..*/),
```
);

## Virtual fields
In the good we have a two numeric fields: `cost`, `count`.
But what about a value of `cost * count`?

Virtual field | Formula
--------------|-----------
[Good.total]    | [Good.cost]*[Good.count]
[Good.tax]    | [Good.total] * 0.18
[Good.with_tax] | [Good.with_tax]

On the spreedsheet in the cell with virtual field add a `Comment` and write formula, such as
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src_virtual_fields.png)

## Aggregate function `COUNT()`
All values `COUNT()` for defined named ranges automaticaly calculated
Examples

Named range | cell template value   | Report value
------------|-----------------------|----------
Goods       | [COUNT(Goods)]        |        8
Countries   | [COUNT(Countries)]    |        3

## SUM()

Set properties for define aggregate `SUM()` functions:

![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_src_properties.jpg)

In the `LibreOffice Calc` click `File`/`Properties` - tab `Custom properties`
And cells with values: `[SUM(Good.tax)]`, `[SUM(Good.total)]` and other are correctly to sum

# And render our template
```php
<?php

require '../../OpenDocumentTemplate.php';

$od = new OpenDocumentTemplate();

$od->open('document_bill.ods', 'document_bill-out.ods', $data, array(
    'with_image_dir' => 'img/', //this path of your images folder
));
```

And open new file `document_bill-out.ods` in the libre office and show print preview

![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_out.jpg)

![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/docs/img/document_template_out_ubuntu.png)
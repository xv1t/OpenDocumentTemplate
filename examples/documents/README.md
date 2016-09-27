# Documents example

# 1. Prepare image files
All files put in one directory, in out example in `img`

Image | size     | variants | dummy image
------|----------|----------|:--------------------:
Logo  | 1024×100 |   3      | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_empty.jpg"> 
Stamp | 400×400  |   3      | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_empty.png" width="150">
Sign  | 685×350  |   3      | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_empty.png" width="200">

If you planing use different images, firstly you make a dummy version of images




## Stamps
stamp_libre_office_calc.png|stamp_open_doc_template.png|stamp_ubuntu.png
:------:|:-----:|:-----:
<img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_empty.png" width="150"> | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_libre_office_calc.png" width="150"> | <img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_open_doc_template.png width="150"> | ![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/stamp_ubuntu.png" width="150">

## Logos
Picture | File
------|-----
<img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_libre_office.jpg width="150"> |logo_libre_office.jpg
<img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_open_doc_template.jpg width="150"> |logo_open_doc_template.jpg
<img src="https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/logo_ubuntu.jpg width="150"> | logo_ubuntu.jpg

## Signs
sign_empty.png | sign_libre_office_calc.png | sign_open_doc_template.png | sign_ubuntu.png
--------|------|-----|-----
![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_empty.png) |![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_libre_office_calc.png) |![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_open_doc_template.png) |![](https://github.com/xv1t/OpenDocumentTemplate/blob/master/examples/documents/img/sign_ubuntu.png)

# 2. Prepare data array


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

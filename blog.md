# Dynamic images and cell data
Data example
```json
{
  "Report": {
    "title": "Test data images"
  },
  "People": [
    {
      "Person": {
        "name": "Baard",
        "image" "board.png"
      }
    },
    {
      "Person": {
        "name: "Poetzo",
        "image": "poetzo.png"
      }
    },
    {
      "Person": {
        "name": "Duuns",
        "image": null
      }
    }
  ]
} 
```
Row ODS

Range | pos | name | image |
------ | -------|----|---
People | [position]|  [Person.name] | [Person.image]

Import image to ods file, and achor it to cell with `[Person.image]`.
All images are must be a `Protect size`!

Well, in `ods_render_row`
```php
<?php
function ods_render(...){
...
  foreach ($cells as $cell){
    ...
    //parse value
    
    //check if cell contain a `draw:frame`
    $
    
  }

...
}
```

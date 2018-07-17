# Image Manipulator


This is a object oriented php project which basically use GD library to manipulate image files. 

  - Using this library you can manipulate image
  - Add effect on image very easily
  - Light-wight library, with fast functionality

# Features

  - Add Instagram effect on the image very easily 
  - Using Method Chain you can get multiple effect and functionality at the same time 
  - You can crop, resize, rotate, add text on image 
  - Support for jpg and png type of file 


You can also:
  - Save file using `save_image()` method, with prefix of the original file
  - Instantly see the the effect on the image without saving it using `display()` method
  

This is a very lightweight php class file. So it will not effect on the performance of the original project.

> I made this library so that everyone can use it.
> Some Instagram effect has been taken from another popular 
> instagram effect maker library named *PHP-Instagram-effects*
> written and developed by **Zaachi**.
> this is the original [Link](https://github.com/zaachi/PHP-Instagram-effects) of his library.
> feel free to use it.



## Installation

This library does not use any third party library or tools. All we need is the php and webserver. This class is written using the [GD Library](http://php.net/manual/en/book.image.php) v2+ to run.

No install dependencies required. Using this library is very easy. A very basic use is given below. 


```php
//include the class file
include 'imagemanipulator/Image_Manipulation.php';

//creating the object
$image = new Utshabimagemanipulator\Image_Manipulation('path/to/image.jpg');

//calling the function
$image
    ->insta_vintage()           // apply vintage effect on the image
    
    ->flip_image('both')        // flip the image in both x-axis and y-axis
    
    ->save_image('prefix_')     // saving the image   
    
    ->display();                // this will display the image on the screen
```



### Available Methods

These are some available method you can use to manipulate the image as you want. The **method name** and **usage** are given below:

| Method Name | Usages and Effect |
| ------ | ------ |
| `display()` | Display the image on the screen |
| `save_image()` | Save the image file as given prefix on the given directory  |
| `rotate_image()` | Rotate the image by given angle  |
| `thumbnail()` | Creates thumbnail of the image according to given ratio |
| `resize_image_pixels()` | Resize the image by given height and width in pixel |
| `resize_image()` | Resize the image by given height and width ratio |
| `crop()` | Crop the image from given starting point and rectangle shape of height and width |
| `stamp_on_image()` | Add a stamp on the original pic, position with margin |
| `watermark_with_image()` | Watermark the image with given image |
| `watermark_image()` | Watermark the image created manually |
| `GD_effect()` | Apply various type of effect on the image by selecting case |
| `border_on_image()` | Add border on image with given thickness and RGB color |
| `flip_image()` | Flip the image according to the direction |
| `text_on_image()` | Add given text on the image and can select the position by margin |
| `insta_aqua()` | Apply insta_aqua effect on the image |
| `insta_dream()` | Apply insta_dream effect on the image |
| `insta_tender()` | Apply insta_tender effect on the image |
| `insta_freshblue()` | Apply insta_freshblue effect on the image |
| `insta_everglow()` | Apply insta_everglow effect on the image |
| `insta_vintage()` | Apply insta_vintage effect on the image |
| `insta_antique()` | Apply insta_antique effect on the image |
| `insta_boost()` | Apply insta_boost effect on the image |
| `insta_fuzzy()` | Apply insta_fuzzy effect on the image |
| `insta_light()` | Apply insta_light effect on the image |
| `insta_cool()` | Apply insta_cool effect on the image |
| `insta_sepia()` | Apply insta_sepia effect on the image |
| `insta_sharpen()` | Apply insta_sharpen effect on the image |




### Development

Want to contribute? Great!

If you find any bug or want ot improve it just ***fork*** on the git project and it will create a  ***pull request*** I will accept it.
***[Git Repo of this project][gitRepo]***

You can mail me if required. 

Email address: <utshab.roy@gmail.com>


#### Find Me on social media

* [LinkedIn][linkedInLink]
* [Facebook][facebookLink]
* [Google+][googlePlusLink]


### Todos

 - More instagram effect need tobe added
 - Improve the resize method without losing the image quality

License
----

GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007


**Free Software, Hell Yeah!**

   [facebookLink]: <https://www.facebook.com/uutshab>
   [linkedInLink]: <https://www.linkedin.com/in/utshab-roy>
   [googlePlusLink]: <https://plus.google.com/u/0/+UtshabRoy>
   [gitRepo]: <https://github.com/utshab-roy/photoEditor_php>
   

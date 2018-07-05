# Image Manipulator


This is a object oriented php project which basically use GD library to manipulate image files. 

  - Using this library you can manipulate image
  - Add effect on image very easily
  - Lightwight library, with fast functionality

# Features

  - Add Instagram effect on the image very easily 
  - Using Method Chain you can get multiple effect and functionality at the same time 


You can also:
  - Save file using `save_image()` method, with prefix of the original file
  - Instantly see the the effect on the image without saving it using `display()` method

This is a very lightweight php class file. So it will not effect on the performance of the original project.

> I made this library so that everyone can use it.
> Some Instagram effect has been taken from another popular 
> instagram effect maker library named PHP-Instagram-effects
> written and developed by Zaachi.
> this is the original [Link](https://github.com/zaachi/PHP-Instagram-effects) of his library.
> fell free to use it.



### Installation

This library does not use any third party library or tools. All we need is the php and webserver. This class is written using the [GD Library](http://php.net/manual/en/book.image.php) v2+ to run.

No install dependencies required. Using this library is very easy. A very basic use is given below. 


```php
//include the class file
include 'photoEditor_php/Image_Manipulation.php';

//creating the object
$image = new Image\Image_Manipulation('books1.jpg');

//calling the function
$image
    ->insta_vintage()       // apply vintage effect on the image
    ->display();            // this will display the image on the screen
```



### Available Methods

These are some available method you can use to manipulate the image as you want. The **method name** and **usage** are given below:

| Method Name | Usages and Effect |
| ------ | ------ |
| `display()` | Display the image on the screen |
| `save_image()` | Save the image file as given prefix on the given directory  |



### Development

Want to contribute? Great!
If you find any bug or want ot improve it just ***fork*** on the git project and it will create a  ***pull request*** I will accept it.
***[Git Repo of this project][gitRepo]***

You can mail me if required email addresses: <utshab.roy@gmail.com>


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
   

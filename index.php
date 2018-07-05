<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'autoload.php';

$img = new Image\Image_Manipulation('images/books2.jpg');

$img
//    ->insta_aqua()
//    ->insta_sharpen()
//    ->insta_sepia()
//    ->insta_cool()
//    ->insta_light()
//    ->insta_fuzzy()
//    ->insta_boost()
//    ->insta_antique()
//    ->insta_vintage()
//    ->insta_everglow()
//    ->insta_freshblue()
//    ->insta_tender()
//    ->insta_dream()
//    ->radium()
//    ->rotate_image(90)                                    // rotate the image
//    ->resize_image(.9, .9)                                // resize the image
//    ->text_on_image()                                     // text on image
    ->flip_image('both')                                  // flip the image, three options
//    ->border_on_image(15, 25, 141, 214)                    // creates a border on the image
//    ->GD_effect('colorize')
//    ->watermark_image(70, 30, 30)
//    ->thumbnail(5)
//    ->watermark_with_image('images/books2.jpg')
//    ->stamp_on_image('images/copyright.png', 40, 20)
//    ->crop(200,200,500,500)
//    ->resize_image_pixels(600, 200)
//    ->save_image()
    ->display();
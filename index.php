<?php

class Image_Manipulation{
    private $file;

    private  $data;

    public function __construct($file = '')
    {
        $this->file = $file;

        //still don't know why need this line
        header('Content-type: image/jpeg');

    }


    /**
     * Rotate image by degree
     *
     * @param int $deg
     */
    public function rotate_image($deg = 90){
        $deg = floatval($deg);

        $this->data = imagecreatefromjpeg($this->file);

        imagesetinterpolation($this->data, IMG_BELL);

        $image_rotated = imagerotate($this->data, $deg, 0);

        imagejpeg($image_rotated);
    }

    /**
     * Save file
     *
     * @param string $file_output  if empty then main image will saved, if not image will be saved as this new name
     */
    public function save_file($file_output = ''){

    }

    public function water_mark(){

    }


}


$img = new Image_Manipulation('images/books1.jpg');
$img->rotate_image(30);
$img->save_file();

//echo "<pre>";
//print_r($image_info);
//echo "</pre>";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image Manipulation</title>
</head>
<body>
<!--<img src="images/rotatedPic.jpg" alt="">-->
</body>
</html>

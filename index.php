<?php

class Image_Manipulation{
    public $file;
    private  $data;
    public $rotation = false; public $resize = false; public $texted = false; public $stamped = false; public $watermarked = false;

    public $rotated_file; public $resize_file; public $texted_file; public $stamped_file; public $watermarked_file;

    public $rotation_deg;

    public function __construct()
    {
//        $this->file = $file;
        //this line may redirect to the image viewer
//        header('Content-type: image/jpeg');
    }




    /**
     * Rotate image by degree
     *
     * @param int $deg
     */
    public function rotate_image($deg){

        if(isset($this->file)){
            $deg = floatval($deg);
            $this->data = imagecreatefromjpeg('images/'.$this->file);
            imagesetinterpolation($this->data, IMG_BELL);
            $image_rotated = imagerotate($this->data, $deg, 0);
            imagejpeg($image_rotated, 'manipulated_image/rotated_'.$this->file, 100);
            $this->rotated_file = 'rotated_'.$this->file;
            imagedestroy($this->data);

            $this->rotation = true;
        }

    }

    public function resize_image(){
        if(isset($this->file)){
            $this->data = imagecreatefromjpeg('images/'.$this->file);
            $image_info = getimagesize('images/'.$this->file);

            $width  = $image_info[0];    // width of the image
            $height = $image_info[1];    // height of the image

            //resizing the image
            $new_width  = round ($width * 0.5);
            $new_height = round ($height * 0.5);

            //creating a new image
            $new_image = imagecreate($new_width, $new_height);
            imagecopyresized($new_image, $this->data, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            imagejpeg($new_image,'manipulated_image/resized_'.$this->file, 100);
            $this->resize_file = 'resized_'.$this->file;
            imagedestroy($new_image);

            $this->resize = true;
        }
    }

    public function add_text_on_image(){
        if(isset($this->file)) {
            $this->data = imagecreatefromjpeg('images/' . $this->file);
            $bluecolor = imagecolorallocate($this->data, 0, 0, 255);
            imagestring($this->data, 5, 500, 500, 'Copyrights balustor.net', $bluecolor);

            imagejpeg($this->data, 'manipulated_image/texted_' . $this->file, 100);
            $this->texted_file = 'texted_' . $this->file;

            imagedestroy($this->data);

            $this->texted = true;
        }
    }

    public function add_stamp_on_image(){
        if(isset($this->file)) {
            $stamp = imagecreatefrompng('images/copyright.png');
            $this->data = imagecreatefromjpeg('images/' . $this->file);

            // Set the margins for the stamp and get the height/width of the stamp image
            $right = 100;
            $bottom = 400;

            // imagesx and imagesy Returns the width of the given image resource.
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
//            $sx = 1600;
//            $sy = 1600;

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($this->data, $stamp, imagesx($this->data) - $sx - $right, imagesy($this->data) - $sy - $bottom, 0, 0, imagesx($stamp), imagesy($stamp));

            imagejpeg($this->data, 'manipulated_image/stamped_' . $this->file, 100);
            $this->stamped_file = 'stamped_' . $this->file;
            imagedestroy($this->data);

            $this->stamped = true;
        }
    }


    public function add_watermark_on_image(){
        if(isset($this->file)) {
            $this->data = imagecreatefromjpeg('images/' . $this->file);

            // First we create our stamp image manually from GD
            $stamp = imagecreatetruecolor(200, 70);

            imagefilledrectangle($stamp, 0, 0, 199, 169, 0x0000FF);
            imagefilledrectangle($stamp, 9, 9, 190, 60, 0xFFFFFF);
            imagestring($stamp, 5, 20, 20, 'Balustor Blog', 0x0000FF);
            imagestring($stamp, 3, 20, 40, '(c) 2018', 0x0000FF);

            // Set the margins for the stamp and get the height/width of the stamp image
            $right = 20;
            $bottom = 20;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Merge the stamp onto our photo with an opacity of 50%
            imagecopymerge($this->data, $stamp, imagesx($this->data) - $sx - $right, imagesy($this->data) - $sy - $bottom, 0, 0, imagesx($stamp), imagesy($stamp), 40);

            // Save the image to file and free memory
            imagejpeg($this->data, 'manipulated_image/watermarked_' . $this->file, 100);
            $this->watermarked_file = 'watermarked_' . $this->file;

            imagedestroy($this->data);
            $this->watermarked = true;
        }
    }

    /**
     * Save file
     *
     * @param string $file_output  if empty then main image will saved, if not image will be saved as this new name
     */
    public function save_file($file_output = ''){
//        echo 'hello world';
    }


    /**
     * this function will upload image to the images folder
     *
     */
    public function upload_image(){
        if(isset($_FILES['image'])){
            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_size =$_FILES['image']['size'];
            $file_tmp =$_FILES['image']['tmp_name'];
            $file_type=$_FILES['image']['type'];

            $tmp = explode('.', $file_name);
            $file_ext = end($tmp);


            $expensions= array("jpeg","jpg","png");

            if(in_array($file_ext,$expensions)=== false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }

            if($file_size > 2097152){
                $errors[]='File size must be excately 2 MB';
            }

            if(empty($errors)==true){
                move_uploaded_file($file_tmp,"images/".$file_name);
                echo "Image file has been uploaded for manipulation. </br>";
                $this->file = $file_name; //setting the current uploaded pic as to manipulation
            }else{
                print_r($errors);
            }
        }
    }


}




$img = new Image_Manipulation();

if(isset($_POST['rotate']) && intval($_POST['rotate']) >0){
    $img->rotation_deg = intval($_POST['rotate']);
}else{
    $img->rotation_deg = 90;
}

$img->upload_image();
$img->rotate_image($img->rotation_deg);
$img->resize_image();

$img->add_text_on_image();
$img->add_stamp_on_image();
$img->add_watermark_on_image();

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <title>Image Manipulation</title>
</head>
<body>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="image"/>

    <label for="rotation_degree"><b>Rotation Degree(default 90):</b></label>
    <input type="text" placeholder="degree value" name="rotate" value="" id="rotation_degree"/>

    <input class="btn btn-danger" type="submit"/>
</form>

<?php
if (isset($img->file)){
    echo '<br/><h3>Original Pic</h3> <br/>';
    echo "<img src='images/$img->file' width='500' height='333'>";
}

if ($img->rotation == true){
    echo '<br/><h3>Rotated Pic</h3></br>';
    echo "<img src='manipulated_image/$img->rotated_file' width='500' height='333'>";
}

if ($img->resize == true){
    echo '<br/><h3>Resized Pic</h3></br>';
    echo "<img src='manipulated_image/$img->resize_file' >";
}

if ($img->texted == true){
    echo '<br/><h3>Added text on Pic</h3></br>';
    echo "<img src='manipulated_image/$img->texted_file' >";
}

if ($img->stamped == true){
    echo '<br/><h3>Added stamp on Pic</h3></br>';
    echo "<img src='manipulated_image/$img->stamped_file' width='500' height='333' >";
}

if ($img->watermarked == true){
    echo '<br/><h3>Added watermark on Pic</h3></br>';
    echo "<img src='manipulated_image/$img->watermarked_file' width='500' height='333' >";
}


?>

</body>
</html>

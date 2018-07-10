<?php
/**
 * @category   Image Editor
 * @package    Image
 * @class      Image_Manipulation
 * @author     Utshab Roy <utshab.roy@gmail.com>
 * @copyright  2018 Utshab Roy
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License (GPL)
 * @version    Release: @package_version@ 1.0.0
 * @link
 * @see        https://github.com/utshab-roy/photoEditor_php
 * @since      Class available since Release 1.0.0
 */

namespace Utshabimagemanipulator{
    class Image_Manipulation{

        protected $image;
        protected $original_image;
        protected $clone_image;
        protected $directory;
        protected $file_type;
        protected $file_name;
        protected $file_path;
        public $file;

        public function __construct($file_path)
        {
            $this->file_path = $file_path;
            $this->file_name = $this->get_file_name($file_path);
            $this->file_type = $this->get_file_extension($file_path);
            switch ($this->file_type){
                case 'jpg':
                    $this->image = imagecreatefromjpeg($file_path);
                    break;
                case 'png':
                    $this->image = imagecreatefrompng($file_path);
                    break;
            }
        }

        /**
         * this method takes the file name or file path and returns the file extension
         * @param $file_name
         * @return mixed
         */
        public function get_file_extension($file_name){
            //checks the file extension
            $tmp = explode('.', $file_name);
            $file_ext = end($tmp);
            return $file_ext;
        }

        /**
         * this method takes the file path of the file and returns the file name
         * @param $file_path
         * @return mixed
         */
        public function get_file_name($file_path){
            //getting the file name
            $tmp = explode('/', $file_path);
            $file_path = end($tmp);
            return $file_path;
        }

        /**
         * this method clone the image resource GD type so that we can use it as a unique resource
         */
        public function clone_image_resource(){
            $width  = imagesx($this->image);
            $height = imagesy($this->image);
            $this->image = imagecreatetruecolor($width, $height);

            imagecopy($this->clone_image, $this->image, 0, 0, 0, 0, $width, $height);
            $this->display_image($this->clone_image);
            return $this;
        }

        /**
         * this method clone the image resource GD type according to source to destination
         * still not working perfectly, I use rotate method for the copy image resource
         * @param $src
         * @param $dest
         */
        public function clone_image_src_dest(&$src, &$dest){
//            $width  = imagesx($src);
//            $height = imagesy($src);
//
//            $dest_width  = imagesx($dest);
//            $dest_height = imagesy($dest);

//            $dest = imagecreate($width,$height);
            $dest = imagerotate($src, 360, 1);
//            $this->display_image($dest);
//            imagecopyresized($dest, $src, 0, 0, 0, 0, $width, $height, $width, $height);
//            imagecopy($dest, $src, 0, 0, 0, 0, $width, $height);
//            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $width, $height);

        }

        /**
         * this method creates a folder if not exists according to $folder_name variable
         * @param string $folder_name
         */
        public function make_directory($folder_name = 'Manipulated_Images'){
            //this will create the manipulated_image folder it not exists.
            if (!file_exists($folder_name)) {
                mkdir($folder_name, 0777, true);
            }
        }

        /**
         * saves the file with prefix on the original file
         * @param string $prefix_of_filename
         * @param string $folder_name
         * @return $this
         */
        public function save_image($prefix_of_filename = 'effected_', $folder_name = 'Manipulated_Images'){
            //make a directory to store the images
            $this->make_directory($folder_name);
            switch ($this->file_type){
                case 'png':
                    imagepng($this->image,"$folder_name/".$prefix_of_filename.$this->file_name, 9);
                    break;
                case 'jpg':
                    imagejpeg($this->image,"$folder_name/".$prefix_of_filename.$this->file_name, 100);
                    break;
            }
            return $this;
        }

        /**
         * display the image on screen
         */
        public function display(){
            // Content type
            switch ($this->file_type){
                case 'jpg':
                    header('Content-type: image/jpeg');
                    imagejpeg($this->image);
                    break;
                case 'png':
                    header('Content-type: image/png');
                    imagepng($this->image);
                    break;
            }
        }

        /**
         * takes the GD type image resource and display the image on the screen
         * @param $image
         */
        public function display_image($image){
            // Content type
            switch ($this->file_type){
                case 'jpg':
                    header('Content-type: image/jpeg');
                    imagejpeg($image);
                    break;
                case 'png':
                    header('Content-type: image/png');
                    imagepng($image);
                    break;
            }
        }

        /**
         * this method rotate the image according to the given degree
         * @param float $deg
         * @return $this
         */
        public function rotate_image($deg = 45.0){
            $deg = floatval($deg);
            $this->image = imagerotate ($this->image , $deg , 0 );
            return $this;
        }

        /**
         * this method crop the image according to the given value.
         *
         * @param int $position_x           starting posing of x-axis
         * @param int $position_y           starting posing of y-axis
         * @param int $width                width of the cropped image size
         * @param int $height               height of the cropped image size
         * @return $this                    returns the object
         */
        public function crop($position_x = 200, $position_y = 200, $width = 500, $height = 400){
            $size = min(imagesx($this->image), imagesy($this->image));

            if ($size > $width && $size > $height){
                $this->image = imagecrop($this->image, ['x' => $position_x, 'y' => $position_y, 'width' => $width, 'height' => $height]);
            }
            return $this;
        }

        /**
         * this method resize the image according to the width and height ratio
         * @param float $width_ratio
         * @param float $height_ratio
         * @return $this
         */
        public function resize_image($width_ratio = 0.1, $height_ratio = 0.1){
            $image_info = getimagesize($this->file_path);

            $width  = $image_info[0];    // width of the image
            $height = $image_info[1];    // height of the image

            //resizing the image
            $new_width  = round ($width * $width_ratio);
            $new_height = round ($height * $height_ratio);

            //creating a new image
            $new_image = imagecreate($new_width, $new_height);
            imagecopyresized($new_image, $this->image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $this->clone_image_src_dest($new_image, $this->image);
//            $this->save_image('resize_');
            return $this;
        }

        /**
         * this method resize the image and returns the image file resource
         * @param $width_ratio
         * @param $height_ratio
         * @param $file_path
         * @return resource
         */
        public function get_resize_image($width_ratio, $height_ratio, $file_path){

            $this->file_path = $file_path;
            $this->file_name = $this->get_file_name($file_path);
            $this->file_type = $this->get_file_extension($file_path);
            switch ($this->file_type){
                case 'jpg':
                    $img = imagecreatefromjpeg($file_path);
                    break;
                case 'png':
                    $img = imagecreatefrompng($file_path);
                    break;
            }

            $image_info = getimagesize($file_path);
            $width  = $image_info[0];    // width of the image
            $height = $image_info[1];    // height of the image

            //resizing the image
            $new_width  = round ($width * $width_ratio);
            $new_height = round ($height * $height_ratio);

            //creating a new image
            $new_image = imagecreate($new_width, $new_height);
            imagecopyresized($new_image, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            return $new_image;
        }

        /**
         * this method resize the image with the given pixel value
         * @param int $width_pixels
         * @param int $height_pixels
         * @return $this
         */
        public function resize_image_pixels($width_pixels = 400, $height_pixels = 250){
            $image_info = getimagesize($this->file_path);

            $width  = $image_info[0];    // width of the image
            $height = $image_info[1];    // height of the image

            //resizing the image
            $new_width  = intval($width_pixels);
            $new_height = intval($height_pixels);

            //creating a new image
            $new_image = imagecreate($new_width, $new_height);
            imagecopyresized($new_image, $this->image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $this->clone_image_src_dest($new_image, $this->image);
//            $this->save_image('resizePixels_');
            return $this;
        }

        /**
         * this method add text on the image and the text input is as string
         * you can select the color of the text using RGB value
         * @param string $text
         * @param int $red
         * @param int $green
         * @param int $blue
         * @return $this
         */
        public function text_on_image($text = 'haven.com', $red = 255, $green = 0, $blue = 0){
            $color = imagecolorallocate($this->image, $red, $green, $blue);
            imagestring($this->image, 5, 800, 600, $text, $color);
            //saving the file with prefix
//            $this->save_image('text_');
            return $this;
        }

        /**
         * this method will flip the image according to the direction
         * x-> HORIZONTAL
         * y-> VERTICAL
         * both-> FLIP_BOTH
         * @param $direction
         * @return $this
         */
        public function flip_image($direction){
            switch ($direction){
                case 'x':
                    imageflip($this->image, IMG_FLIP_HORIZONTAL);
//                    $this->save_image('flipX_');
                    break;
                case 'y':
                    imageflip($this->image, IMG_FLIP_VERTICAL);
//                    $this->save_image('flipY_');
                    break;
                case 'both':
                    imageflip($this->image, IMG_FLIP_BOTH);
//                    $this->save_image('flipBoth_');
                    break;
            }
            return $this;
        }

        /**
         * this method add a border on the image and the border thickness depends on the $thickness var
         * @param int $thickness
         * @param int $red
         * @param int $green
         * @param int $blue
         * @return $this
         */
        public function border_on_image($thickness = 10, $red = 255, $green = 0, $blue = 0){
            //width and height of the image
            $width = imagesx($this->image);
            $height = imagesy($this->image);

            $dest = imagecreatetruecolor($width + $thickness * 2, $height + $thickness * 2);

            $dest = $this->get_border_color($dest, $red, $green, $blue);

//            $this->display_image($dest);

            // Copy
            imagecopy($dest, $this->image, $thickness, $thickness, 0, 0, $width, $height);
//            $this->display_image($dest); //its displaying the perfect image, after cloning the pic reduce quality
            $this->clone_image_src_dest($dest, $this->image);
            //saving the file with prefix
//            $this->save_image('border_');
            return $this;
        }

        /**
         * takes the image and the RGB color to make the border
         * @param $image
         * @param $red
         * @param $green
         * @param $blue
         * @return mixed
         */
        function get_border_color($image, $red = 0, $green = 0, $blue = 0){
            // sets background to red
            $color = imagecolorallocate($image, $red, $green, $blue);
            imagefill($image, 0, 0, $color);
            return $image;
        }

        /**
         * this method will create effect on the image. Various type of effect can be imposed on the image
         * according to the case selection. The case name refers the effect that going to apply on the image.
         * to be continued.
         * @param $effect
         * @param int $level
         * @param int $red
         * @param int $green
         * @param int $blue
         * @param int $opacity
         * @return $this
         */
        public function GD_effect($effect = 'negative', $level = 100, $red = 124, $green = 34, $blue = 200, $opacity =10){
            switch ($effect){
                case 'blur':
                    for ($i = 0; $i < 10; $i++){
                        imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR);
                    }
//                    $this->save_image('GD_effect_');
                    break;
                case 'selective_blur':
                    for ($i = 0; $i < 10; $i++) {
                        imagefilter($this->image, IMG_FILTER_SELECTIVE_BLUR);
                    }
//                    $this->save_image('GD_effect_');
                    break;
                case 'negative':
                    imagefilter($this->image, IMG_FILTER_NEGATE);
//                    $this->save_image('GD_effect_');
                    break;
                case 'gray':
                    imagefilter($this->image, IMG_FILTER_GRAYSCALE);
//                    $this->save_image('GD_effect_');
                    break;
                case 'edge_detection':
                    imagefilter($this->image, IMG_FILTER_EDGEDETECT);
//                    $this->save_image('GD_effect_');
                    break;
                case 'emboss':
                    imagefilter($this->image, IMG_FILTER_EMBOSS);
//                    $this->save_image('GD_effect_');
                    break;
                case 'sketchy':
                    imagefilter($this->image, IMG_FILTER_MEAN_REMOVAL);
//                    $this->save_image('GD_effect_');
                    break;
                case 'brightness':
                    imagefilter($this->image, IMG_FILTER_BRIGHTNESS, $level);
//                    $this->save_image('GD_effect_');
                    break;
                case 'contrast':
                    imagefilter($this->image, IMG_FILTER_CONTRAST, $level);
//                    $this->save_image('GD_effect_');
                    break;
                case 'smooth':
                    imagefilter($this->image, IMG_FILTER_SMOOTH, $level);
//                    $this->save_image('GD_effect_');
                    break;
                case 'pixels':
                    imagefilter($this->image, IMG_FILTER_PIXELATE, $level,true);
//                    $this->save_image('GD_effect_');
                    break;
                case 'colorize':
                    imagefilter($this->image, IMG_FILTER_COLORIZE, $red, $green, $blue, $opacity);
//                    $this->save_image('GD_effect_');
                    break;
            }
            return $this;
        }

        /**
         * this method create a water mark image first then merge it to the original image
         * @param int $opacity
         * @param int $margin_right
         * @param int $margin_bottom
         * @return $this
         */
        public function watermark_image($opacity = 40, $margin_right = 20, $margin_bottom = 20){
            // First we create our stamp image manually from GD
            $stamp = imagecreatetruecolor(200, 70);

            imagefilledrectangle($stamp, 0, 0, 199, 169, 0x0000FF);
            imagefilledrectangle($stamp, 9, 9, 190, 60, 0xFFFFFF);
            imagestring($stamp, 5, 20, 20, 'Balustor Blog', 0x0000FF);
            imagestring($stamp, 3, 20, 40, '(c) 2018', 0x0000FF);

            // Set the margins for the stamp and get the height/width of the stamp image
            $right = $margin_right;
            $bottom = $margin_bottom;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Merge the stamp onto our photo with an opacity of 50%
            imagecopymerge($this->image, $stamp, imagesx($this->image) - $sx - $right, imagesy($this->image) - $sy - $bottom, 0, 0, imagesx($stamp), imagesy($stamp), $opacity);

//            $this->save_image('waterMark_');
            return $this;
        }

        /**
         * this method watermark the original image with the given image, first it resize the given
         * image then it create the watermark of the image
         * @param $image_path
         * @param int $opacity
         * @return $this
         */
        public function watermark_with_image($image_path, $opacity = 40){
            // Load the stamp and the photo to apply the watermark to
            $stamp = $this->get_resize_image(.2, .2,$image_path);

            // Set the margins for the stamp and get the height/width of the stamp image
            $marge_right = 10;
            $marge_bottom = 10;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
//            imagecopy($this->image, $stamp, imagesx($this->image) - $sx - $marge_right, imagesy($this->image) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
            imagecopymerge($this->image, $stamp, imagesx($this->image) - $sx - $marge_right, imagesy($this->image) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), $opacity);

//            $this->save_image('watermak_with_image_');
            return $this;
        }

        /**
         * this method stamped the original image with the given image. The given image has to be
         * properly sized. The $marge_right & $marge_bottom will position the stamp on the image
         * @param $file_path
         * @param int $marge_right
         * @param int $marge_bottom
         * @return $this
         */
        public function stamp_on_image($file_path, $marge_right = 40,$marge_bottom = 20){
            // Load the stamp and the photo to apply the watermark to
            $file_type = $this->get_file_extension($file_path);
            switch ($file_type){
                case 'jpg':
                    $stamp = imagecreatefromjpeg($file_path);
                    break;
                case 'png':
                    $stamp = imagecreatefrompng($file_path);
                    break;
            }

            // Set the margins for the stamp and get the height/width of the stamp image
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($this->image, $stamp, imagesx($this->image) - $sx - $marge_right, imagesy($this->image) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

//            $this->save_image('stamp_on_image_');
            return $this;
        }

        /**
         * this method creates the thumbnail of the original pic according to the thumb ratio
         * @param $thumb_ratio
         * @return $this
         */
        public function thumbnail($thumb_ratio){
            $width = imagesx($this->image);
            $height = imagesy($this->image);

            //get the min value of the between height and width
            $min_value = min($width, $height);

            $thumb_width = intval($min_value / $thumb_ratio);
            $thumb_height = intval($min_value / $thumb_ratio);

            $thumbnail = imagecreatetruecolor($thumb_width, $thumb_height);

            imagecopyresampled($thumbnail, $this->image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
//            $this->display_image($thumbnail);
            $this->clone_image_src_dest($thumbnail, $this->image);
//            $this->save_image('thumb_');

            return $this;
        }

        /**
         * simply returns the Aspect ratio of the image
         * @param $image
         * @return float|int
         */
        public function getAspectRatio($image){
            return imagesx($image) / imagesy($image);
        }

        /**
         * creates the aqua effect on the image
         * @return $this
         */
        public function insta_aqua() {
//            $this->clone_image_resource();
            imagefilter($this->image, IMG_FILTER_COLORIZE, 0, 70, 0, 30);
//            $this->save_image('insta_aqua_');
            return $this;
        }

        /**
         * creates the sepia effect on the image
         * @return $this
         */
        public function insta_sepia() {
//            $this->clone_image_resource();
            imagefilter($this->image, IMG_FILTER_GRAYSCALE);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 100, 50, 0);
//            $this->save_image('insta_sepia_');
            return $this;
        }

        /**
         * creates the cool effect on the image
         * @return $this
         */
        public function insta_cool() {
            imagefilter($this->image, IMG_FILTER_MEAN_REMOVAL);
            imagefilter($this->image, IMG_FILTER_CONTRAST, -50);
            return $this;
        }

        /**
         * creates the light effect on the image
         * @return $this
         */
        public function insta_light() {
            imagefilter($this->image, IMG_FILTER_BRIGHTNESS, 10);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 100, 50, 0, 10);
            return $this;
        }

        /**
         * creates the fuzzy effect on the image
         * @return $this
         */
        public function insta_fuzzy() {
            $gaussian = array(
                array(1.0, 1.0, 1.0),
                array(1.0, 1.0, 1.0),
                array(1.0, 1.0, 1.0)
            );
            imageconvolution($this->image, $gaussian, 9, 20);
            return $this;
        }

        /**
         * creates the boost effect on the image
         * @return $this
         */
        public function insta_boost() {
            imagefilter($this->image, IMG_FILTER_CONTRAST, -35);
            imagefilter($this->image, IMG_FILTER_BRIGHTNESS, 10);
            return $this;
        }

        /**
         * creates the antique effect on the image
         * @return $this
         */
        public function insta_antique() {
            imagefilter($this->image, IMG_FILTER_BRIGHTNESS, 0);
            imagefilter($this->image, IMG_FILTER_CONTRAST, -30);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 75, 50, 25);
            return $this;
        }

        /**
         * creates the vintage effect on the image
         * @return $this
         */
        public function insta_vintage() {
            imagefilter($this->image, IMG_FILTER_BRIGHTNESS, 10);
            imagefilter($this->image, IMG_FILTER_GRAYSCALE);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 40, 10, -15);
            return $this;
        }

        /**
         * creates the everglow effect on the image
         * @return $this
         */
        public function insta_everglow() {
            imagefilter($this->image, IMG_FILTER_BRIGHTNESS, -30);
            imagefilter($this->image, IMG_FILTER_CONTRAST, -5);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 30, 30, 0);
            return $this;
        }

        /**
         * creates the freshblue effect on the image
         * @return $this
         */
        public function insta_freshblue() {
            imagefilter($this->image, IMG_FILTER_CONTRAST, -5);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 20, 0, 80, 60);
            return $this;
        }

        /**
         * creates the tender effect on the image
         * @return $this
         */
        public function insta_tender() {
            imagefilter($this->image, IMG_FILTER_CONTRAST, 5);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 80, 20, 40, 50);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 0, 40, 40, 100);
            imagefilter($this->image, IMG_FILTER_SELECTIVE_BLUR);
            return $this;
        }

        /**
         * creates the dream effect on the image
         * @return $this
         */
        public function insta_dream() {
            imagefilter($this->image, IMG_FILTER_COLORIZE, 150, 0, 0, 50);
            imagefilter($this->image, IMG_FILTER_NEGATE);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 0, 50, 0, 50);
            imagefilter($this->image, IMG_FILTER_NEGATE);
            imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR);
            return $this;
        }

        /**
         * creates the frozen effect on the image
         * @return $this
         */
        public function insta_frozen() {
            imagefilter($this->image, IMG_FILTER_BRIGHTNESS, -15);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 0, 0, 100, 50);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 0, 0, 100, 50);
            imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR);
            return $this;
        }

        /**
         * creates the sharpen effect on the image
         * @return $this
         */
        public function insta_sharpen() {

            $gaussian = array(
                array(1.0, 1.0, 1.0),
                array(1.0, -7.0, 1.0),
                array(1.0, 1.0, 1.0)
            );
            imageconvolution($this->image, $gaussian, 1, 4);
//            $this->save_image('insta_sharpen_');
            return $this;
        }

        /**
         * creates a radium effect on the image
         * @return $this
         */
        public function radium(){
            // change gamma value of the pic
            imagegammacorrect($this->image, 1.0, .1);
            return $this;
        }

        /**
         * this function will upload image to the images folder
         */
        public function upload_image(){
            if(isset($_FILES['image'])){
                $errors= array();
                $file_name = $_FILES['image']['name'];
                $file_size =$_FILES['image']['size'];
                $file_tmp =$_FILES['image']['tmp_name'];
//                $file_type=$_FILES['image']['type'];

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
}

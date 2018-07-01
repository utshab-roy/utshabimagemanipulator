<?php
namespace Image{
    class Image_Manipulation{
        public $file;
        private  $data;
//  boolean variables
        public $rotation, $resize, $texted, $stamped, $watermarked, $thumbnailed, $croped, $flip_vertically, $flip_horizontally, $grayed, $watermarked2, $flip_both, $bordered = false;

//  variables to store the name of the files
        public $rotated_file, $resize_file, $texted_file, $stamped_file, $watermarked_file, $thumbnail_file, $croped_file, $fliped_vertically_file, $fliped_horizontally_file, $gray_pic_file,$watermarked2_file, $fliped_both_file, $bordered_file;

//  rotation degree value
        public $rotation_deg;
//    resize values for resizing the pic height and width
        public $resize_width, $resize_height;
//    text on the pic
        public $text_on_pic;
//    thumb ratio value
        public $thumb_ratio;

        public function __construct()
        {
//        $this->file = $file;
            //this line may redirect to the image viewer
//        header('Content-type: image/jpeg');
        }




        /**
         * Rotate image by degree the parameter takes a int value
         *
         * @param int $deg
         */
        public function rotate_image($deg = 45){

            if(isset($this->file)){
                $deg = floatval($deg);
                $this->data = imagecreatefromjpeg('images/'.$this->file);
                //getting the file extension
                $file_ext = $this->get_file_extension($this->file);
                //creating the image according to file type
                $this->data = $this->image_create_according_to_file_extension($this->file,$file_ext);
                imagesetinterpolation($this->data, IMG_BELL);
                $image_rotated = imagerotate($this->data, $deg, 0);
                //saving the file with prefix
                $this->save_file($image_rotated,'rotated_',$this->file,$file_ext);
                $this->rotated_file = 'rotated_'.$this->file;
                imagedestroy($this->data);

                $this->rotation = true;
            }

        }

        /**
         * resize the original image. these two variables provides the ratio of the width and height
         * @param float $width_ratio
         * @param float $height_ratio
         */

        public function resize_image($width_ratio = 0.5, $height_ratio = 0.5){
            if(isset($this->file)){
                $this->data = imagecreatefromjpeg('images/'.$this->file);
                $image_info = getimagesize('images/'.$this->file);

                $width  = $image_info[0];    // width of the image
                $height = $image_info[1];    // height of the image

                //resizing the image
                $new_width  = round ($width * $width_ratio);
                $new_height = round ($height * $height_ratio);

                //creating a new image
                $new_image = imagecreate($new_width, $new_height);
                imagecopyresized($new_image, $this->data, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                imagejpeg($new_image,'manipulated_image/resized_'.$this->file, 100);
                $this->resize_file = 'resized_'.$this->file;
                imagedestroy($new_image);

                $this->resize = true;
            }
        }

        /**
         * this will add text on the image and the text will be taken as string
         * @param $text
         */
        public function add_text_on_image($text){
            if(isset($this->file)) {
                //getting the file extension
                $file_ext = $this->get_file_extension($this->file);
                //creating the image according to file type
                $this->data = $this->image_create_according_to_file_extension($this->file,$file_ext);
                $bluecolor = imagecolorallocate($this->data, 0, 0, 255);
                imagestring($this->data, 5, 500, 500, $text, $bluecolor);

                //saving the file with prefix
                $this->save_file($this->data,'texted_', $this->file, $file_ext);

                $this->texted_file = 'texted_' . $this->file;
                imagedestroy($this->data);
                $this->texted = true;
            }
        }

        /**
         * this method will add a image on the original pic. temp pic been copied and paste on the original pic.
         * here right and bottom variable just positioning the stamp
         */

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

        /**
         * this method will add watermark on the original pic
         * the watermark pic has to provide
         */

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
         * creates the thumbnail of the image and the ratio to shorter the pic
         * @param $thumb_ratio
         */
        public function create_thumbnail($thumb_ratio){
            if(isset($this->file)) {
                $this->data = imagecreatefromjpeg('images/' . $this->file);

                $width = imagesx($this->data);
                $height = imagesy($this->data);

                $thumb_width = intval($width / $thumb_ratio);
                $thumb_height = intval($height / $thumb_ratio);

                $thumbnail = imagecreatetruecolor($thumb_width, $thumb_height);

                imagecopyresampled($thumbnail, $this->data, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);

                imagejpeg($thumbnail, 'manipulated_image/thumbnail_' . $this->file, 100);
                $this->thumbnail_file = 'thumbnail_' . $this->file;
                imagedestroy($thumbnail);
                imagedestroy($this->data);
                $this->thumbnailed = true;
            }
        }

        /**
         * this method crop the image, the max limit is the min size between the height & width
         * right now it will crop like a square of the min size of height or width
         */

        public function crop_image(){
            if(isset($this->file)) {
                $this->data = imagecreatefromjpeg('images/' . $this->file);
                $size = min(imagesx($this->data), imagesy($this->data));


                $im2 = imagecrop($this->data, ['x' => 0, 'y' => 0, 'width' => ($size), 'height' => ($size)]);
                if ($im2 !== FALSE) {
                    imagejpeg($im2, 'manipulated_image/croped_' . $this->file, 100);
                    $this->croped_file = 'croped_' . $this->file;
                    imagedestroy($im2);
                }
                imagedestroy($this->data);
                $this->croped = true;
            }
        }

        /**
         * this method will flip the image according to the direction
         * x-> HORIZONTAL
         * y-> VERTICAL
         * both-> FLIP_BOTH
         * @param $direction
         */
        public function flip_image($direction){
            if(isset($this->file)) {
                $file_ext = $this->get_file_extension($this->file);
                $this->data = $this->image_create_according_to_file_extension($this->file,$file_ext);
                switch ($direction){
                    case 'x':
                        imageflip($this->data, IMG_FLIP_HORIZONTAL);
                        $this->save_file($this->data,'fliped_horizontal_',$this->file,$file_ext);
                        $this->fliped_horizontally_file = 'fliped_horizontal_' . $this->file;
                        imagedestroy($this->data);
                        $this->flip_horizontally = true;
                        break;
                    case 'y':
                        imageflip($this->data, IMG_FLIP_VERTICAL);
                        $this->save_file($this->data,'fliped_vertical_',$this->file,$file_ext);
                        $this->fliped_vertically_file = 'fliped_vertical_' . $this->file;
                        imagedestroy($this->data);
                        $this->flip_vertically = true;
                        break;
                    case 'both':
                        imageflip($this->data, IMG_FLIP_BOTH);
                        $this->save_file($this->data,'fliped_both_',$this->file,$file_ext);
                        $this->fliped_both_file = 'fliped_both_' . $this->file;
                        imagedestroy($this->data);
                        $this->flip_both = true;
                        break;
                }
            }
        }

        /**
         * this method convert the original picture to gray scale
         */
        public function gray_scale(){
            if(isset($this->file)) {
                //getting the file extension of the image
                $file_ext = $this->get_file_extension($this->file);

                //creating the image
                $this->data = $this->image_create_according_to_file_extension($this->file,$file_ext);

                $width = imagesx($this->data);
                $height = imagesy($this->data);

                for ($i=0; $i<$width; $i++)
                {
                    for ($j=0; $j<$height; $j++)
                    {
                        // get the rgb value for current pixel
                        $rgb = ImageColorAt($this->data, $i, $j);
                        // extract each value for r, g, b
                        $rr = ($rgb >> 16) & 0xFF;
                        $gg = ($rgb >> 8) & 0xFF;
                        $bb = $rgb & 0xFF;
                        // get the Value from the RGB value
                        $g = round(($rr + $gg + $bb) / 3);
                        // grayscale values have r=g=b=g
                        $val = imagecolorallocate($this->data, $g, $g, $g);
                        // set the gray value
                        imagesetpixel ($this->data, $i, $j, $val);
                    }
                }

                $this->save_file($this->data,'gray_',$this->file,$file_ext);
                $this->gray_pic_file = 'gray_' . $this->file;
                imagedestroy($this->data);
                $this->grayed = true;
            }
        }

        public function watermark_two(){
            if(isset($this->file)) {
                // Load the stamp and the photo to apply the watermark to
                $stamp = $this->resize_img(.2, .2,'books2.jpg');
                $this->data = imagecreatefromjpeg('images/' . $this->file);

                // Set the margins for the stamp and get the height/width of the stamp image
                $marge_right = 10;
                $marge_bottom = 10;
                $sx = imagesx($stamp);
                $sy = imagesy($stamp);

                // Copy the stamp image onto our photo using the margin offsets and the photo
                // width to calculate positioning of the stamp.
                imagecopy($this->data, $stamp, imagesx($this->data) - $sx - $marge_right, imagesy($this->data) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

                // Output and free memory
                imagejpeg($this->data, 'manipulated_image/watermark2_' . $this->file, 100);
                $this->watermarked2_file = 'watermark2_' . $this->file;
                imagedestroy($this->data);
                $this->watermarked2 = true;
            }
        }

        /**
         * this method resize the image and returns the image file resource
         * @param $width_ratio
         * @param $height_ratio
         * @param $img_filename
         * @return resource
         */
        public function resize_img($width_ratio, $height_ratio, $img_filename){

            $file_ext = $this->get_file_extension($img_filename);

            $img = $this->image_create_according_to_file_extension($img_filename,$file_ext);

            $image_info = getimagesize('images/'.$img_filename);

            $width  = $image_info[0];    // width of the image
            $height = $image_info[1];    // height of the image

            //resizing the image
            $new_width  = round ($width * $width_ratio);
            $new_height = round ($height * $height_ratio);

            //creating a new image
            $new_image = imagecreate($new_width, $new_height);
            imagecopyresized($new_image, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            $this->save_file($new_image, 'resized_img_',$img_filename,$file_ext);

            return $new_image;
        }

        /**
         * add a border to the original image, the border size has to given as pixel
         * and the color of the border will be according to RGB color, default border color is black
         * @param $border_pixel
         * @param $red
         * @param $green
         * @param $blue
         */

        public function add_border_to_image($border_pixel = 10, $red = 0, $green = 0, $blue = 0){
            if(isset($this->file)) {
                //getting the file extension of the image
                $file_ext = $this->get_file_extension($this->file);
                //creating the image instances
                $this->data = $this->image_create_according_to_file_extension($this->file,$file_ext);

                $sx = imagesx($this->data);
                $sy = imagesy($this->data);

                $dest = imagecreatetruecolor($sx + $border_pixel * 2, $sy + $border_pixel * 2);

                $dest = $this->border_color($dest, $red, $green, $blue);

                // Copy
                imagecopy($dest, $this->data, $border_pixel, $border_pixel, 0, 0, $sx, $sy);

                //saving the file with prefix
                $this->save_file($dest,'border_',$this->file,$file_ext);
                $this->bordered_file = 'border_'.$this->file;

                // Output and free from memory
                imagedestroy($dest);
                imagedestroy($this->data);
                $this->bordered = true;
            }
        }

        /**
         * takes the image and the RGB color to make the border
         * @param $image
         * @param $red
         * @param $green
         * @param $blue
         * @return mixed
         */
        function border_color($image, $red = 0, $green = 0, $blue = 0){
            // sets background to red
            $color = imagecolorallocate($image, $red, $green, $blue);
            imagefill($image, 0, 0, $color);
            return $image;
        }

        /**
         * this function returns the file extension of the file
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
         *this method saves the image according to it's file type
         * have to provide the image file, prefix text that is going to add before the image file name
         * the original file name that will be the last part of the manipulated image
         * and last the file extension for the type of the image
         * @param $new_image
         * @param $prefix_of_filename
         * @param $original_filename
         * @param $file_ext
         */
        public function save_file($new_image, $prefix_of_filename, $original_filename, $file_ext){
            switch ($file_ext){
                case 'png':
                    imagepng($new_image,'manipulated_image/'.$prefix_of_filename.$original_filename, 9);
                    break;
                case 'jpg':
                    imagejpeg($new_image,'manipulated_image/'.$prefix_of_filename.$original_filename, 100);
                    break;
            }
        }

        /**
         * @param $img_filename
         * @param $file_ext
         * @return resource
         */
        public function image_create_according_to_file_extension($img_filename,$file_ext){

            switch ($file_ext){
                case 'png':
                    $img = imagecreatefrompng('images/' . $img_filename);
                    break;
                case 'jpg':
                    $img = imagecreatefromjpeg('images/' . $img_filename);
                    break;
            }
            return $img;
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
}

<?php

namespace Image{
    class Instagram_Effect{
        protected $image;
        protected $original_image;
        protected $directory;
        protected $file_type;
        protected $file_name;

        public function __construct($file_name)
        {
            //make a directory to store the images
            $this->make_directory();
            $this->file_name = $this->get_file_name($file_name);
            $this->file_type = $this->get_file_extension($file_name);
            switch ($this->file_type){
                case 'jpg':
                    $this->original_image = imagecreatefromjpeg($file_name);
                    break;
                case 'png':
                    $this->original_image = imagecreatefrompng($file_name);
                    break;
            }
        }



        public function aqua() {
            $this->clone_image_resource();
            imagefilter($this->image, IMG_FILTER_COLORIZE, 0, 70, 0, 30);
            $this->save_image('insta_aqua_');
            imagedestroy($this->image);
            return $this;
        }

        public function sepia() {
            $this->clone_image_resource();
            imagefilter($this->image, IMG_FILTER_GRAYSCALE);
            imagefilter($this->image, IMG_FILTER_COLORIZE, 100, 50, 0);
            $this->save_image('insta_sepia_');
            imagedestroy($this->image);
            return $this;
        }

        public function sharpen() {
            $this->clone_image_resource();
            $gaussian = array(
                array(1.0, 1.0, 1.0),
                array(1.0, -7.0, 1.0),
                array(1.0, 1.0, 1.0)
            );
            imageconvolution($this->image, $gaussian, 1, 4);
            $this->save_image('insta_sharpen_');
            imagedestroy($this->image);
            return $this;
        }

        public function get_file_extension($file_name){
            //checks the file extension
            $tmp = explode('.', $file_name);
            $file_ext = end($tmp);
            return $file_ext;
        }

        public function get_file_name($file_name){
            //getting the file name
            $tmp = explode('/', $file_name);
            $file_name = end($tmp);
            return $file_name;
        }

        public function clone_image_resource(){
            $width  = imagesx($this->original_image);
            $height = imagesy($this->original_image);
            $this->image = imagecreatetruecolor($width, $height);

            imagecopy($this->image, $this->original_image, 0, 0, 0, 0, $width, $height);
        }

        public function make_directory($folder_name = 'Insta_Effect'){
            //this will create the manipulated_image folder it not exists.
            if (!file_exists($folder_name)) {
                mkdir($folder_name, 0777, true);
            }
        }


        public function save_image($prefix_of_filename){
            switch ($this->file_type){
                case 'png':
                    imagepng($this->image,'Insta_Effect/'.$prefix_of_filename.$this->file_name, 9);
                    break;
                case 'jpg':
                    imagejpeg($this->image,'Insta_Effect/'.$prefix_of_filename.$this->file_name, 100);
                    break;
            }
        }
    }
}
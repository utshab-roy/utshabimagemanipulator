<?php
include 'autoload.php';

//this will create the manipulated_image folder it not exists.
if (!file_exists('manipulated_image')) {
    mkdir('manipulated_image', 0777, true);
}

$img = new Image\Image_Manipulation(); //does not require () at the end. why ?

if(isset($_POST['rotate']) && intval($_POST['rotate']) >0){
    $img->rotation_deg = intval($_POST['rotate']);
}else{
    $img->rotation_deg = 90;
}

if(isset($_POST['resize_width']) && floatval($_POST['resize_width']) > 0){
    $img->resize_width = floatval($_POST['resize_width']);
}else{
    $img->resize_width = 0.5;
}

if(isset($_POST['resize_height']) && floatval($_POST['resize_height']) > 0){
    $img->resize_height = floatval($_POST['resize_height']);
}else{
    $img->resize_height = 0.5;
}

if(isset($_POST['text_on_pic']) && strval($_POST['text_on_pic']) != ''){
    $img->text_on_pic = strval($_POST['text_on_pic']);
}else{
    $img->text_on_pic = 'default text: balustor.com';
}


if(isset($_POST['thumbnail_ratio']) && intval($_POST['thumbnail_ratio']) > 0){
    $img->thumb_ratio = intval($_POST['thumbnail_ratio']);
}else{
    $img->thumb_ratio = 5;
}


$img->upload_image();
//$img->rotate_image($img->rotation_deg);
//$img->resize_image($img->resize_width, $img->resize_height);
//
//$img->add_text_on_image($img->text_on_pic);
//$img->add_stamp_on_image();
//$img->add_watermark_on_image();
//$img->create_thumbnail($img->thumb_ratio);
//$img->crop_image();
////$img->flip_image_vertically();
////$img->flip_image_horizontally();
//$img->flip_image('x');
//$img->flip_image('y');
//$img->flip_image('both');
//$img->gray_scale();
//$img->watermark_two();
$img->add_border_to_image(5, 25, 141, 214);
//$img->add_effect_on_image('colorize', '',0,0,200,50);
$img->add_effect_on_image('colorize',30,214, 24, 97);
$img->best_fit(500, 500);

$image = new Image\Instagram_Effect('images/books1.jpg');
$image  ->aqua()
        ->sepia()
        ->sharpen();

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

    <div class="form-group">
        <label for="exampleFormControlFile1">Example file input</label>
        <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
    </div>

    <div class="col-6 mb-2">
        <label for="rotation_degree"><b>Rotation Degree(default 90):</b></label>
        <input type="text" class="form-control" name="rotate" value="" id="rotation_degree" placeholder="degree value">
<!--    </div>-->

<!--    <div class="form-inline mb-2 col-12">-->
        <label for="resize"><b>Resize width(ratio to original pic):</b></label>
        <input type="text" class="form-control" name="resize_width" value="" id="resize_width" placeholder="width value">
        <label for="resize"><b>Resize height(ratio to original pic):</b></label>
        <input type="text" class="form-control" name="resize_height" value="" id="resize_height" placeholder="height value">

        <label for="text_on_pic"><b>Text we want on the pic :</b></label>
        <input type="text" class="form-control" name="text_on_pic" value="" id="text_on_pic" placeholder="Text field">

        <label for="thumbnail"><b>Thumbnail Ratio (default value 5):</b></label>
        <input type="text" class="form-control" name="thumbnail_ratio" value="" id="thumbnail" placeholder="thumbnail ratio">

        <input class="btn btn-danger mt-3" type="submit"/>
    </div>


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

if ($img->thumbnailed == true){
    echo '<br/><h3>Thumbnail of the Pic</h3></br>';
    echo "<img src='manipulated_image/$img->thumbnail_file' >";
}

if ($img->croped == true){
    echo '<br/><h3>Croped Pic</h3></br>';
    echo "<img src='manipulated_image/$img->croped_file' >";
}

if ($img->flip_vertically == true){
    echo '<br/><h3>flip_vertically Pic</h3></br>';
    echo "<img src='manipulated_image/$img->fliped_vertically_file' width='500' height='333' >";
}

if ($img->flip_horizontally == true){
    echo '<br/><h3>flip_horizontally Pic</h3></br>';
    echo "<img src='manipulated_image/$img->fliped_horizontally_file' width='500' height='333' >";
}

if($img->flip_both == true){
    echo '<br/><h3>Flip Both</h3></br>';
    echo "<img src='manipulated_image/$img->fliped_both_file' width='500' height='333' >";
}

if ($img->grayed == true){
    echo '<br/><h3>Gray Pic</h3></br>';
    echo "<img src='manipulated_image/$img->gray_pic_file' width='500' height='333' >";
}

if($img->watermarked2 == true){
    echo '<br/><h3>Watermarked 2 Pic</h3></br>';
    echo "<img src='manipulated_image/$img->watermarked2_file' width='500' height='333' >";
}

if($img->bordered == true){
    echo '<br/><h3>Image with Border</h3></br>';
    echo "<img src='manipulated_image/$img->bordered_file' width='500' height='333' >";
}

if($img->effected == true){
    echo '<br/><h3>Effected Image</h3></br>';
    echo "<img src='manipulated_image/$img->effected_file' width='500' height='333' >";
}

if($img->bestFit == true){
    echo '<br/><h3>Best Fit</h3></br>';
    echo "<img src='manipulated_image/$img->bestFit_file' >";
}




?>

</body>
</html>

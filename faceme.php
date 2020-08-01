
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
error_log($target_file);
//rename file to avoid cache issues
$newname = time(). basename($_FILES["fileToUpload"]["name"]);

$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
	//echo "path:".$_FILES["fileToUpload"]["tmp_name"];
        $uploadOk = 1;
	error_log("upload SUCCESS");
    } else {
        $uploadOk = 0;
	error_log("failed to upload file");
    }
// Check if file already exists
if (file_exists($target_file)) {
    //Sorry, file already exists
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 15000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded, our engineers are working on this.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir.$newname)) {
        echo "";
	error_log("move SUCCESS");
    } else {
        echo "Sorry, there was an error scanning your file.".$_FILES["fileToUpload"]["tmp_name"]."<br/>";
    }
}


}//end form action

// echo memory used
error_log("memory used:".memory_get_usage());

?>

<?php

#facedetect
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
$filename = "/var/www/html/radar/uploads/".$newname;
$exif = exif_read_data($filename, 0, true);

#shrink large images
$image="/var/www/html/radar/uploads/".$newname;
$target_file = "/var/www/html/radar/uploads/".$newname;
list($width, $height) = getimagesize($image);
$modwidth = 500;
$diff = $width / $modwidth;
$modheight = $height / $diff;
$dest = imagecreatetruecolor($modwidth, $modheight);
$src = imagecreatefromjpeg($image);

imagecopyresampled($dest, $src, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
imagejpeg($dest, $target_file, 100);

// echo memory used copy resampled
error_log("memory used2:".memory_get_usage());


imagedestroy($src);

#faceai
$faceurl="https://manageteam.online/uploads/".$newname;
$facedata = array("Url" => $faceurl);
$facedata_string = json_encode($facedata);

$facech = curl_init('https://australiaeast.api.cognitive.microsoft.com/face/v1.0/detect');
curl_setopt($facech, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($facech, CURLOPT_POSTFIELDS, $facedata_string);
curl_setopt($facech, CURLOPT_RETURNTRANSFER, true);
curl_setopt($facech, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Ocp-Apim-Subscription-Key: cc231bd1ce2e43a2b4a80c1acc88f1cb')
);

$faceresult = curl_exec($facech);

curl_close ($facech);

$facejson = json_decode($faceresult, true);
//troubleshooting debug
//var_dump(json_decode($faceresult));
error_log($faceresult);

//crop 
$width=$facejson['0']['faceRectangle']['width'];
$height=$facejson['0']['faceRectangle']['height'];
$x=$facejson['0']['faceRectangle']['left'];
$y=$facejson['0']['faceRectangle']['top'];
$image="/var/www/html/radar/uploads/".$newname;
$target_file = "/var/www/html/radar/uploads/".$newname;

//find size of one quarter of image
$headslices=$height/4;
error_log("heightslices::::".$headslices);

//start crop, crop forehead
$middlefacetop=$y;
$middlefaceheight=$y+$height;

$dest = imagecreatetruecolor($width, $middlefaceheight);
$src = imagecreatefromjpeg($image);
imagecopyresampled($dest, $src, 0, 0, $x, $middlefacetop, $width, $middlefaceheight, $width, $middlefaceheight);
imagejpeg($dest, $target_file . "_cropforehead.jpg", 100);

// echo memory used
error_log("memory used3:".memory_get_usage());

//image is cut into 4 pieces
$count=0;
echo '<article class="therapistsArea" style="font-family:Baskerville;">
<br/>
<Br/>
<img src="uploads/'.$newname.'_cropforehead.jpg">
<Br/><br/>
<h1><a href="uploads/'.$newname.'_cropforehead.jpg"> Download Face </a></h1>
';


}

?>

<html>
<head>

        <link rel="stylesheet" type="text/css" href="../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../css/main.css">

</head>

<body>
<div class="button"><a href="https://manageteam.online">GO BACK <br/> << </a> </div>
</body>
</html>

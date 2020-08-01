
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


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
$filename = "/var/www/html/radar/recom/uploads/".$newname;
$exif = exif_read_data($filename, 0, true);

#shrink large images
$image="/var/www/html/radar/recom/uploads/".$newname;
$target_file = "/var/www/html/radar/recom/uploads/".$newname;
list($width, $height) = getimagesize($image);
$modwidth = 500;
$diff = $width / $modwidth;
$modheight = $height / $diff;
$dest = imagecreatetruecolor($modwidth, $modheight);
$src = imagecreatefromjpeg($image);

imagecopyresampled($dest, $src, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
imagejpeg($dest, $target_file, 100);


$myresults = shell_exec("python3 /var/www/html/radar/recom/image.py --image ".$image);
$myjson = json_decode($myresults, true);
//var_dump(json_encode($myjson));


echo "<center><h1>Here are some recommended homes & styles for you:</h1>";

function test_print($item, $key)
{
    if($key == "thumbnailUrl")
    {
     echo "<img src='".$item."'>";
    }
}

array_walk_recursive($myjson, 'test_print');

echo "</center>";


imagedestroy($src);



}

?>

<html>
<head>

        <link rel="stylesheet" type="text/css" href="../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../css/main.css">

<style>
.button {
  background-color: #f44336;
  color: white;
  padding: 14px 0px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}

.button:hover, .button:active {
  background-color: red;
}
</style>

</head>

<body>

	<!-- BEGIN: banner wrapper -->
		<section id="bannerWrapper" style="background-color:#51e2f5;">
				
			<!-- BEGIN: banner area -->
			<article class="bannerArea" style="font-family:Baskerville; background-color:#51e2f5;">
				<div id="owl-one" class="owl-carousel">
				<center>
					<div class="item" style="background-color: pink;">
						
						<div class="mycontainer">
						
							<div class="overlay" style="background-color:white; padding:5px; ">
							
							<br/>
							<div >
<br/><br/>

                        <form action="#" method="post" enctype="multipart/form-data" name="myform" id="myform" onsubmit="DoSubmit();">
                        <label for="fileToUpload"><div style="color:#B6577B;font-family:Cambria;">


			<p><font color="black" size="3" face="verdana">

</font>

<h1>Upload or Take a picture of a home you like to see our recommendations on homes and styles you will like for sale!</h1>
<br><br/><b><div class="button" style="width:70%;"><h1>RECOMMENDED STYLES AND HOMES FOR YOU >></h1></div></b> </div></label>
                        <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;" accept="image/*" onchange="this.form.submit()" onclick="document.getElementById('loading').style.display = 'block';"/>
                         </form>


<br><br/>

<br/><br/>
		</div>
</center>




<div class="button"><a href="https://manageteam.online">GO BACK <br/> << </a> </div>
</body>
</html>

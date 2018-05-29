<?php
function imageValidate(){
	$target_dir ="product_pics/";  ///UPADTE THIS TO DATABASE AT SOME POINT
	
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$target_file = $target_dir .$id.".".$imageFileType; /// <- update name to path/id.filetype
	$error ="";
	
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["fileToUpload"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$error. "File is not an image.<br>";
			$uploadOk = 0;
			}
	}
	// Check if file already exists
	if (file_exists($target_file)) { 
		$error. "Sorry, file already exists.<br>";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		$error. "Sorry, your file is too large.<br>";
		$uploadOk = 0;
	}
	// Allow certain file formats INGNORE FOR NOW 
	
	if($imageFileType != "jpg" ) {
		$error. "Sorry, only JPG files are allowed.<br>";
		$uploadOk = 0;
	} 
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$error. "<br>File was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			$error. "Sorry, there was an unknown error (probably a premission error) uploading your file contact a system admin.";
		}
	}
}
	?>
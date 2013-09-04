<?php

try{

	if($_POST['send']){
		require_once $_SERVER['DOCUMENT_ROOT'].'/testserver/scripts/functions/fileuploader.php';

				/*$fileUploader=new FileUploader();
				if($fileUploader->upload()){
			
					print("upload successful for $fileName[$n] - $uploadedFile_size[$n] bytes<br>\n");
				} else { 
					print("error: upload failed<br>\n");
				}*/
				
				$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/rushcms/uploads';
				
				foreach ($_FILES["uploadedFile"] as $key => $value) {
					move_uploaded_file( 
						$_FILES["uploadedFile"]["tmp_name"][$key], 
						$upload_dir.'/'.$_FILES["uploadedFile"]["name"][$key] 
					) or die("Problems with upload");
				}




				
	}
}

catch(Exception $e){
	echo $e->getMessage();
	exit();
} 

?>


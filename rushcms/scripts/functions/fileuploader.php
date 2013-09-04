<?php

class FileUploader{

	private $uploadFile;	
	private $name;	
	private $tmp_name;	
	private $type;	
	private $size;	
	private $error;
 
	private $allowedTypes=array
	('image/jpeg','image/pjpeg','image/bmp','image/x-icon','image/gif','image/png');
	
	/**
		 * Fixes the odd indexing of multiple file uploads from the format:
		 *
		 * $_FILES['field']['key']['index']
		 *
		 * To the more standard and appropriate:
		 *
		 * $_FILES['field']['index']['key']
		 *
		 * @param array $files
		 * @author Corey Ballou
		 * @link http://www.jqueryin.com
		 */
		function fixFilesArray(&$files)
		{
			
		}
	
	public function __construct(){
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/rushcms/uploads/';
		if(!is_dir($uploadDir)){
			throw new Exception('Invalid upload directory.');
		}
		
		if(!count($_FILES)){
			throw new Exception('Invalid number of file upload parameters.');
		} 
		
		 
		
		$this->fixFilesArray($_FILES['uploadedFile']);
		foreach($_FILES['uploadedFile'] as $key=>$value){
			$names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);
		
			foreach ($_FILES['uploadedFile'] as $key => $part) {
				// only deal with valid keys and multiple files
				$key = (string) $key;
				if (isset($names[$key]) && is_array($part)) {
					foreach ($part as $position => $value) {
						$files[$position][$key] = $value;
					}
					// remove old key reference
					unset($files[$key]);
				}
			}
			
			$this->$key = $value;
		}
		
		if(!in_array($this->type,$this->allowedTypes)){
			throw new Exception('Invalid MIME type of target file. '.$this->type);
		}
		
		$this->uploadFile = $uploadDir.basename($this->name);
	
	}
	
	// upload target file to specified location
	
	public function upload(){
		
		if(move_uploaded_file($this->tmp_name,$this->uploadFile)){
			return true;
		}
	
		// throw exception according to error number
	
		switch($this->error){
			case 1:
				throw new Exception('Target file exceeds maximum allowed size.');
				break;
			case 2:
				throw new Exception('Target file exceeds the MAX_FILE_SIZE value specified on the upload form.');
				break;
			case 3:
				throw new Exception('Target file was not uploaded completely.');
				break;
			case 4:
				throw new Exception('No target file was uploaded.');
				break;
			case 6:
				throw new Exception('Missing a temporary folder.');
				break;
			case 7:
				throw new Exception('Failed to write target file to disk.');
				break;
			case 8:
				throw new Exception('File upload stopped by extension.');
				break;
			
		}
	}
}

?>
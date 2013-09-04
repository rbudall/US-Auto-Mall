<?
   // Config upload options
      define("ALL_LOWERCASE", true);  // Fixes a bug with capitalised file extensions
      $allowed_filetypes = array('.jpg', '.jpeg','.gif','.bmp','.png', '.ico','.JPG','.PNG','.JPEG','.GIF','.ICO','.BMP',); // These will be the types of file that will pass the validation.
      $max_filesize = 2097152; // Maximum filesize in BYTES (currently 2.0MB).
      $upload_path = $_SERVER['DOCUMENT_ROOT'].'/testserver/upload/'; // The place the files will be uploaded to (currently a 'upload' directory, remember it needs to be CHMOD 777).

   $filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

   // Checks file extension thats requested to be uploaded compared to the allowed_filetypes array, if allowed continue. If not on the allow list, DISPLAY ERROR 1
   if(!in_array($ext,$allowed_filetypes))
      die('<h3>OOPS!</h3>
	  <p>The file you attemped to upload is not allowed, make sure the file is on the allowed list and try again.</p>'); // ERROR 1

   // Checks file size to make sure it is below the maximum set by the max_filesize array. If under set value (Currently 2.0 MB) continue. If larger then set value, DISPLAY ERROR 2
   if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
      die('<h3>OOPS!</h3>
	  <p>The image you attempted to upload is too large.</p>'); // ERROR 2

   // Confirms the existance and access to the upload directory (Where uploads will be stored) if directory set is writable upload will process. If directory set doesn't exist or isn't accesible, DISPLAY PROCESS ERROR 1
   if(!is_writable($upload_path))
      die('You cannot upload to the specified directory, please CHMOD it to 777.'); // PROCESS ERROR 1

   // Uploader will upload the requested file to the path given.
   if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $filename))  

   // Upload successful, final step, generate embed codes. (My URL structure e.g. /testserver/upload/ is present in this code at the moment as in order to test out the uploader and embed codes yourself (For the live preview) I've set it up fully. Edit them to your website url and directories etc.. when you set it up on your own web-server

         echo '<h3>The Upload was successful</h3>
		 <p><strong>Your image upload of <em>'. $filename . '</em> was successful:</strong></p>
		 <img src="/testserver/upload/'.$filename.' " "alt="uploaded image"/>
		<p><strong>Your uploaded image is now live on our servers, scroll down below to obtain the URL to your file. Optionally you can use one of the embed methods which have been generated for you.</strong></p>
		 <table border="0" cellspacing="10" cellpadding=0">
		 <tr>
		 <td>If you just need the general link to your uploaded image then use this code below:</td>
		 </tr>
		 <tr>
         <td><strong>Direct link to image</strong>:<br/> <textarea cols="35" rows="3">wwww.strikeforcemusic.com/testserver/upload/'.$filename.'</textarea></td>
         </tr>
		 </table>
		 <table border="0" cellspacing="10" cellpadding="0">
		 <tr>
		 <td>If you have uploaded this image and need it embedding in a forum post then use the codes generated below!</td>
		 </tr>
		 <tr>
		 <td><strong>Embedding the image in a forum:</strong><br/> <textarea cols="35" rows="3">[IMG]wwww.strikeforcemusic.com/testserver/upload/'.$filename.'[/IMG]</textarea></td>
		 </tr>
		 </table>
		 <table border="0" cellspacing="10" cellpadding="0">
		 <tr>
		 <td>If you want to make your images have a hyperlink on a forum or website then use these codes:</td>
		 </tr>
		 <tr>
		 <td>
		 <strong>Hotlink for forums 1</strong>:<br/> <textarea cols="35" rows="3">[URL=http://yourlinkhere.com][IMG=wwww.strikeforcemusic.com/testserver/upload/'.$filename.'[/IMG][/URL]</textarea></td>
		 </tr>
		 <tr>
		 <td>
		 <strong>Hotlink for forums 2</strong>:<br/> <textarea cols="35" rows="3">[url=http://yourlinkhere.com][img=wwww.strikeforcemusic.com/testserver/upload/'.$filename.'[/img][/url]</textarea></td>
		 </tr>
		 <tr>
		 <td>
		 <strong>Hotlink for Websites</strong>:<br/> <textarea cols="35" rows="3"><a href="http://yourlinkhere.com"><img src="wwww.strikeforcemusic.com/testserver/upload/'.$filename.'" border="0" alt="image hosted at StrikeForceMusic.com"/></a></textarea></td>
         </tr>
         </table>
		 ';
      else
         echo '<h3>OOPS</h3>
		 <p><strong>There was an error during the image upload.</strong></p>'; // PROCESS ERROR 2 <IMG class=wp-smiley alt=:( src="http://www.james-blogs.com/wp-includes/images/smilies/icon_sad.gif"> .

?>
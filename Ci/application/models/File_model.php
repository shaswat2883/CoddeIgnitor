<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class File_model extends CI_Model{
		
		/* function to get the file content and segragate the data with new line*/
			public function getContent($reciept,$suppliers)
			{
				 $files = array($reciept,$suppliers);
				 $return_array = array();
				 foreach($files as $file){
					    $file_type =  mime_content_type($_FILES[$file]['tmp_name']);
						/*checking for the file mime type */
					  	if($file_type == 'text/plain'){
							$$file = file_get_contents($_FILES[$file]['tmp_name']);
							/*creating array of string by breaking to content according to the newline*/
							$$file= preg_split('/[\n]/', $$file );	
							$return_array[$file] = $$file;
						}
						else{
							exit("file format not supported");
							
						}
				 }	
				 return $return_array;
			}
	}
?>
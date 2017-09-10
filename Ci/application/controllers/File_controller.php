<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class File_controller extends CI_Controller {

		public function __construct() { 
	         parent::__construct(); 
	         $this->load->helper(array('form', 'url')); 
	      }

		public function index(){
			$this->load->view('file_upload',array('error' => ' ' ));
		}

		public function do_upload(){
			 $config['upload_path']   = './uploads/'; 
	         $config['allowed_types'] = 'txt|doc|rtf'; 
	         $config['max_size']      = 100; 
	         $config['max_width']     = 1024; 
	         $config['max_height']    = 768;  
	         $this->load->library('upload', $config);
	         //$reciept_file = $_FILES['reciept']['name'];
	         $supplier_file = $_FILES['suppliers']['name'];
	         $files = array("reciept","suppliers");
			 $flag = 0;
			 foreach($files as $file){
				 if ( ! $this->upload->do_upload($file)){
					$error = array('error' => $this->upload->display_errors()); 
					$flag = 1;
					$this->load->view('file_upload', $error); 
				 }
				 else { 
					$data = array('upload_data' => $this->upload->data()); 
					$$file = file_get_contents('./uploads/'.$_FILES[$file]["name"]);
					$$file= preg_split('/[\n]/', $$file );
				 }
			 }
	         $wordArray = $this->getWordArray($reciept);	
			 $stringOfReciept = $this->convertRecieptToString($wordArray);
	         $supplier_name_array = $this->getSupplierNameArray($suppliers);
			 
			 
			if($flag!=1){
				$output = $this->checkCompanyName($stringOfReciept,$supplier_name_array);
				echo $output;

			}

		}

		private function getWordArray($reciept_content){
			foreach($reciept_content as $str){
				
				$str_rep = str_replace("'",'"',$str);
				$sub_arrays[] = json_decode($str_rep);
			}
			foreach ($sub_arrays as $sub_array) {
					$wordArray[$sub_array->word_id]= $sub_array->word; 
			}
			ksort($wordArray);
			return $wordArray;
		}

		private function convertRecieptToString($wordArray){
			$recieptStr = implode(" ",$wordArray);
			return $recieptStr;
		}

		public function getSupplierNameArray($supplier_content){
			foreach($supplier_content as $supplier){
				
				$array = explode(",", $supplier );
				$sub_array[] = $array[1];
			}
			return $sub_array;
		}

		private function checkCompanyName($stringOfReciept,$supplier_name_array){
			foreach($supplier_name_array as $supplier_name){
				$stringOfReciept = preg_replace('/\s+/', '', $stringOfReciept);
				if (strpos($stringOfReciept, preg_replace('/\s+/', '', $supplier_name)) !== false) {
					$result = $supplier_name;
					break ;
				}
				else{
					$result = 0;
				}
			}
			return $result;
		}
	}
?>
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class File extends CI_Controller {

		public function __construct() { 
	         parent::__construct(); 
	         $this->load->helper(array('form', 'url')); 
			 $this->load->model('file_model');
	    }                 

		public function index(){
			$this->load->view('file_upload',array('error' => ' ' ));
		}

		public function submit(){
			/*calling the method getContent located in model*/
			 $modelReturn[] = $this->file_model->getContent('reciept','suppliers');
			 /* getting the required content from the returned array*/
			 $reciept = $modelReturn[0]['reciept'];
			 $suppliers = $modelReturn[0]['suppliers'];
			 /* calling function for reciepts*/
	         $wordArray = $this->getWordArray($reciept);	
			 $stringOfReciept = $this->convertRecieptToString($wordArray);
			 /*calling function to compre the company name with reciept and returns output as a result*/
	         $supplier_name_array = $this->getSupplierNameArray($suppliers);
			 $output = $this->checkCompanyName($stringOfReciept,$supplier_name_array);
			 if($output==0){ 
			 	echo "No match found";
			 }
			 else{ 
			 	"Reciept belongs to : ".$output;
			 }
		}

		private function getWordArray($reciept_content){
			foreach($reciept_content as $str){
				$str_rep = str_replace("'",'"',$str);
				$sub_arrays[] = json_decode($str_rep);
			}
			foreach ($sub_arrays as $sub_array) {
				if((isset($sub_array->word_id))&&(isset($sub_array->word))){
					$wordArray[$sub_array->word_id] = $sub_array->word; 
				}
				else{
					exit("please upload the correctly formatted file");
				}
			}
			ksort($wordArray);
			return $wordArray;
		}


	/*Function to join all the sorted words to create a string*/
		private function convertRecieptToString($wordArray){
			$recieptStr = implode(" ",$wordArray);
			return $recieptStr;
		}


	/*function to extract company name from the given data and create an array*/
		public function getSupplierNameArray($supplier_content){
			foreach($supplier_content as $supplier){
				$array = explode(",", $supplier );
				/*checking if the uploaded file have the required file format*/
				if(isset($array[1]))
					$sub_array[] = $array[1];
				else 
					exit("please upload the correctly formatted file");
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
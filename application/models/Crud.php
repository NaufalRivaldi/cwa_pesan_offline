<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Model {

	public function create_sess($post){
		foreach($post as $key=>$value){
			$_SESSION[$key] = $value;
		}
		return true;
	}

	public function destroy_sess($post){
		foreach($post as $key=>$value){
			if(isset($_SESSION[$key]))
				unset($_SESSION[$key]);
		}
		return true;
	}



	public function echo_sess($sess_name,$default=null){
		if(isset($_SESSION[$sess_name])){
			echo $_SESSION[$sess_name];
			unset($_SESSION[$sess_name]);
		}
		else{
			echo $default;
		}
	}


	public function validate($input,$ignore=null){
		if(empty($input) or $input == $ignore){
			return false;
		}
		return true;
	}

	public function validate_array($arr){
		$out = true;
		foreach($arr as $r){
			$out = $out && $this->validate($r);
		}
		return $out;
	}

	public function insert($tbname,$values){
		$out = "INSERT INTO $tbname VALUES (";
		$arr = array();

		foreach($values as $val){
			if(is_null($val)){
				array_push($arr, "NULL");
			}
			elseif(empty($val)){
				array_push($arr, "''");
			}
			else{
				array_push($arr, $this->db->escape($val));
			}
		}
		$out .= implode(", ",$arr);

		$out .= ");";
		
		$query = $this->db->query($out);

		return $query;
	}

	public function file_error($num){
		$err = array( 
	        0=>"There is no error, the file uploaded with success", 
	        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
	        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
	        3=>"The uploaded file was only partially uploaded", 
	        4=>"No file was uploaded. Please insert at least one.", 
	        6=>"Missing a temporary folder" 
		);
		return $err[$num];
	}

	public function upload($filename,$tmp){
		$hash = sha1(rand(1,10000));
		$nmfile = $hash.".".$this->def->get_extension($filename);
		$loc = "upload/".$nmfile;

		move_uploaded_file($tmp, $loc);

		return $nmfile;
	}

	

	public function rollback_upload($fname){
		if(file_exists("upload/".$fname)){
			unlink("upload/".$fname);
		}
		return true;
	}

	public function search_single($tb,$param,$value,$return){
		$query = $this->db->query("SELECT $return FROM $tb WHERE $param = ".$this->db->escape($value));
		$row = $query->row_array();
		return $row[$return];
	}

}
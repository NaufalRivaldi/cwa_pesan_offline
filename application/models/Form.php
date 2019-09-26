<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends CI_Model {

	public function addInput($type,$name,$label,$value=null,$attr=null,$isArray=false){
		/*Type : 
		- Select, Textarea, Custom
		*/
		$out = "";
		$tmpVal = "";
		if(isset($_SESSION[$name]) && $type <> "select"){
			$value = $_SESSION[$name];
			$tmpVal = $_SESSION[$name];
			unset($_SESSION[$name]);
		}
		else if(isset($_SESSION[$name]) && $type == "select"){
			$tmpVal = $_SESSION[$name];
			unset($_SESSION[$name]);
		}

		$out .= "<div class='form-ipt'>";
		$out .= "	<label class='form-lbl'>$label</label>";
		$out .= "	<div class='input'>";

		if($isArray==true){
			$id = $name;
			$name = $name."[]";
		}
		else{
			$id = $name;
		}

		switch($type){
			case "select":
				$out .= "	<select name='".$name."' id='".$id."' $attr>";
				$out .= "		<option></option>";
				foreach($value as $key=>$hsl){
					if($key == $tmpVal)
						$sel = "selected";
					else
						$sel = "";
					$out .= "		<option value='".$key."' $sel>$hsl</option>";
				}
				$out .= "	</select>";
			break;

			case "textarea" :
				$out .= "	<textarea name='".$name."' id='".$id."' $attr>$value</textarea>";
			break;

			default : 
				$out .= "	<input type='".$type."' name='".$name."' id='".$id."' value='".$value."' $attr>";
		}
		$out .= "
			</div>
		</div>";

		echo $out;
		return $out;
	}

	public function get_form_array($sql, $key,$value){
		//cocok dipakai di tag <select>
		$arr = array();
		$query = $this->db->query($sql);
		foreach($query->result_array() as $data){
			$arr[$data[$key]] = $data[$value];
		}
		return $arr;
	}

	public function addButton($type="button",$label="Process"){
		$out = "<div class='form-ipt'>";
		$out .= "<button type='$type' name='btn-$type' class='button'>$label</button>";
		$out .= "</div>";
		echo $out;
		return $out;
	}

	public function addControl($type,$name,$label,$value=null,$attr=null,$isArray=false){
		$out = "";
		if($isArray == true){
			$id = $name;
			$name = $name."[]";
		}else{
			$id = $name;
		}

		$out .= "<div class='form-ipt'>";
		$out .= "<label for='$id'><input type='$type' name='$name' id='$id' value='$value' $attr> $label</label>";
		$out .= "</div>";
		echo $out;
		return $out;
	}

}
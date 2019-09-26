<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trash extends CI_Controller {

	public function index(){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$data['title'] = "Tempat Sampah";
		$data['menu'] = 3;
		$this->load->model("mail");

		$current = $this->def->get_current();
		$data['query'] = $this->mail->get_trash($current);

		$this->load->view("header",$data);
		$this->load->view("trash",$data);
		$this->load->view("footer");
	}

	public function view($id){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$this->load->model("mail");
		$this->load->model("crud");
		$data['menu'] = 3;
		$data['trash'] = true;
		$data['id'] = $id;
		$data['row'] = $this->mail->get_message($id);
		$data['attachment'] = $this->mail->get_attachment($id);
		
		$this->load->view("header",$data);
		$this->load->view("single",$data);
		$this->load->view("footer");
		$this->mail->set_read($id);
	}

	public function restore($id=null){
		$this->load->model("mail");
		$var = $this->mail->restore_msg($id);
		if($var)
			$this->def->pesan("success","Berhasil mengembalikan pesan ke tempat semula","trash");
		else
			$this->def->pesan("error","Kesalahan ketika mengembalikan file","trash");
	}

	public function batch(){
		$this->load->model("mail");
		$this->load->model("crud");
		if(!$this->def->cek_login()){
			redirect("");
		}

		if(isset($_POST['restore'])){
			$action = "restore";
			$psn = "mengembalikan ke semula";
		}
		elseif(isset($_POST['delete'])){
			$action = "delete";
			$psn = "menghapus permanen";
		}

		$checkid = $_POST['checkid'];

		if(!is_array($checkid)){
			$id = intval(str_replace("ch_", "", $chk));
			if($action == "restore")
				$del = $this->mail->restore_msg($id);
			elseif($action == "delete")
				$del = $this->mail->delete_permanent($id);
		}

		if(count($checkid) > 0){
			foreach($checkid as $chk){
				$id = intval(str_replace("ch_", "", $chk));
				//del_process
				if($action == "restore")
					$del = $this->mail->restore_msg($id);
				elseif($action == "delete")
					$del = $this->mail->delete_permanent($id);
			}
		}

		$this->def->pesan("success","Berhasil $psn pesan-pesan terpilih","trash");
	}
}
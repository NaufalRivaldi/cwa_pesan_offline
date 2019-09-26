<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		if($this->def->cek_login()){
			$data['title'] = "Pesan Masuk";
			$data['menu'] = 1;
			$this->load->model("mail");

			$current = $this->def->get_current("username");
			$data['query'] = $this->mail->get_mail("",$current);
			$data['news'] = array(
				"id" => 3,
				"msg" => "NEWS : Fitur terbaru scoreboard penjualan, sekarang sudah dapat diakses di menu di samping. Segera hubungi Admin apabila menemukan masalah dalam sistem."
			);


			$this->load->view("header",$data);
			$this->load->view("dashboard");
			$this->load->view("footer");
		}
		else{
		  //  echo "Web sedang dalam proses maintenance, Terima Kasih";
			$data['title'] = "Login CWA Mail";
			$this->load->view('login');
		}
	}

	public function view($id){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$this->load->model("mail");
		$this->load->model("crud");
		$data['id'] = $id;
		$data['inbox'] = true;
		$data['row'] = $this->mail->get_message($id);
		$data['attachment'] = $this->mail->get_attachment($id);
		$data['menu'] = 1;

		$this->load->view("header",$data);
		$this->load->view("single",$data);
		$this->load->view("footer");
		$this->mail->set_read($id);
	}

	public function forward($id){
		$this->load->model("crud");
		$this->load->model("mail");

		if(!$this->def->cek_login()){
			redirect("");
		}

		$data['menu'] = 1;
		if(isset($_GET['to']))
			$data['id_admin'] = $_GET['to'];
		else
			$data['id_admin'] = 0;
		$data['forward'] = true;
		$data['id_pesan'] = $id;
		$data['fwd_subject'] = $this->mail->get_forwarded_msg($id,"subject");
		$data['fwd_message'] = $this->mail->get_forwarded_msg($id);

		$this->load->view("header",$data);
		$this->load->view("compose",$data);
		$this->load->view("footer");
	}

	public function compose(){
		$this->load->model("crud");
		if(!$this->def->cek_login()){
			redirect("");
		}

		$this->load->model("mail");
		$data['menu'] = 1;
		if(isset($_GET['to']))
			$data['id_admin'] = $_GET['to'];
		else
			$data['id_admin'] = 0;

		$this->load->view("header",$data);
		$this->load->view("compose",$data);
		$this->load->view("footer");
	}

	public function send(){
		$this->load->model("crud");
		$this->load->model("mail");

		if(!$this->def->cek_login()){
			redirect("");
		}

		if(!isset($_POST[$_SESSION['form-token']])){
			$this->def->pesan("error","Kode token keamanan tidak valid","home/compose");
		}


		//deklarasi variabel
		$tujuan = $_POST['tujuan'];
		$subject = $_POST['subject'];
		$msg = $_POST['msg'];
		$file = $_FILES['file'];

		$list = array(
			"tujuan" => $tujuan,
			"subject" => $subject,
			"msg" => $msg
		);

		if(count($tujuan) == 0){
			$this->crud->create_sess($list);
			$this->def->pesan("error","Mohon mengisi kolom yang sudah disediakan dengan lengkap","home/compose");
		}
		else{
			//check upload file
			$num = count($file['name']);

			if($num < 1){
				//no file uploaded
				$id = $this->mail->save_mail($tujuan, $subject, $msg);

				if(isset($_POST['forward_from'])){
					//proses attachment lama jika ada
					$forward_from = $_POST['forward_from'];

					$this->mail->forward_attachment($forward_from, $id);
				}


				$this->def->pesan("success","Berhasil mengirim pesan","home");
			}
			else{
				//some file uploaded
				if($subject == ""){
					$subject = "No Subject";
				}

				if(isset($_POST['reply_to'])){
					$getm = $this->mail->get_message($_POST['reply_to']);
					$rmsg = $getm['message'];


					$rc = "";
					$receiver = $this->mail->get_receiver($_POST['reply_to']);
					foreach($receiver as $rec){
						$rc .= $rec['username'].", ";
					}
					$rc = substr($rc, 0, -2);

					$isi = "<br><blockquote>
					<b>---Reply to $getm[username]---</b>
					<br>
					Tanggal : ".$this->def->indo_date($getm['tgl'])."
					<br>
					Untuk : $rc
					<br>
					<br>
					$getm[message]
					</blockquote>";

					$msg = $msg . $isi;
				}


				$id = $this->mail->save_mail($tujuan, $subject, $msg);

				if(isset($_POST['forward_from'])){
					//proses attachment lama jika ada
					$forward_from = $_POST['forward_from'];

					$this->mail->forward_attachment($forward_from, $id);
				}
				

				for($i=0; $i<$num; $i++){
					if($file['error'][$i] == 0){
						$filename = $file['name'][$i];
						$tmp = $file['tmp_name'][$i];

						$filehash = $this->crud->upload($filename, $tmp); //proses upload

						$ins = $this->crud->insert("tb_attachment", array(null, $id, $filename, $filehash));
					}
					elseif($file['error'][$i] == 4)
						continue;
					else{
						$this->crud->create_sess($list);
						$this->def->pesan("error",$this->crud->file_error($file['error'][$i]), "home/compose");
					}
				}

				//harusnya sih ga ada masalah sampai disini..
				$this->crud->destroy_sess($list);
				$this->def->pesan("success","Berhasil mengirim pesan","home");
			}

		}
	}

	public function delete($id){
		if(!$this->def->cek_login()){
			redirect("");
		}

		$this->load->model("mail");
		$process = $this->mail->delete_msg($id);

		if(empty($process)){ //success
			$this->def->pesan("success","Berhasil menghapus pesan","home");
		}
		else{
			$this->def->pesan("error","Error SQL : $process","home/view/$id");
		}
	}

	public function batch_delete(){
		$this->load->model("mail");
		$this->load->model("crud");
		if(!$this->def->cek_login()){
			redirect("");
		}

		$checkid = $_POST['checkid'];

		if(!is_array($checkid)){
			$id = intval(str_replace("ch_", "", $chk));
			$del = $this->mail->delete_msg($id);
		}

		if(count($checkid) > 0){
			foreach($checkid as $chk){
				$id = intval(str_replace("ch_", "", $chk));
				//del_process
				$del = $this->mail->delete_msg($id);
				if($del)
					$this->def->pesan("error","Error SQL : $del","home");
			}
		}

		$this->def->pesan("success","Berhasil menghapus pesan-pesan terpilih","home");
	}


	public function login(){
		$this->load->model("login");
		if($this->def->cek_login()){
			redirect(base_url("home"));
		}
		else{
			$this->login->process();
		}
	}

	public function changepass(){
		if($this->def->first_login()){
			$_SESSION['sudah_muncul'] = 1;
			$data['title'] = "Ganti Password";
			$data['menu'] = 4;
			$data['firsttime'] = true;

			$this->load->view("header",$data);
			$this->load->view("setting");
			$this->load->view("footer");
		}
		else{
			redirect("home");
		}
	}
}

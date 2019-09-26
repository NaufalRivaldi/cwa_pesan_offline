<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		$this->load->model("mdbackdoor");
		if(!$this->mdbackdoor->cek_login()){
			$this->load->view("backend/login");
		}
		else{
			$data['menu'] = 1;
			$data['title'] = "Manage User";
			$this->load->view("backend/header",$data);
			$this->load->view("backend/home");
			$this->load->view("backend/footer");
		}
	}

	public function tes(){
		if(false){

		}
		else{
			echo "
			<h2>Admin Control</h2>
			<div style='padding:1em; border:1px solid #999;'>

				<a href='".base_url()."backend/add'>[Tambah Admin]</a>

				<table cellpadding=5>
					<tr>
						<th>Username</th>
						<th>Name</th>
						<th>Stat</th>
						<th>Aksi</th>
					</tr>
			";

			$sql = "SELECT * FROM tb_admin";
			$run = $this->db->query($sql);
			foreach($run->result_array() as $row){
				if($row['stat'] == 1){
					$action = "delete";
					$stat = "Aktif";
				}
				else{
					$action = "activate";
					$stat = "<span style='color:#d00'>Nonaktif</span>";
				}
				echo "
					<tr>
						<td>$row[username]</td>
						<td>$row[name]</td>
						<td>$stat</td>
						<td><a href='".base_url()."backend/$action?username=$row[username]'>[$action]</a></td>
					</tr>
				";
			}


			echo "
				</table>
			</div>



			<h2>Weekly Log Preview</h2>

			<form action='' method='get'>
				Select Header : 
				<select name='header'>
					<option></option>
			";

				$hd = "SELECT DISTINCT header FROM tb_log";
				$qr = $this->db->query($hd);
				foreach($qr->result_array() as $hehe){
					echo "<option value='$hehe[header]'>$hehe[header]</option>";
				}

			echo "
				</select>
				<br>
				Actor : 
				<select name='actor'>
					<option></option>
			";

				$act = "SELECT * FROM tb_admin";
				$acr = $this->db->query($act);
				foreach($acr->result_array() as $hoam){
					echo "<option value='$hoam[username]'>$hoam[username]</option>";
				}

			echo "
				</select>
				<br>
				Message Include : 
				<input type='text' name='inc' maxlength=50>
				<br>
				<input type='submit'>
			</form>

			";

			$addQuery = $adq = "";
			$arq = array();
			if(isset($_GET['header'])){
				if($_GET['header'] <> ""){
					$addQuery = "header = ".$this->db->escape($_GET['header']);
					array_push($arq, $addQuery);
				}
			}
			if(isset($_GET['actor'])){
				if($_GET['actor'] <> ""){
					$addQuery2 = "message LIKE ".$this->db->escape("%".$_GET['actor']."%");
					array_push($arq, $addQuery2);
				}
			}
			if(isset($_GET['inc'])){
				if($_GET['inc'] <> ""){
					$addQuery3 = "message LIKE ".$this->db->escape("%".$_GET['inc']."%");
					array_push($arq, $addQuery3);
				}
			}

			if(count($arq) > 0){
				$newQuery = "WHERE " . implode(" AND ", $arq);
			}
			else{
				$newQuery = "";
			}


			echo "
			<table class='data' border=1 cellpadding=3>
			<tr>
				<th>Tanggal</th>
				<th>Header</th>
				<th>Message</th>
			</tr>
			";

			$finalQuery = "SELECT * FROM tb_log $newQuery ORDER BY id DESC LIMIT 100";
			$run = $this->db->query($finalQuery);
			$ctrl_date = "";
			foreach($run->result_array() as $rur){
				$getdate = $this->def->indo_date($rur['tgl'],"half");
				if($ctrl_date == $getdate){
					$tanggal = $this->def->indo_date($rur['tgl'],"time");
				}
				else{
					$ctrl_date = $getdate;
					$tanggal = $this->def->indo_date($rur['tgl'],"full");
				}

				echo "
				<tr>
					<td align='right' nowrap>$tanggal</td>
					<td>$rur[header]</td>
					<td>$rur[message]</td>
				</tr>
				";
			}
			echo "</table>";


		}
	}


	public function add(){
		$this->load->model("mdbackdoor");
		if(!$this->mdbackdoor->cek_login()){
			redirect("backend");
		}

		echo "
		<h2>Tambah Admin</h2>
		<form action='addproses' method='post'>
			Username : <input type='text' name='username'>
			<br>
			Name : <input type='text' name='name'>
			<br>
			<input type='submit'>
		</form>
		";

	}

	public function edit($id){
		$data['menu'] = 1;
		$data['title'] = "Edit User";
		$data['user'] = $this->db->query("SELECT * FROM tb_admin WHERE username like '%$id%' ")->row();
		$this->load->view("backend/header", $data);
		$this->load->view("backend/edit_user", $data);
		$this->load->view("backend/footer");
	}



	public function addproses(){
		$this->load->model("mdbackdoor");
		if(!$this->mdbackdoor->cek_login()){
			redirect("backend");
		}

		$username = $_POST['username'];
		$name = $_POST['name'];

		$cek = "SELECT * FROM tb_admin WHERE username = '$username' OR name = '$name'";
		$run = $this->db->query($cek);
		if($run->num_rows() > 0){
			echo "Error : Username atau nama tersebut sudah ada. <a href='backend/add'>[Kembali]</a>";
		}
		else{
			$add = $this->mdbackdoor->add_user($username, $name);
			echo $add;
		}

	}

	public function delete(){
		$this->load->model("mdbackdoor");
		if(!$this->mdbackdoor->cek_login()){
			redirect("backend");
		}


		$username = $_GET['username'];
		$sql = "UPDATE tb_admin SET stat = 9 WHERE username = ".$this->db->escape($username);
		$run = $this->db->simple_query($sql);
		if($run){
			echo "Berhasil menonaktifkan Admin. <a href='".base_url("backend")."'>[Kembali]</a>";
		}
	}

	public function activate(){
		$this->load->model("mdbackdoor");
		if(!$this->mdbackdoor->cek_login()){
			redirect("backend");
		}

		$username = $_GET['username'];
		$sql = "UPDATE tb_admin SET stat = 1 WHERE username = ".$this->db->escape($username);
		$run = $this->db->simple_query($sql);
		if($run){
			echo "Berhasil mengaktifkan Admin. <a href='".base_url("backend")."'>[Kembali]</a>";
		}
	}



	public function login(){
		$this->load->model("mdbackdoor");
		$username = $_POST['username'];
		$password = $_POST['password'];

		$cond1 = $username === $this->mdbackdoor->harduser;
		$cond2 = $password === $this->mdbackdoor->hardpass;

		if($cond1 && $cond2){
			//login sukses
			$token = sha1($password);
			$_SESSION['backdoor_token'] = $token;
			redirect("backend");
		}
		else{
			$this->def->pesan("error","Username / password yang anda masukkan salah.","backend");
		}
	}

}
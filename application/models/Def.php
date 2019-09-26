<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Def extends CI_Model {

	public function err_dump($pesan,$type){
		//type : Notice, Warning, Critical
		$tipe = "Error Level : ".ucfirst($type);
		echo "
		<div style=\"padding:1em; margin:.2em; border:1px solid #d00; color:#777;\">
			<div style=\"font-weight:bold; color:#d00;\">$tipe</div>
			$pesan
		</div>
		";
		if($type=="critical"){
			exit;
		}
	}

	public function quote($txt){
		return $this->db->escape($txt);
	}

	public function get_setting($param,$get="value"){
		$cek = $this->db->query("SELECT $get FROM tb_setting WHERE param = ".$this->quote($param));
		return $cek->row_array()[$get];
	}
	public function add_setting($param,$value=null,$desc=null){
		$st = $this->get_setting($param,"id");
		if($st){
			$q = "UPDATE tb_setting SET value = ".$this->quote($value);
			if($desc<>null){
				$q .= ", description = ".$this->quote($desc);
			}
			$q .= "WHERE id_setting = ".$this->quote($st);
		}
		else{
			$q = "INSERT INTO tb_setting VALUES
			(NULL, ".$this->quote($param).", ".$this->quote($param).", ".$this->quote($value).", ".$this->quote($desc).")";
		}
		$run = $this->db->query($q);
		return true;
	}



	public function cek_login(){
		if(isset($_SESSION['token'])){
			$cek = $this->db->query("SELECT username FROM tb_admin WHERE token = ".$this->quote($_SESSION['token']));

			return $cek->row_array();
		}
		return false;
	}

	public function create_token(){
		$random = rand(1,100000);
		$a = md5($random);
		$r = rand(1,10);
		$token = sha1(substr($random,$r,10));
		return $token;
	}

	public function echo_token(){
		$token = $this->create_token();
		$_SESSION['form-token'] = $token;
		echo "
		<input type='hidden' class='action-token' name='$token' value='1'>
		";
	}
	public function assign_token($username,$token){
		$ass = $this->db->query("UPDATE tb_admin SET token = ".$this->quote($token)." WHERE username = ".$this->quote($username));
		return true;
	}

	public function pesan($tipe,$isipesan,$header=null){
		//in case lupa dan salah manggil
		if($tipe == "error")
			$tipe = "danger";
		$_SESSION[$tipe] = $isipesan;
		if(!is_null($header)){
			$loc = base_url()."".$header;
			echo "
			<script>
				window.location = '$loc';
			</script>
			";
			exit;
		}
	}

	public function msghandling(){
		$type = ["danger","success","warning"];
		foreach($type as $tp){
			if(isset($_SESSION[$tp])){
				echo "
				<div class='alert alert-$tp'>
					$_SESSION[$tp]
				</div>
				";

				if($tp == "danger"){
					$username = $this->get_current();
					$url = $this->current_url();
					$this->add_log("ALERT_ERROR","$username in page '<a href='$url'>$url</a>' ".$_SESSION[$tp]);
				}

				unset($_SESSION[$tp]);
			}
		}
	}

	public function current_url(){
		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $url;
	}

	public function make_sess($sess_name,$sess_value){
		$_SESSION[$sess_name] = $sess_value;
		return $sess_value;
	}

	public function echo_sess($sess_name){
		if(isset($_SESSION[$sess_name])){
			echo $_SESSION[$sess_name];
			unset($_SESSION[$sess_name]);
		}
	}

	public function make_multisess($arr){
		foreach($arr as $key=>$value){
			make_sess($key,$value);
		}
	}

	public function compare_output($a,$b,$output=null){
		if($a <> $b)
			return false;

		if(!empty($output))
			return $output;
		else
			return true;

	}

	public function rupiah($num){
		$angka = number_format($num,0,",",".");
		return "Rp ".$angka;
	}

	public function dump($txt){
		echo "<textarea>";
		var_dump($txt);
		echo "</textarea>";
		exit;
	}

	public function check_extension($txt,$allowed=["jpg","gif","png","jpeg"]){
		$data = explode(".",$txt);
		$n = count($data);
		$ext = $data[$n-1];

		if(!in_array($ext, $allowed)){
			return false;
		}
		return true;
	}

	public function get_extension($file){
		$exp = explode(".",$file);
		$num = count($exp);
		return $exp[$num-1];
	}


	public function create_navigation($page,$total,$url){
		$limit = $this->get_setting("paging");
		$prev = $page - 1;
		$next = $page + 1;

		$max = ceil($total / $limit['value']);
		if($prev <= 0)
			$prev = 1;
		if($next > $max)
			$next = $max;

		$out = "";
		$out .= "<ul class='pagination'>";
		$out .= "<li class='prev'><a href='$url?page=$prev'>&laquo;</a></li>";


		for($i=1;$i<=$max;$i++){
			if($i == $page)
				$cl = "active";
			else
				$cl = "";
			$out .= "<li class='$cl'><a href='$url?page=$i'>$i</a></li>";
		}

		$out .= "<li class='next'><a href='$url?page=$next'>&raquo;</a></li>";
		$out .= "</ul>";

		echo $out;
	}

	public function text_trim($txt,$num=15){
		$expl = explode(" ",$txt);
		if(count($expl) <= $num)
			return $txt;

		$out = "";
		for($i=0;$i<$num;$i++){
			$out .= $expl[$i]." ";
		}
		$out .= "...";
		return $out;
	}

	public function indo_date($date=null, $type="full"){
		if(is_null($date)){
			$date = date("Y-m-d H:i:s");
		}
		$out = "";
		if($type <> "time"){
			$m = date("n",strtotime($date));
			$arrMo = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
			$mo = $arrMo[$m];

			$dt = date("d",strtotime($date));
			$yr = date("Y",strtotime($date));

			$out .= "$dt $mo $yr ";
		}
		
		if($type == "full" or $type == "time"){
			$time = date("H:i:s",strtotime($date));
			$out .= $time;
		}

		return $out;
	}


	public function get_current($param="username"){
		if(isset($_SESSION['token'])){
			$token = $_SESSION['token'];
			$query = "SELECT $param FROM tb_admin WHERE token = ".$this->db->escape($token);
			$run = $this->db->query($query);
			$row = $run->row_array();
			return $row[$param];
		}
		else
			return false;
	}

	public function get_admin($val, $out="name",$in="username"){
		$query = "SELECT $out FROM tb_admin WHERE $in = ".$this->db->escape($val);
		$run = $this->db->query($query);
		$row = $run->row_array();
		return $row[$out];
	}


	public function first_login(){
		if($this->cek_login()){
			$default_pass = sha1("123456");
			$current_pass = $this->get_current("password");

			if($default_pass == $current_pass){
				//munculkan
				return true;
			}
		}
		return false;
	}

	public function show_changepass(){
		$current_user = $this->get_current();

		echo "
		<div class='alert alert-danger'>
		Kelihatannya Anda baru login pertama kali di sistem ini. Segera ganti password untuk menjaga keamanan email Anda.<br><a href='home/changepass'>Klik disini untuk mengganti password</a>
		</div>
		";
	}

	public function add_log($header,$message){
		if(strpos($message,"http://cwa.pe.hu/home") !== false){
			return false;
		}

		$date = date("Y-m-d H:i:s");
		$ins = "INSERT INTO tb_log VALUES (NULL, ".$this->db->escape($header).", ".$this->db->escape($message).", '$date', 1)";

		//sebelumnya dicek dulu
		$cek = $this->db->query("SELECT * FROM tb_log WHERE header = ".$this->db->escape($header)." ORDER BY id DESC LIMIT 1");
		$row = $cek->row_array();
		if($row['message'] <> $message){
			$run = $this->db->simple_query($ins);
			return $run;
		}
		return true;
	}

	public function page_record(){
		if($this->cek_login()){
			$username = $this->get_current();
			$url = $this->current_url();
			$this->def->add_log("RECORD_STEP","$username open page \"<a href='$url'>$url</a>\"");
		}
	}


	public function echo_news($arr){
		$user = $this->cek_login();
		$username = $user['username'];
		$id = $arr['id'];
		$isi = $arr['msg'];

		$format = "$username just read news : $isi";
		$cek = $this->db->query("SELECT * FROM tb_log WHERE header = 'READ_NEWS_$id' AND message = '$format'");

		if($cek->num_rows()==0){
			//news belum pernah dibaca
			//tampilkan
			echo "
			<div class='alert alert-info'>
				$isi
			</div>
			";
			$tgl = date("Y-m-d H:i:s");
			$this->db->query("INSERT INTO tb_log VALUES (NULL, 'READ_NEWS_$id', '$format', '$tgl', 1)");
		}
	}


	public function get_notif(){
		$this->load->model("mail");
		$current = $this->get_current("username");
		$query = $this->mail->get_mail("",$current);

        $num = 0;
		foreach($query as $row){
			$ctrl = $this->mail->get_control($row['id']);
			if($ctrl == 0){
				//ada pesan yg belum dibaca
				$num++;
			}
		}

		if($num > 0){
			//create notif
			if($this->save_sess($num)){
				$pesan = "Email baru!";
				$message = "Ada $num pesan baru di inbox Citra Warna. Buruan cek sekarang..";
				return array($pesan,$message);
			}
		}
		return false;
	}

	public function save_sess($num){
		echo "alert(\"tai\")";
		echo "alert(\"". $num ."\");";
		if(isset($_SESSION['notif'])){
			$notif_item = $_SESSION['notif'];

			if($num == $notif_item)
				return false;
			else
				return true;
		}
		return true;
	}


	public function cek_upload_cabang($user, $tglFile){
		$q = $this->db->where('username', $user)
						->like('tgl', $tglFile)
						->get('attach_penjualan_member')->row_array();
		return $q;
	}



	//function ultah 
	public function ultah(){
		//ini coding nampilin ultah 
		$karyawans= $this->db->like('ttl', date('m-d'))->get('ultah')->result_array();;
		$teks = "Selamat Ulang Tahun <b>";
		if($karyawans){
			foreach ($karyawans as $karyawan) {
			
			$teks = $teks ." ". $karyawan['nama_lengkap']." (".$karyawan['devisi'].")". ", ";
				
			}
			$teksUltah = rtrim($teks, ", ");
			$ultah = $teksUltah. '</b>. Wish you all the best!  <i class="fa fa-gift"></i> <i class="far fa-smile-beam"></i> <i class="fa fa-heart"></i> ';
		} else {
			$teks = '';
			$ultah = '';
		}
		
		
		
		echo $ultah;
	}


	public function is_finance($account){
		$user = explode('@', $account);

		if($user[0] == 'finance'){
			return true;
		} else if($user[0] == 'it'){
			return true;
		} else {
			return false;
		}
	}

	public function is_spt($account){
		$user = explode('@', $account);

		if($user[0] == 'awaludin' || $user[0] == 'it'){
			return true;
		} else {
			return false;
		}
	}

	public function checkUser($akun){
		$user = explode("@", $akun);

		if(strpos($user[0], 'cw') !== false || $user[0] == "it" || $user[0] == "gudang"){
			return true;
		} else {
			return false;
		}
	}
	
	public function checkUserMaster($akun){
		$user = explode("@", $akun);

		if(strpos($user[0], 'cw') !== false || $user[0] == "it" || $user[0] == "gudang" || $user[0] == "scm"){
			return true;
		} else {
			return false;
		}
	}

	public function is_PU($akun){
		$user = explode("@", $akun);

		if(strpos($user[0], 'cw') !== false || $user[0] == "it" || $user[0] == "scm"){
			return true;
		} else {
			return false;
		}
	}

	public function is_master($akun){
		$user = explode("@", $akun);

		if(strpos($user[0], 'cw') !== false || $user[0] == "it" || $user[0] == "gudang" || $user[0] == "scm"){
			return true;
		} else {
			return false;
		}
	}

	public function cabang_only($account){
		$user = explode("@", $account);
		if(strpos($user[0], 'cw') !== false){
			return true;
		} else {
			return false;
		}
	}

	public function character($data){
		$char = explode("@", $data);
		return $char[0];
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends CI_Model {

	function get_mail($sender="", $receiver="",$addQuery="", $stat=1){
		if($sender === "" && $receiver === ""){
			$this->def->pesan("error","Unidentified sender or receiver","home");
		}

		if(strlen($sender) > 0){
			$sql = "SELECT * FROM tb_inbox WHERE (username = ".$this->db->escape($sender)." AND stat = ".$this->db->escape($stat).") $addQuery ORDER BY tgl DESC";
		}
		else if(strlen($receiver) > 0){
			$sql = "SELECT * FROM tb_inbox WHERE id IN (SELECT DISTINCT id_inbox FROM tb_receiver WHERE username = ".$this->db->escape($receiver).") AND stat = ".$this->db->escape($stat)." $addQuery ORDER BY tgl DESC";
		}

		$run = $this->db->query($sql);
		return $run->result_array();
	}


	function get_message($id,$trash=false){
		$hehe = "";
		if(!$trash)
			$hehe = " AND stat <> 9";

		$sql = "SELECT * FROM tb_inbox WHERE id = ".$this->db->escape($id).$hehe;
		$run = $this->db->query($sql);
		return $run->row_array();

	}

	function get_receiver($id){
		$sql = "SELECT * FROM tb_receiver WHERE id_inbox = ".$this->db->escape($id);
		$run = $this->db->query($sql);
		return $run->result_array();
	}

	function set_read($id){
		//membuat status email menjadi sudah dibaca
		$current = $this->def->get_current();
		$stat = 1; //status read
		$tgl = date("Y-m-d H:i:s");
		//cek 
		$cek = "SELECT * FROM tb_control WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current);
		$runcek = $this->db->query($cek);
		if($runcek->num_rows() == 0){
			//belum ada, INSERT!!
			$ins = "INSERT INTO tb_control VALUES (NULL, ".$this->db->escape($id).", ".$this->db->escape($current).", '$tgl', $stat)";
			$jln = $this->db->simple_query($ins);
		}
		else{
			$row = $runcek->row_array();
			$stat = $row['stat'];
			if($stat == 0){
				$changeto = 1;
			}
			elseif($stat == 8){
				$changeto = 9;
			}
			else{
				return false;
			}
			$query = "UPDATE tb_control SET stat = $changeto WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current);
			$run = $this->db->simple_query($query);
		}

	}

	function get_control($id){
		$current = $this->def->get_current();
		$cek = "SELECT stat FROM tb_control WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current);
		$run = $this->db->query($cek);
		if($run->num_rows()==0)
			return 0;
		$row = $run->row_array();
		return $row['stat'];
	}

	function list_email($addQuery=""){
		$current = $this->def->get_current();
		$sql = "SELECT username FROM tb_admin WHERE username <> '$current' AND stat <> 9 $addQuery ORDER BY username";
		$run = $this->db->query($sql);
		return $run->result_array();
	}

	function save_mail($tujuan, $subject, $msg){
		$current = $this->def->get_current();
		$tgl = date("Y-m-d H:i:s");
		$ins = $this->crud->insert("tb_inbox",array(null, $current, $subject, $msg, $tgl, 1));
		if($ins){
			//mail half saved
			$id_inbox = $this->id_inbox($current, $subject, $msg, $tgl);

			if(!is_array($tujuan)){
				$ins2 = $this->crud->insert("tb_receiver",array(null, $id_inbox, $tujuan));
			}
			else{
				foreach($tujuan as $t){
					$ins2 = $this->crud->insert("tb_receiver",array(null, $id_inbox, $t));
				}
			}
			return $id_inbox;
		}
		return false;
	}

	function id_inbox($current, $subject, $msg, $tgl){
		 $sql = "SELECT id FROM tb_inbox WHERE username = ".$this->db->escape($current)." AND subject = ".$this->db->escape($subject)." AND message = ".$this->db->escape($msg)." AND tgl = ".$this->db->escape($tgl);
		 $run = $this->db->query($sql);
		 $row = $run->row_array();
		 $id_inbox = $row['id'];
		 return $id_inbox;
	}

	function get_attachment($id){
		$sql = "SELECT * FROM tb_attachment WHERE id_inbox = ".$this->db->escape($id);
		$run = $this->db->query($sql);
		if($run->num_rows() == 0)
			return false;
		else
			return $run->result_array();
	}

	function get_forwarded_msg($id_inbox,$ret="msg"){
		$sql = "SELECT * FROM tb_inbox WHERE id = ".$this->db->escape($id_inbox);
		$run = $this->db->query($sql);
		$row = $run->row_array();

		if($ret == "msg"){
			$rc = "";
			$receiver = $this->get_receiver($id_inbox);
			foreach($receiver as $rec){
				$rc .= $rec['username'].", ";
			}
			$rc = substr($rc, 0, -2);

			return "<br><blockquote>
			<b>---Forwarded Message---</b>
			<br>
			Dari : $row[username]
			<br>
			Tanggal : ".$this->def->indo_date($row['tgl'])."
			<br>
			Subject : $row[subject]
			<br>
			Untuk : $rc
			<br>
			<br>
			".$row['message']."
			</blockquote>";
		}
		elseif($ret=="subject"){
			return "Fwd : ".$row['subject'];
		}
	}

	public function forward_attachment($from,$id){
		$cek_attachment = "SELECT * FROM tb_attachment WHERE id_inbox = ".$this->db->escape($from);
		$run = $this->db->query($cek_attachment);
		foreach($run->result_array() as $ro){
			$nmfile = $ro['nmfile'];
			$location = $ro['location'];

			//save
			$this->crud->insert("tb_attachment",array(null, $id, $nmfile, $location));
		}
	}


	public function delete_msg($id){
		$current = $this->def->get_current();
		$tgl = date("Y-m-d H:i:s");
		if($id > 0){
			$sql = "SELECT * FROM tb_control WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current);
			$run = $this->db->query($sql);
			if($run->num_rows() > 0){
				//data sudah ada,, cukup diupdate.
				$sql = $upd = "UPDATE tb_control SET stat = 9 WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current);
				$run = $this->db->simple_query($upd);
			}
			else{
				//data belum ada,, langsung diinsert
				$sql = $run = $this->crud->insert("tb_control",array(null, $id, $current, $tgl, 8));
			}
			if($run)
				return null;
			else
				return $sql;
		}
		else
			return null;
	}


	public function get_trash($user){
		$sql = "SELECT * FROM tb_inbox WHERE id IN (SELECT DISTINCT id_inbox FROM tb_control WHERE username = ".$this->db->escape($user)." AND stat >= 8 ORDER BY tgl DESC)";
		$run = $this->db->query($sql);
		return $run->result_array();
	}


	public function restore_msg($id){
		$current = $this->def->get_current();
		$cek = "SELECT stat FROM tb_control WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current);
		$run = $this->db->query($cek);
		$row = $run->row_array();

		if($row['stat'] == 8)
			$changeto = 0;
		else if($row['stat'] == 9)
			$changeto = 1;
		else
			return false;

		$process = $this->db->simple_query("UPDATE tb_control SET stat = $changeto WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current));
		if($process)
			return true;
		else
			return false;
	}


	public function delete_permanent($id){
		$current = $this->def->get_current();

		$process = $this->db->simple_query("UPDATE tb_control SET stat = 5 WHERE id_inbox = ".$this->db->escape($id)." AND username = ".$this->db->escape($current));
		if($process)
			return true;
		else
			return false;
	}

	public function read_control($id,$stat){
		$current = $this->def->get_current();
		$sql = "SELECT * FROM tb_control WHERE id_inbox = ".$this->db->escape($id)." AND stat = $stat AND username <> ".$this->db->escape($current);
		$run = $this->db->query($sql);
		$num = $run->num_rows();
		return $num;
	}

}
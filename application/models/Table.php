<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table extends CI_Model {
	var $query,
		$page,
		$where,
		$order,
		$limit,
		$totalQuery;

	public function __construct(){
		parent::__construct();

		$this->query = "";
		$this->page = 1;
		$this->where = array();
		$this->order = "";

		$limit = $this->def->get_setting("paging");
		$this->limit = $limit["value"];
	}


	//SETTER METHOD
	public function query($sql){
		$this->clear_query();
		$this->query = $sql;
	}
	public function page($page){
		$this->page = $page;
	}
	public function where($where,$place="after"){
		$cek = $this->where;
		if(count($cek) > 0){
			if($place == "after")
				array_push($this->where,$where);
			else
				array_merge($where,$this->where);
		}
		else{
			array_push($this->where,$where);
		}
	}
	public function order($order){
		$this->order = $order;
	}
	public function limit($limit){
		//set 0 to disable
		$this->limit = $limit;
	}


	public function run(){
		if(count($this->where) > 0){
			$where = "WHERE " . implode(" AND ",$this->where);
		}
		else
			$where = "";

		if($this->limit > 0){
			$offset = ($this->page-1) * intval($this->limit);
			$limit = "LIMIT $offset,".$this->limit;
		}
		else
			$limit = "";

		$ord = $this->order;

		$this->totalQuery = $this->query . " " . $where . " " . $ord . " " . $limit;

		return $this->db->query($this->totalQuery);
	}

	public function queryDump(){
		return $this->totalQuery;
	}

	public function lookup($fieldname,$key,$output,$default="default.jpg"){
		$this->where($fieldname." = ".$this->db->escape($key));
		$hasil = $this->run();
		$row = $hasil->row_array();
		if(empty($row[$output])){
			return $default;
		}
		else{
			return $row[$output];
		}
	}


	public function dump($output){
		echo "<textarea style='width:100%; height:200px'>";
		var_dump($output);
		echo "</textarea>";
		exit;
	}

	public function clear_query(){
		$this->query = "";
		$this->page = 1;
		$this->where = array();
		$this->order = "";

		$limit = $this->def->get_setting("paging");
		$this->limit = $limit["value"];
	}
}
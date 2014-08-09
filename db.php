<?php

/* Exit if PXL not defined */
if (!defined('PXL'))
	exit('You can\'t access direct script.');

class DB {

	private $db;
	private $db_host = DB_HOST;
	private $db_name = DB_NAME;
	private $db_user = DB_USER;
	private $db_password = DB_PASSWORD;

	/* Connect to database */
	function __construct() {
		try {
			$this->db = new PDO('mysql:host='. $this->db_host .';dbname='. $this->db_name, $this->db_user, $this->db_password);
		} catch(PDOException $e) {
			exit ('<h2>Can\'t connecting to database</h2>');
		}
	}
	
	/* Select single query */
	public function select($table, $field, $field_value, $param = '', $cond = '=') {
		$query = $this->db->prepare("SELECT * FROM $table WHERE $field $cond ?$param");
		$query->execute((array)$field_value);
		$select = $query->fetch();
		
		return $select;
	}
	
	/* Select all query */
	public function select_all($table, $param = '', $fields = '') {
		$query = $this->db->prepare("SELECT * FROM $table$param");
		
		if (is_array($fields))
			$query->execute((array)$fields);
		else
			$query->execute();
		
		$output = array();
		while ($select = $query->fetch())
			$output[] = $select;
			
		return $output;
	}
	
	/* Select query by order */
	public function select_by_order($table, $param = '', $field = '') {
		$query = $this->db->prepare("SELECT * FROM $table$param");
		$query->execute();
		$result = $query->fetch();
		
		return $result[$field];
	}
	
	/* Select more queries */
	public function select_more($table, $field, $field_value, $param = '', $cond = '=') {
		$query = $this->db->prepare("SELECT * FROM $table WHERE $field $cond ?$param");
		$query->execute((array)$field_value);
		
		$output = array();
		while ($select = $query->fetch())
			$output[] = $select;
			
		return $output;
	}
	
	/* Get single row count */
	public function row_count($table, $field, $field_value, $param = '', $cond = '=') {
		$query = $this->db->prepare("SELECT * FROM $table WHERE $field $cond ?$param");
		$query->execute((array)$field_value);
		$count = $query->rowCount();
		
		return $count;
	}
	
	/* Get all row count */
	public function row_count_all($table, $param = '') {
		$query = $this->db->prepare("SELECT * FROM $table$param");
		$query->execute();
		$count = $query->rowCount();
		
		return $count;
	}
	
	/* Insert query */
	public function insert($table, $data) {
		if (!is_array($data))
			return false;
		
		$array_fields = array();
		$array_field_keys = array();

		foreach ($data as $key => $value) :
			$array_fields[] = str_replace(':', '', $key);
			$array_field_keys[] = $key;
		endforeach;
		
		$fields = implode($array_fields, ',');
		$field_keys = implode($array_field_keys, ',');
		
		$query = $this->db->prepare("INSERT INTO $table ($fields) VALUES ($field_keys)");
		$query->execute((array)$data);
		
		return true;
	}
	
	/* Update query */
	public function update($table, $data, $field, $field_values, $param = '', $cond = '=') {
		if (!is_array($data))
			return false;
		
		$array_fields = array();
		$array_field_values = array();

		foreach ($data as $key => $value) {
			$array_fields[] = str_replace(':', '', $key) .' = ?';
			$array_field_values[] = $value;
		}
		
		$fields = implode($array_fields, ', ');
		$data = array_merge($array_field_values, (array)$field_values);
		$query = $this->db->prepare("UPDATE $table SET $fields WHERE $field $cond ?$param");
		$query->execute((array)$data);
		
		return true;
	}
	
	/* Delete query */
	public function delete($table, $field, $field_values, $param = '', $cond = '=') {
		$query = $this->db->prepare("DELETE FROM $table WHERE $field $cond ?$param");
		$query->execute((array)$field_values);
		
		return true;
	}
}

?>
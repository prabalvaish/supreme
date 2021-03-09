<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class location_model extends CI_Model
	{
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_locations()
	{
	  return $this->db->get("xin_office_location");
	}
	 
	 public function read_location_information($id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE location_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


// get allowances
public function getAllowances($id) {
	
	$sql = 'SELECT xin_comapny_allowances.allowance_title, xin_comapny_allowances.allowance_amount,xin_designations.designation_name from xin_comapny_allowances inner join xin_designations on xin_comapny_allowances.designation_id=xin_designations.designation_id ORDER BY xin_designations.designation_name';
	$binds = array($id);
	$query = $this->db->query($sql, $binds);
	
	if ($query->num_rows() > 0) {
		return $query->result();
	} else {
		return null;
	}
}

// get Deduction
public function getDeduction($id) {
	
	$sql = 'SELECT * FROM xin_company_detection WHERE company_id = ?';
	$binds = array($id);
	$query = $this->db->query($sql, $binds);
	
	if ($query->num_rows() > 0) {
		return $query->result();
	} else {
		return null;
	}
}

// get Deduction
public function getDesignation($id) {
	
	$sql = 'SELECT * FROM xin_designations WHERE location_id = ?';
	$binds = array($id);
	$query = $this->db->query($sql, $binds);
	
	if ($query->num_rows() > 0) {
		return $query->result();
	} else {
		return null;
	}
}

	public function get_company_office_location($company_id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_office_location', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('location_id', $id);
		$this->db->delete('xin_office_location');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('location_id', $id);
		if( $this->db->update('xin_office_location',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record without logo > in table
	public function update_record_no_logo($data, $id){
		$this->db->where('location_id', $id);
		if( $this->db->update('xin_office_location',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all office locations
	public function all_office_locations() {
	  $query = $this->db->query("SELECT * from xin_office_location");
  	  return $query->result();
	}

// Function to add record in table > allowance
public function add_company_allowances($data){
	$this->db->insert('xin_comapny_allowances', $data);
	if ($this->db->affected_rows() > 0) {
		return true;
	} else {
		return false;
	}
}
// Function to add record in table >Company detection
public function add_company_detection($data){
	$this->db->insert('xin_company_detection', $data);
	if ($this->db->affected_rows() > 0) {
		return true;
	} else {
		return false;
	}
}

public function getEsic($id) {
	
	$sql = 'SELECT is_esic FROM xin_office_location WHERE location_id = ?';
	$binds = array($id);
	$query = $this->db->query($sql, $binds);
	return $query->result();
}

}
?>
<?php
	header('Access-Control-Allow-Origin: *');
	class department_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_departments() {
	  return $this->db->get("xin_departments");
	}
	public function get_sub_departments() {
	  return $this->db->get("xin_sub_departments");
	}
	 
	 public function read_department_information($id) {
	
		$sql = 'SELECT * FROM xin_departments WHERE department_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function get_department_subdepartments($company_id) {
	
		$sql = 'SELECT * FROM xin_sub_departments WHERE department_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function get_company_departments($company_id) {
	
		$sql = 'SELECT * FROM xin_departments WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	public function read_sub_department_info($id) {
	
		$sql = 'SELECT * FROM xin_sub_departments WHERE sub_department_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get single record > company | locations
	 public function ajax_location_information($id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get single record > company | locations
	 public function ajax_company_location_information($id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function ajax_company_last_emp($id) {
	
		$sql = 'SELECT CONCAT(xin_companies.company_prefix,(MAX(xin_employees.user_id)+1)) as Prefix from xin_companies inner join xin_employees on xin_companies.company_id=xin_employees.company_id where xin_companies.company_id=?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get customer allowances
	public function ajax_get_comapny_allowances($id,$id2) {
		echo $id;
		$sql = 'SELECT * FROM `xin_comapny_allowances` WHERE company_id = ? AND designation_id= ?';
		$binds = array($id,$id2);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			echo 'hello';
			return $query->result();
		} else {
			echo 'world';
			return null;
		}
	}
	// get customer deduction
	public function ajax_get_company_deduction($id) {
	
		$sql = 'SELECT * FROM `xin_company_detection` WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get single record > company | locations
	 public function ajax_location_departments_information($id) {
	
		$sql = 'SELECT * FROM xin_departments WHERE location_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// get single record > company | employees
	 public function ajax_company_employee_info($id) {
	
		//$sql = "SELECT * FROM xin_employees WHERE company_id = ? and user_role_id!='1' and is_logged_in='1'";
		$sql = "SELECT * FROM xin_employees WHERE company_id = ? and user_role_id!='1'";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_departments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// Function to add record in table
	public function add_sub($data){
		$this->db->insert('xin_sub_departments', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('department_id', $id);
		$this->db->delete('xin_departments');
		
	}
	// Function to Delete selected record from table
	public function delete_sub_record($id){
		$this->db->where('sub_department_id', $id);
		$this->db->delete('xin_sub_departments');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('department_id', $id);
		$data = $this->security->xss_clean($data);
		if( $this->db->update('xin_departments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// Function to update record in table
	public function update_sub_record($data, $id){
		$this->db->where('sub_department_id', $id);
		$data = $this->security->xss_clean($data);
		if( $this->db->update('xin_sub_departments',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// get all departments
	public function all_departments()
	{
	  $query = $this->db->query("SELECT * from xin_departments");
  	  return $query->result();
	}
	
	public function is_department_head($id) {

        $condition = "employee_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_departments');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
?>
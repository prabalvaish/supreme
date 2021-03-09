<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, OPTIONS");
    Header('Access-Control-Allow-Headers: *');
	class Company_Salary_Model extends CI_Model{
        public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_companies() {
        return $this->db->get("xin_companies");
      }

      // get single record > company | locations
	 public function ajax_company_location_information($id) {
	
		$sql = 'SELECT * FROM xin_office_location WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
        $output = '<option value="">Select Customer</option>';

		foreach($query->result() as $row)
  {
   $output .= '<option value="'.$row->location_id.'">'.$row->location_name.'</option>';
  }
  return $output;
    }
     // get single record > company | locations
	 public function ajax_location_location_information($id) {
	
		$sql = 'SELECT * FROM xin_departments WHERE location_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
        $output = '<option value="">Select Location</option>';

		foreach($query->result() as $row)
  {
   $output .= '<option value="'.$row->department_id.'">'.$row->department_name.'</option>';
  }
  return $output;
    }
    
// Department
public function ajax_location_departments_information($id) {
	
    $sql = 'SELECT * FROM xin_sub_departments WHERE department_id = ?';
    $binds = array($id);
    $query = $this->db->query($sql, $binds);
    $output = '<option value="">Select Department</option>';

    foreach($query->result() as $row)
{
$output .= '<option value="'.$row->sub_department_id.'">'.$row->department_name.'</option>';
}
return $output;
}

public function ajax_location_designation_information($id) {
	
    $sql = 'SELECT * FROM xin_designations WHERE sub_department_id = ?';
    $binds = array($id);
    $query = $this->db->query($sql, $binds);
    $output = '<option value="">Select Designation</option>';

    foreach($query->result() as $row)
        {
            $output .= '<option value="'.$row->designation_id.'">'.$row->designation_name.'</option>';
        }
        return $output;
    }

    public function ajax_allowances_data($data, $data1) {
       // if($f==0){
        $sql = 'SELECT * FROM xin_comapny_allowances WHERE location_id= ? AND designation_id= ?';
        $binds = array($data, $data1);
        $query = $this->db->query($sql, $binds);
        $l=count($query->result());
        //return $query->result();}
        //if($f==1){
        $sql1 = 'SELECT * FROM xin_company_detection WHERE location_id= ?';
        $binds1 = array($data);
        $query1 = $this->db->query($sql1, $binds1);
        $farray=array_merge($query->result(),$query1->result());
        $sql2 = 'SELECT *, xin_designations.basic_salary from xin_comapny_allowances inner join xin_designations on xin_comapny_allowances.designation_id = xin_designations.designation_id where xin_comapny_allowances.designation_id= ?';
        $binds2 = array($data1);
        $query2 = $this->db->query($sql2, $binds2);
        // Sum of Allowances
        $sql3 = 'SELECT SUM(xin_comapny_allowances.allowance_amount) allowances from xin_comapny_allowances WHERE designation_id=?';
        $binds3 = array($data1);
        $query3 = $this->db->query($sql3, $binds3);
        
        // Overtime Ammount
        $sql4 = 'SELECT overtime_id,overtime_title,	overtime_rate from xin_comapny_overtime WHERE designation_id=?';
        $binds4 = array($data1);
        $query4 = $this->db->query($sql4, $binds4);
        return  [$l,$farray,$query2->result(),$query3->result(), $query4->result()];
        }
        
     public function update_allowances($data,$id) {
        $this->db->update('xin_comapny_allowances',$data,'allowance_id ='.$id.'');
        $this->db->update('xin_salary_allowances',$data,'company_allowance_id ='.$id.'');

     }

     public function update_deduction($data,$id) {
        $this->db->update('xin_company_detection',$data,'statutory_deductions_id ='.$id.'');
        $this->db->update('xin_salary_deduction',$data,'company_deduction_id ='.$id.'');

     }
     public function add_company_overtime($data) {
        $this->db->insert('xin_comapny_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
        
         }

    }

    

?>
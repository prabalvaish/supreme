<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employees_summary_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_employees_summary($id,$id1,$id2) {
		
		$sql = 'Select xin_employees.employee_id as emp_id,
        xin_employees.first_name,
        xin_employees.last_name,
        xin_employees.email,
        xin_employees.pincode,
        xin_employees.date_of_birth,
        xin_employees.gender,
        xin_employees.date_of_joining,
        xin_employees.salary,
        xin_employees.address,
        xin_departments.department_name,
        xin_companies.name,
        xin_designations.designation_name,
        xin_office_location.location_name,
        xin_sub_departments.department_name,
        xin_salary_payslips.*
        from xin_employees inner join  xin_companies on xin_employees.company_id=xin_companies.company_id inner join xin_departments on xin_departments.department_id=xin_employees.department_id inner join xin_designations on xin_designations.designation_id=xin_employees.designation_id inner join xin_salary_payslips on xin_employees.user_id=xin_salary_payslips.employee_id inner join xin_office_location on xin_office_location.location_id=xin_employees.location_id inner join xin_sub_departments on xin_sub_departments.sub_department_id= xin_employees.sub_department_id  where xin_salary_payslips.company_id=?  AND xin_salary_payslips.location_id=? AND xin_salary_payslips.salary_month=?';
		$binds = array($id,$id1,$id2);
		$query = $this->db->query($sql, $binds);
	    return $query->result();
	}


}
?>
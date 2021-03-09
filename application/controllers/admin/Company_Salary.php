<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Ezmata License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.ezmata.com
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to work@ezmata.com so we can send you a copy immediately.
 *
 * @author   Ezmata
 * @author-email  work@ezmata.com
 * @copyright  Copyright © ezmata.com. All Rights Reserved
 */
header('Access-Control-Allow-Origin: *');


class Company_Salary extends MY_Controller {
	
	 public function __construct() {
		parent::__construct();
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Company_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Location_model");
		$this->load->model("Xin_model");
		$this->load->model("Company_Salary_Model");

	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	 public function index()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$data['title'] = $this->lang->line('xin_employees').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_company_set_salary');
		if(!in_array('13',$role_resources_ids)) {
			$data['path_url'] = 'myteam_employees';
		} else {
			$data['path_url'] = 'employees';
		}
		
		// reports to 
 		$reports_to = get_reports_team_data($session['user_id']);
		if(in_array('13',$role_resources_ids) || $reports_to > 0) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/location/dialog_salary", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
		
	 }
	 public function getCustomer() {
		
		 echo $this->Company_Salary_Model->ajax_company_location_information($this->input->post('company_id'));
		 
	 }
	 public function getLocation() {
		
		echo $this->Company_Salary_Model->ajax_location_location_information($this->input->post('location_id')); 
	}
	public function getDepartment() {
		
		echo $this->Company_Salary_Model->ajax_location_departments_information($this->input->post('department_id')); 
	}
	public function getDesignation() {
		
		echo $this->Company_Salary_Model->ajax_location_designation_information($this->input->post('subdepartment_id')); 
	}
	public function getAllowancesData() {
		$data= $this->Company_Salary_Model->ajax_allowances_data($this->input->post('location_id'),$this->input->post('designation_id'));
		//$data1 = $this->company_salary_model->ajax_summary_data($this->input->post('designation_id'));	
		//$datafinal=array_merge($data,$data1);	
		//echo json_encode($datafinal);
		echo json_encode($data);
	}

	public function update_allowance_option() {
		$data = array(
		'allowance_title' => $this->input->post('allowance_title'),
		'allowance_amount' => $this->input->post('allowance_amount'),
		'pf_option' => $this->input->post('pf_option')
		);
		var_dump($data);
		$result = $this->Company_Salary_Model->update_allowances($data,$this->input->post('allowance_id'));
		echo json_encode($result);
	}
	// Update Deduction
	public function update_deduction_option() {
		$data = array(
		'deduction_title' => $this->input->post('deduction_title'),
		'deduction_amount' => $this->input->post('deduction_amount')
		);
		var_dump($data);
		$result = $this->Company_Salary_Model->update_deduction($data,$this->input->post('statutory_deductions_id'));
		echo json_encode($result);
	}
	// Add Company Overtime
	public function company_overtime_rate() {
		$data= array (
			'company_id' => $this->input->post('company_id'),
			'customer_id' =>$this->input->post('customer_id'),
			'location_id' => $this->input->post('location_id'),
			'department_id' => $this->input->post('department_id'),
			'designation_id' => $this->input->post('designation_id'),
			'overtime_title' => $this->input->post('overtime_title'),
			'overtime_rate' => $this->input->post('overtime_rate')
		);
		$this->Company_Salary_Model->add_company_overtime($data);
	}
}

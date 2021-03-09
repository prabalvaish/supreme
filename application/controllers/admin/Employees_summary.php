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
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_summary extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Employees_model");
		$this->load->model("Xin_model");
		$this->load->model("Designation_model");
        $this->load->model("Department_model");
        $this->load->model("Employees_summary_model");

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
		 $data['title'] = $this->lang->line('xin_Summary').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_Summary');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		// reports to 
 		$reports_to = get_reports_team_data($session['user_id']);
		if(in_array('22',$role_resources_ids) || $reports_to > 0) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/employees_summary/employees_summary", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}		  
     }

     public function getSummary() {
		 $data= $this->Employees_summary_model->get_employees_summary($this->input->post('company_id'),$this->input->post('location_id'),$this->input->post('salary_month'));
         echo json_encode($data);
	}
}

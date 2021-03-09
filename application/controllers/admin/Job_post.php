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

class Job_post extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Job_post_model");
		$this->load->model("Xin_model");
		$this->load->library('email');
		$this->load->helper('string');
		$this->load->model('Users_model');
		$this->load->model("Designation_model");
		$this->load->model("Recruitment_model");
		$this->load->library('Pdf');

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
		$system = $this->Xin_model->read_setting_info(1);
		if($system[0]->module_recruitment!='true'){
			redirect('admin/dashboard');
		}
		$data['title'] = $this->lang->line('left_job_posts').' | '.$this->Xin_model->site_title();
		
		$data['breadcrumbs'] = $this->lang->line('left_job_posts');
		$data['path_url'] = 'job_post';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('49',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/job_post/job_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
     }
	 public function jobs_dashboard()
     {
     } 
	 public function employer()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_jobs_employers').' | '.$this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		//$data['get_company_types'] = $this->Company_model->get_company_types();
		$data['breadcrumbs'] = $this->lang->line('xin_jobs_employers');
		$data['path_url'] = 'jobs_employer';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('5',$role_resources_ids)) {
			$data['subview'] = $this->load->view("admin/job_post/employer_list", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
     }
	 public function read_employer() {
		
		$session = $this->session->userdata('username');
		if(empty($session)){
			redirect('admin/');
		}
		$user_id= $this->input->get('user_id');
		$result = $this->Users_model->read_users_info($user_id);
		$data = array(
		'user_id' => $result[0]->user_id,
		'first_name' => $result[0]->first_name,
		'middle_name' => $result[0]->middle_name,
		'last_name' => $result[0]->last_name,
		'company_name' => $result[0]->company_name,
		'email' => $result[0]->email,
		'password' => $result[0]->password,
		'gender' => $result[0]->gender,
		'is_active' => $result[0]->is_active,
		'profile_photo' => $result[0]->profile_photo,
		'profile_background' => $result[0]->profile_background,
		'contact_number' => $result[0]->contact_number
		);
		if(!empty($session)){ 
			$this->load->view('admin/job_post/dialog_employer', $data);
		} else {
			redirect('admin/');
		}
     }
	 public function employer_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/job_post/employer_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		$all_employers = $this->Recruitment_model->get_employers();
		$data = array();

          foreach($all_employers->result() as $r) {
			  			  
			  if(in_array('247',$role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-user_id="'. $r->user_id . '"><span class="fas fa-pencil-alt"></span></button></span>';
			} else {
				$edit = '';
			}
			if(in_array('248',$role_resources_ids)) { // delete
				$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-sm btn-outline-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '"><span class="fas fa-trash-restore"></span></button></span>';
			} else {
				$delete = '';
			}
			$combhr = $edit.$delete;//
			
			$app_row = $this->Job_post_model->employer_applications_available($r->user_id);
			if($app_row > 0) {
				$app_available = '<a class="badge bg-purple btn-sm" href="'.site_url('admin/job_candidates/').'by_employer/'.$r->user_id.'" target="_blank"><i class="fa fa-list"></i> '.$this->lang->line('xin_view_job_applicants').'</a>';
			} else {
				$app_available = '0';
			}
			$fname = $r->first_name.' '.$r->last_name;
			if($r->is_active == 1){
				$is_active = $fname.'<br><span class="badge badge-success">'.$this->lang->line('xin_employees_active').'</span>';
			} else {
				$is_active = $fname.'<br><span class="badge badge-danger">'.$this->lang->line('xin_employees_inactive').'</span>';
			}
			//$icname = $r->name.'<br><small class="text-muted"><i>'.$this->lang->line('xin_type').': '.$type_name.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('dashboard_contact').'#: '.$r->contact_number.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_website').': '.$r->website_url.'<i></i></i></small>';
		   $data[] = array(
				$combhr,
				$is_active,
				$r->company_name,
				$r->email,
				$r->contact_number,
				$app_available
		   );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $all_employers->num_rows(),
                 "recordsFiltered" => $all_employers->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
	 }
	 








	 //  Email Send
	public function send_offer()
	{
		$system = $this->Xin_model->read_setting_info(1);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$key = $this->uri->segment(4);
		$user = $this->Xin_model->read_user_info($key);
		if (is_null($user)) {
			redirect('admin/employees');
		}
		if (!in_array('421', $role_resources_ids)) {
			redirect('admin/employees');
		}

		$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($_des_name)) {
			$_designation_name = $_des_name[0]->designation_name;
		} else {
			$_designation_name = '';
		}
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$_department_name = $department[0]->department_name;
		} else {
			$_department_name = '';
		}
		$fname = $user[0]->first_name . ' ' . $user[0]->middle_name . ' ' . $user[0]->last_name;
		$salary = $user[0]->basic_salary * 12;
		// company info
		$company = $this->Xin_model->read_company_info($user[0]->company_id);
		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '--';
			}
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} else {
			$company_name = '--';
			$address_1 = '--';
			$address_2 = '--';
			$city = '--';
			$state = '--';
			$zipcode = '--';
			$country_name = '--';
			$c_info_email = '--';
			$c_info_phone = '--';
		}
		$location = $this->Location_model->read_location_information($user[0]->location_id);
		if (!is_null($location)) {
			$location_name = $location[0]->location_name;
		} else {
			$location_name = '--';
		}
		// $user_role = $this->Roles_model->read_role_information($user[0]->user_role_id);
		// if(!is_null($user_role)){
		// 	$iuser_role = $user_role[0]->role_name;
		// } else {
		// 	$iuser_role = '--';
		// }
		// set default header data
		//$c_info_address = $address_1.' '.$address_2.', '.$city.' - '.$zipcode.', '.$country_name;
		// $c_info_address = $address_1.' '.$address_2.', '.$city.' - '.$zipcode;
		//$email_phone_address = "$c_info_address \n".$this->lang->line('xin_phone')." : $c_info_phone | ".$this->lang->line('dashboard_email')." : $c_info_email ";

		// $company_info = $this->lang->line('left_company').": $company_name | ".$this->lang->line('left_location').": $location_name \n";
		$designation_info = $this->lang->line('left_designation') . ": $_designation_name  and " . $this->lang->line('left_department') . ": $_department_name";

		// $header_string = "$company_info"."$designation_info";
		// set document information
		$pdf->SetCreator('Ezmata');
		$pdf->SetAuthor('Ezmata');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		//$pdf->SetTitle('Workable-Zone - Payslip');
		//$pdf->SetSubject('TCPDF Tutorial');
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// if($user[0]->profile_picture!='' && $user[0]->profile_picture!='no file') {
		// 	$ol = 'uploads/profile/'.$user[0]->profile_picture;
		// } else {
		// 	if($user[0]->gender=='Male') { 
		// 		$de_file = 'uploads/profile/default_male.jpg';
		// 	 } else {
		// 		$de_file = 'uploads/profile/default_female.jpg';
		// 	 }
		// 	$ol = $de_file;
		// }

		$header_namae = $fname . ' ' . $this->lang->line('xin_profile');
		// $pdf->SetHeaderData('../../../'.$ol, 15, $header_namae, $header_string);

		$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

		// set header and footer fonts
		$pdf->setHeaderFont(array('helvetica', '', 11.5));
		$pdf->setFooterFont(array('helvetica', '', 9));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont('courier');

		// set margins
		$pdf->SetMargins(15, 27, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 25);

		// set image scale factor
		// $pdf->setImageScale(1.25);
		$pdf->SetAuthor('Ezmata');
		// $pdf->SetTitle($company_name.' - '.$this->lang->line('xin_download_profile_title'));
		// $pdf->SetSubject($this->lang->line('xin_download_profile_title'));
		// $pdf->SetKeywords($this->lang->line('xin_download_profile_title'));
		// set font
		$pdf->SetFont('helvetica', 'B', 10);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 10, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		/*$tbl = '<br>
	<table cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td align="center"><h1>'.$fname.'</h1></td>
		</tr>
	</table>
	';
	$pdf->writeHTML($tbl, true, false, false, false, '');*/
		// -----------------------------------------------------------------------------
		$date_of_joining = $this->Xin_model->set_date_format($user[0]->date_of_joining);

		// set cell padding
		$pdf->setCellPaddings(1, 1, 1, 1);

		// set cell margins
		$pdf->setCellMargins(0, 0, 0, 0);

		// set color for background
		$pdf->SetFillColor(255, 255, 127);
		/////////////////////////////////////////////////////////////////////////////////
		if ($user[0]->marital_status == 'Single') {
			$mstatus = $this->lang->line('xin_status_single');
		} else if ($user[0]->marital_status == 'Married') {
			$mstatus = $this->lang->line('xin_status_married');
		} else if ($user[0]->marital_status == 'Widowed') {
			$mstatus = $this->lang->line('xin_status_widowed');
		} else if ($user[0]->marital_status == 'Divorced or Separated') {
			$mstatus = $this->lang->line('xin_status_divorced_separated');
		} else {
			$mstatus = $this->lang->line('xin_status_single');
		}
		if ($user[0]->is_active == '0') {
			$isactive = $this->lang->line('xin_employees_inactive');
		} else if ($user[0]->is_active == '1') {
			$isactive = $this->lang->line('xin_employees_active');
		} else {
			$isactive = $this->lang->line('xin_employees_inactive');
		}
		$tbl_12 = '
	<p><strong>Date: ' . date("d/m/y") . ' </strong>&nbsp; &nbsp; &nbsp;&nbsp;</p>
	<p>
			<strong>Dear Mrs/Ms/Mr.&nbsp;</strong><strong>' . $user[0]->first_name . ' ' . $user[0]->middle_name . ' ' . $user[0]->last_name . ',
		</strong></p>

		<p>
			With reference to your application and the subsequent interview/s you had with the management
			team, we are pleased to offer you employment "' . $designation_info . '",
			on the terms and conditions agreed upon at the time of the final interview on <strong>
			' . $date_of_joining . ';
			</strong> at
			our <strong>
			' . $location_name . ' 
			</strong>. You will be deputed at our <strong>
			' . $location_name . ' 
			</strong>.
			' . $salary . '
		</p>
		<p><strong>As discussed you must join on or before&nbsp;</strong><strong>' . $date_of_joining . '</strong>
		</p>
		<p> Your total enmoluments are Rs. ' . $salary . ' per Month.</p>
		<p>On joining day please contact HR at 9.30 am, with the documents mentioned below to
			complete the joining formalities at our Head Office.
		</p>
		<p> Please confirm &amp; revert with the acceptance.</p>

		<p><strong>Note: On joining date, you should bring copies of the following documents for
			submission along with originals for verification.</strong>
		</p>

		<ul>
			<li>All qualification certificates.</li>
			<li>Date of birth proof.</li>
			<li>Two passport size photographs.</li>
			<li>Application blank (to be collected from HR/ respective Manager).</li>
			<li>Latest salary slip from the last organization.</li>
			<li>Work experience/relieving letter from the last organization.</li>
			<li>PAN / Adhar.</li>
			<li>Bank Passbook / Cancelled Cheque</li>
			<li>ESI / PF Numbers if any</li>
		  </ul>
		  <br>
		  <br>

<p>
		For Supreme&gt <br>
		<!-- Space for signature. --><br>
		<br>
		Authorized Signatory<br>
		<br>
		<a href ="https://www.googel.com">Click Here to Accept</a>
		</p>';
		//	$events = $this->Events_model->get_employee_events($user[0]->user_id);</p>';
		$pdf->writeHTML($tbl_12, true, false, false, false, '');



		ob_start();

		// $pdf->Output('payslip_'.$fname.'_'.$pay_month.'.pdf', 'I');
		$pdf->Output(dirname(__FILE__) . 'OfferLetter' . '.pdf', 'F');
		$config = array(
			'protocol' 	=> 'smtp',
			'smtp_host' => 'smtp.office365.com',
			'smtp_port' => 587,
			'smtp_user' => 'work@ezmata.com',
			'smtp_pass' => 'VQvdg666',
			'smtp_crypto' => 'tls',
			'mailtype' 	=> 'html',
			'charset' 	=> 'utf-8',
			'wordwrap' 	=> TRUE
		);
		$msg = '<p xss="removed"><strong>Date: ' . date("d/m/y") . ' </strong></p>
<p>
		<strong>Dear Mrs/Ms/Mr.&nbsp;</strong><strong><span xss="removed">' . $user[0]->first_name . ' ' . $user[0]->middle_name . ' ' . $user[0]->last_name . '</span>,
	</strong></p>

	<p>
		With reference to your application and the subsequent interview/s you had with the management
		team, we are pleased to offer you employment "' . $designation_info . '",
		on the terms and conditions agreed upon at the time of the final interview on <strong>
		' . $date_of_joining . ';
		</strong> at
		our <strong>
		' . $location_name . ' 
		</strong>. You will be deputed at our <strong>
		' . $location_name . ' 
		</strong>.
	</p>
	<p><strong>As discussed you must join on or before&nbsp;</strong><span xss="removed"><strong>' . $date_of_joining . '</strong></span><strong>. </strong>
	</p>
	<p>On joining day please contact HR at 9.30 am, with the documents mentioned below to
		complete the joining formalities at our Head Office.
	</p>
	<p> Please confirm &amp; revert with the acceptance.</p>

	<p><strong>Note: On joining date, you should bring copies of the following documents for
		submission along with originals for verification.</strong>
	</p>

	<ul>
		<li>All qualification certificates.</li>
		<li>Date of birth proof.</li>
		<li>Two passport size photographs.</li>
		<li>Application blank (to be collected from HR/ respective Manager).</li>
		<li>Latest salary slip from the last organization.</li>
		<li>Work experience/relieving letter from the last organization.</li>
		<li>PAN / Adhar.</li>
		<li>Bank Passbook / Cancelled Cheque</li>
		<li>ESI / PF Numbers if any</li>
	  </ul>
	  <br>
	  <br>

<p>
	For &lt;Supreme&gt; <br>
	<!-- Space for signature. --><br>
	<br>
	Authorized Signatory<br>
	<br>
	<a href ="https://www.youtube.com/watch?v=m6Y8xEfyXTs&list=RDm6Y8xEfyXTs&start_radio=1">Click Here to Accept</a>
	</p>';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('work@ezmata.com');
		$this->email->to('prabal@ezmata.com');
		$this->email->subject('Offer Letter');
		$this->email->message($msg);

		$this->email->attach('application/controllers/adminOfferLetter.pdf');
		if ($this->email->send()) {
			unlink('application/controllers/adminOfferLetter.pdf');
			redirect('/admin/employees/detail/' . $key);
			echo "success";
			$this->session->set_flashdata('message', 'Offer Letter Sended');
		} else {

			$this->session->set_flashdata('message', 'There is an error in email send');
			redirect('/admin/employees/detail/' . $key);
		}

		ob_end_flush();
	}




























 
    public function job_list()
     {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/job_post/job_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$jobs = $this->Job_post_model->get_jobs();
		$data = array();
		
        foreach($jobs->result() as $r) {
			 			  
		// get job designation
		$category = $this->Job_post_model->read_job_category_info($r->category_id);
		if(!is_null($category)){
			$category_name = $category[0]->category_name;
		} else {
			$category_name = '--';
		}
		// get job type
		$job_type = $this->Job_post_model->read_job_type_information($r->job_type);
		if(!is_null($job_type)){
			$jtype = $job_type[0]->type;
		} else {
			$jtype = '--';
		}
		// get date
		$date_of_closing = $this->Xin_model->set_date_format($r->date_of_closing);
		$created_at = $this->Xin_model->set_date_format($r->created_at);
		/* get job status*/
		if($r->status==1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_call_for_interview').'</span>'; 
		elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_pending').'</span>';
		elseif($r->status==3): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';
		elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_rejected').'</span>';
		elseif($r->status==5): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
		
	    else: $status = '<span class="badge bg-orange">'.$this->lang->line('xin_unpublished').'</span>'; endif;
		$employer = $this->Recruitment_model->read_employer_info($r->employer_id);
		if(!is_null($employer)){
			$employer_name = $employer[0]->company_name;
		} else {
			$employer_name = '--';	
		}
		
		if(in_array('292',$role_resources_ids)) { //edit
			$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-job_id="'. $r->job_id . '"><span class="fas fa-pencil-alt"></span></button></span>';
		} else {
			$edit = '';
		}
		if(in_array('293',$role_resources_ids)) { // delete
			$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-sm btn-outline-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->job_id . '"><span class="fas fa-trash-restore"></span></button></span>';
		} else {
			$delete = '';
		}
		//if(in_array('293',$role_resources_ids)) { //view
			$view = '<a href="'.site_url().'jobs/detail/'.$r->job_url.'" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"><span class="fa fa-eye"></span></button></a>';
		//} else {
			//$view = '';
		//}
		$combhr = $edit.$view.$delete;
		$app_row = $this->Job_post_model->job_applications_available($r->job_id);
		if($app_row > 0) {
			$app_available = '<br><a class="badge bg-purple btn-sm" href="'.site_url('admin/job_candidates/').'by_job/'.$r->job_id.'" target="_blank"><i class="fa fa-list"></i> '.$this->lang->line('xin_job_applicants_title').'</a>';
		} else {
			$app_available = '';
		}
	//	$ijob_title = $r->job_title.'<br><small class="text-muted"><i>'.$status.' '.$jtype.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_role_added_date').': '.$created_at.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_hr_jb_positions').': '.$r->job_vacancy.'<i></i></i></small>';
		$ijob_title = $r->job_title.'<br><small class="text-muted">'.$category_name.'</small>'.$app_available;
		
		//send-mail
		$sendmail = '<a href="'.site_url().'admin/job_post/send_offer" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"><span class="fa fa-eye"></span></button></a>';


		$data[] = array(
			$combhr,
			$r->employer_id ,
			// $employer_name,
			$ijob_title,
			// $r->long_description ,
			$date_of_closing,
		   $r->is_featured ,
		   $r->job_vacancy ,
			// $created_at,
			$status,
			$sendmail,
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $jobs->num_rows(),
			 "recordsFiltered" => $jobs->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 // Validate and add info in database
	public function add_employer() {
	
		if($this->input->post('add_type')=='employer') {
		// Check validation for user input
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		
		//$file = $_FILES['photo']['tmp_name'];
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$valid_email = $this->Users_model->check_user_email($this->input->post('email'));
		$options = array('cost' => 12);
		$password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT, $options);
		/* Server side PHP input validation */
		if($this->input->post('company_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_company_name');
		} else if($this->input->post('first_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_first_name');
		} else if( $this->input->post('last_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_last_name');
		} else if($this->input->post('email')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_email');
		} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
			$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
		} else if($valid_email->num_rows() > 0) {
			$Return['error'] = $this->lang->line('xin_rec_email_exists');
		} else if($this->input->post('password')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_password');
		} else if($this->input->post('contact_number')==='') {
			$Return['error'] = $this->lang->line('xin_error_contact_field');
		} else if($_FILES['company_logo']['size'] == 0) {
			$Return['error'] = $this->lang->line('xin_rec_error_company_logo_field');
		} else {
			if(is_uploaded_file($_FILES['company_logo']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['company_logo']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["company_logo"]["tmp_name"];
					$bill_copy = "uploads/employers/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$lname = basename($_FILES["company_logo"]["name"]);
					$newfilename = 'employer_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $bill_copy.$newfilename);
					$fname = $newfilename;
					$data = array(
					'company_name' => $this->input->post('company_name'),
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'email' => $this->input->post('email'),
					'password' => $password_hash,
					'contact_number' => $this->input->post('contact_number'),
					'is_active' => 1,
					'user_type' => 1,
					'company_logo' => $fname,		
					'created_at' => date('d-m-Y h:i:s')
					);
					// add record > model
					$result = $this->Users_model->add($data);
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}
		}
		if($Return['error']!=''){
       		$this->output($Return);
    	}	
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_jobs_employer_added_success');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	// Validate and update info in database
	public function update_employer() {
	
		if($this->input->post('edit_type')=='employer') {
		$session = $this->session->userdata('username');		
		//$file = $_FILES['company_logo']['tmp_name'];
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$id = $this->input->post('_token');
		/* Server side PHP input validation */
		if($this->input->post('company_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_company_name');
		} else if($this->input->post('first_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_first_name');
		} else if( $this->input->post('last_name')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_last_name');
		} else if($this->input->post('email')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_email');
		} else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
			$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
		}
		/* Check if file uploaded..*/
		else if($_FILES['company_logo']['size'] == 0) {
			
			 $no_logo_data = array(
				'company_name' => $this->input->post('company_name'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'is_active' => $this->input->post('is_active'),
				'contact_number' => $this->input->post('contact_number'),
			);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			 $result = $this->Users_model->update_record_no_photo($no_logo_data,$id);
		} else {
			if(is_uploaded_file($_FILES['company_logo']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','gif');
				$filename = $_FILES['company_logo']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["company_logo"]["tmp_name"];
					$bill_copy = "uploads/employers/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$lname = basename($_FILES["company_logo"]["name"]);
					$newfilename = 'employer_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $bill_copy.$newfilename);
					$fname = $newfilename;
					$data = array(
					'company_name' => $this->input->post('company_name'),
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'email' => $this->input->post('email'),
					'is_active' => $this->input->post('is_active'),
					'contact_number' => $this->input->post('contact_number'),
					'company_logo' => $fname,		
					);
					// update record > model
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$result = $this->Users_model->update_record($data,$id);
				} else {
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}
		}
		
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_jobs_employer_updated_success');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$this->output($Return);
		exit;
		}
	}
	 
	 // get company > designations
	 public function get_designations() {

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);
		
		$data = array(
			'company_id' => $id
			);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/job_post/get_designations", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	 }
	 
	 public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('job_id');
		$result = $this->Job_post_model->read_job_information($id);
		$data = array(
				'job_id' => $result[0]->job_id,
				'employer_id' => $result[0]->employer_id,
				'job_title' => $result[0]->job_title,
				'category_id' => $result[0]->category_id,
				'job_type_id' => $result[0]->job_type,
				'job_vacancy' => $result[0]->job_vacancy,
				'is_featured' => $result[0]->is_featured,
				'gender' => $result[0]->gender,
				'minimum_experience' => $result[0]->minimum_experience,
				'date_of_closing' => $result[0]->date_of_closing,
				'short_description' => $result[0]->short_description,
				'long_description' => $result[0]->long_description,
				'status' => $result[0]->status,
				'all_designations' => $this->Designation_model->all_designations(),
				'all_job_types' => $this->Job_post_model->all_job_types(),
				'all_companies' => $this->Xin_model->get_companies()
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/job_post/dialog_job_post', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and add info in database
	public function add_job() {
	
		if($this->input->post('add_type')=='job') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		$long_description = $_POST['long_description'];	
		$short_description = $_POST['short_description'];	
		$qt_short_description = htmlspecialchars(addslashes($short_description), ENT_QUOTES);
		$qt_description = htmlspecialchars(addslashes($long_description), ENT_QUOTES);
		
		if($this->input->post('company')==='') {
       		$Return['error'] = $this->lang->line('xin_error_company');
		} else if($this->input->post('job_title')==='') {
       		$Return['error'] = $this->lang->line('xin_error_jobpost_title');
		} else if($this->input->post('job_type')==='') {
			$Return['error'] = $this->lang->line('xin_error_jobpost_type');
		} else if($this->input->post('designation_id')==='') {
			$Return['error'] = $this->lang->line('xin_error_jobpost_designation');
		} else if($this->input->post('vacancy')==='') {
			$Return['error'] = $this->lang->line('xin_error_jobpost_positions');
		} else if($this->input->post('date_of_closing')==='') {
       		$Return['error'] = $this->lang->line('xin_error_jobpost_closing_date');
		} else if($qt_short_description==='') {
       		$Return['error'] = $this->lang->line('xin_error_jobpost_short_description');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		$jurl = random_string('alnum', 40);
		$data = array(
		'job_title' => $this->input->post('job_title'),
		'employer_id' => $this->input->post('user_id'),
		'job_type' => $this->input->post('job_type'),
		'category_id' => $this->input->post('category_id'),
		'job_url' => $jurl,
		'short_description' => $qt_short_description,
		'long_description' => $qt_description,
		'status' => $this->input->post('status'),
		'is_featured' => $this->input->post('is_featured'),
		'job_vacancy' => $this->input->post('vacancy'),
		'date_of_closing' => $this->input->post('date_of_closing'),
		'gender' => $this->input->post('gender'),
		'minimum_experience' => $this->input->post('experience'),
		'created_at' => date('Y-m-d h:i:s'),
		
		);
		$result = $this->Job_post_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_job_added');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='job') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		$long_description = $_POST['long_description'];	
		$short_description = $_POST['short_description'];	
		$qt_short_description = htmlspecialchars(addslashes($short_description), ENT_QUOTES);
		$qt_description = htmlspecialchars(addslashes($long_description), ENT_QUOTES);
		
		if($this->input->post('company')==='') {
       		$Return['error'] = $this->lang->line('xin_error_company');
		} else if($this->input->post('job_title')==='') {
       		$Return['error'] = $this->lang->line('xin_error_jobpost_title');
		} else if($this->input->post('job_type')==='') {
			$Return['error'] = $this->lang->line('xin_error_jobpost_type');
		} else if($this->input->post('designation_id')==='') {
			$Return['error'] = $this->lang->line('xin_error_jobpost_designation');
		} else if($this->input->post('vacancy')==='') {
			$Return['error'] = $this->lang->line('xin_error_jobpost_positions');
		} else if($this->input->post('date_of_closing')==='') {
       		$Return['error'] = $this->lang->line('xin_error_jobpost_closing_date');
		} else if($qt_short_description==='') {
       		$Return['error'] = $this->lang->line('xin_error_jobpost_short_description');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'job_title' => $this->input->post('job_title'),
		'employer_id' => $this->input->post('user_id'),
		'job_type' => $this->input->post('job_type'),
		'category_id' => $this->input->post('category_id'),
		'short_description' => $qt_short_description,
		'long_description' => $qt_description,
		'status' => $this->input->post('status'),
		'is_featured' => $this->input->post('is_featured'),
		'job_vacancy' => $this->input->post('vacancy'),
		'date_of_closing' => $this->input->post('date_of_closing'),
		'gender' => $this->input->post('gender'),
		'minimum_experience' => $this->input->post('experience')		
		);
		
		$result = $this->Job_post_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_job_updated');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	
	public function delete() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Job_post_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_job_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
	public function delete_employer() {
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Job_post_model->delete_employer_record($id);
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_jobs_employer_deleted_success');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
	public function pages() {
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_jobs_cms_pages').' | '.$this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_jobs_cms_pages');
		$data['path_url'] = 'jobs_cms_pages';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(in_array('63',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/job_post/pages_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}		  
     } 
	
	//cms pages_list
	  public function pages_list() {

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/job_post/pages_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$pages = $this->Job_post_model->get_cms_pages();

		$data = array();

        foreach($pages->result() as $r) {
									 			  				
		$data[] = array('<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-page_id="'. $r->page_id . '"><span class="fas fa-pencil-alt"></span></button></span>',
			$r->page_title,
			$r->page_url
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $pages->num_rows(),
			 "recordsFiltered" => $pages->num_rows(),
			 "data" => $data
		);
		
	  echo json_encode($output);
	  exit();
     } 
	 
	public function read_pages()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('page_id');
		$result = $this->Job_post_model->read_cms_pages($id);
		$data = array(
			'page_id' => $result[0]->page_id,
			'page_title' => $result[0]->page_title,
			'page_url' => $result[0]->page_url,
			'page_details' => $result[0]->page_details,
			'created_at' => $result[0]->created_at,
		);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/job_post/dialog_pages', $data);
		} else {
			redirect('admin/');
		}
	} 
	// Validate and update info in database
	public function update_pages() {
	
		if($this->input->post('edit_type')=='update_page') {
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		
		if($this->input->post('page_details')==='') {
			$Return['error'] = $this->lang->line('xin_jobs_page_content_field_error');
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}
		
		$page_details = $this->input->post('page_details');
		$new_page_details = htmlspecialchars(addslashes($page_details), ENT_QUOTES);
	
		$data = array(
		'page_details' => $new_page_details
		);
		
		$result = $this->Job_post_model->update_page_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_jobs_page_updated_success');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
}

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

class Register extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Xin_model');
		$this->load->model("Job_post_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Xin_recruitment_model");
	}
	
	public function index()
	{		
		$data['title'] = $this->Xin_model->site_title().' | Log in';
		$data['subview'] = $this->load->view("frontend/register", $data, TRUE);
		$this->load->view('frontend/layout/job_layout_main', $data); //page load
	}
}
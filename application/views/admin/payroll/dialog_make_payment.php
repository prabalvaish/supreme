<!-- Calculate Number of Sunday In the current month -->

<?php
$month = date('m');
$year = date('Y');
$sundays = 0;
$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

for ($i = 1; $i <= $total_days; $i++)
  if (date('N', strtotime($year . '-' . $month . '-' . $i)) == 7)
    $sundays++;
$month_working_days = $total_days-$sundays;
?>


<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='monthly_payment'){ ?>
<?php
$system = $this->Xin_model->read_setting_info(1);
$payment_month = strtotime($this->input->get('pay_date'));
$p_month = date('F Y',$payment_month);
if($wages_type==1){
	if($system[0]->is_half_monthly==1){
		//if($half_deduct_month==2){
			$basic_salary = $basic_salary / 2;
		//} else {
			//$basic_salary = $basic_salary;
		//}
	} else {
		$basic_salary = $basic_salary;
	}
} else {
	$basic_salary = $daily_wages;
}
?>
<?php
$details = $this->Employees_model->sallary_emp_details($user_id);
foreach($details as $detail){ 
	$gender= $detail->gender;
	$state=$detail->department_name;
	$total_basic=$detail->basic_salary;
	$location = $detail->location_id;
	$allow = $detail->pf_allow;
	$designation = $detail->designation_name;
	$tds_type = $detail->tds_type;
  }
//   echo $tds_type;

$salary_allowances = $this->Employees_model->read_salary_allowances($user_id);
$count_allowances = $this->Employees_model->count_employee_allowances($user_id);
$first_day= date('Y-m-01');
$last_day  = date('Y-m-t');
  $total_days = $this->Employees_model->total_days($user_id,$first_day,$last_day);
foreach($total_days as $present){
	$present_days=$present->Total;
  }
  $esic = $this->Employees_model->getEsic($location); 
foreach ($esic as $result){
  $is_esic = $result->is_esic;
}
// Get Salary Type
$salary_type = $this->Employees_model->salary_type($location);
foreach($salary_type as $result){
  $employee_salary_type=$result->salary_type;
}
echo $employee_salary_type;

$first_day= date('Y-m-01');
$last_day  = date('Y-m-t');
 $check = $this->Timesheet_model->checkLeave($user_id,$first_day,$last_day);
foreach($check as $leave){
  $leave_days = $leave->leave_days;
  $days = $leave->Days;
}
$total_leaves=$leave_days+$days;
  $present_days=$present_days+$total_leaves;
// echo $present_days;

$fraction=$present_days/$month_working_days;
$basic_salary=$basic_salary*$fraction;
$allowance_amount = 0;
$eallowance_amount = 0;
$iallowance_amount = 0;
$pf_pay=0;
$allow_array=[];
// var_dump($salary_allowances);
if($count_allowances > 0) {
	foreach($salary_allowances as $sl_allowances){
		$tax_allowance = $sl_allowances->pf_option;
		// var_dump($tax_allowance);
		//$allowance_amount += $sl_allowances->allowance_amount;
	  if($system[0]->is_half_monthly==1){
		 if($system[0]->half_deduct_month==2){
			 $eallowance_amount = $sl_allowances->allowance_amount/2;
		 } else {
			 $eallowance_amount = $sl_allowances->allowance_amount;
		 }
		 $allowance_amount += $eallowance_amount;
	  } else {
		  if($sl_allowances->is_allowance_taxable == 1) {
			  if($sl_allowances->amount_option == 0) {
				  $iallowance_amount = $sl_allowances->allowance_amount;
			  } else {
				  $iallowance_amount = $basic_salary / 100 * $sl_allowances->allowance_amount;
			  }
			 $allowance_amount -= $iallowance_amount; 
		  } else if($sl_allowances->is_allowance_taxable == 2) {
			  if($sl_allowances->amount_option == 0) {
				  $iallowance_amount = $sl_allowances->allowance_amount / 2;
			  } else {
				  $iallowance_amount = ($basic_salary / 100) / 2 * $sl_allowances->allowance_amount;
			  }
			 $allowance_amount -= $iallowance_amount; 
		  }else {
			if($sl_allowances->amount_option == 0) {
// shu
				$iallowance_amount = ($sl_allowances->allowance_amount)*$fraction;
			} else {
				$iallowance_amount = $basic_salary / 100 * ($sl_allowances->allowance_amount)*$fraction;
}
// shu
			$allowance_amount += $iallowance_amount;
		}
	  }
	  if($tax_allowance==1){
		$pf_pay+=$iallowance_amount;
	
	  }
	  $allow_array[$sl_allowances->allowance_title]=$iallowance_amount;
		
	}
// print_r($allow_array);
}
// 3: all loan/deductions
$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($user_id);
$count_loan_deduction = $this->Employees_model->count_employee_deductions($user_id);
$loan_de_amount = 0;
if($count_loan_deduction > 0) {
	foreach($salary_loan_deduction as $sl_salary_loan_deduction){
		if($system[0]->is_half_monthly==1){
			  if($system[0]->half_deduct_month==2){
				  $er_loan = $sl_salary_loan_deduction->loan_deduction_amount/2;
			  } else {
				  $er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
			  }
		  } else {
			  $er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
		  }
		  $loan_de_amount += $er_loan;
	}
} else {
	$loan_de_amount = 0;
}
// 4: other payment
$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
$other_payments_amount = 0;
if(!is_null($other_payments)):
	foreach($other_payments->result() as $sl_other_payments) {
		if($system[0]->is_half_monthly==1){
		  if($system[0]->half_deduct_month==2){
			  $epayments_amount = $sl_other_payments->payments_amount/2;
		  } else {
			  $epayments_amount = $sl_other_payments->payments_amount;
		  }
		  $other_payments_amount += $epayments_amount;
	  } else {
		  //$epayments_amount = $sl_other_payments->payments_amount;
		  if($sl_other_payments->is_otherpayment_taxable == 1) {
			  if($sl_other_payments->amount_option == 0) {
				  $epayments_amount = $sl_other_payments->payments_amount;
			  } else {
				  $epayments_amount = $basic_salary / 100 * $sl_other_payments->payments_amount;
			  }
			 $other_payments_amount -= $epayments_amount; 
		  } else if($sl_other_payments->is_otherpayment_taxable == 2) {
			  if($sl_other_payments->amount_option == 0) {
				  $epayments_amount = $sl_other_payments->payments_amount / 2;
			  } else {
				  $epayments_amount = ($basic_salary / 100) / 2 * $sl_other_payments->payments_amount;
			  }
			 $other_payments_amount -= $epayments_amount; 
		  } else {
			  if($sl_other_payments->amount_option == 0) {
				  $epayments_amount = $sl_other_payments->payments_amount;
			  } else {
				  $epayments_amount = $basic_salary / 100 * $sl_other_payments->payments_amount;
			  }
			  $other_payments_amount += $epayments_amount;
		  }
	  }
	  
	}
endif;
// all other payment
$all_other_payment = $other_payments_amount;
// 5: commissions
$commissions = $this->Employees_model->set_employee_commissions($user_id);
if(!is_null($commissions)):
	$commissions_amount = 0;
	foreach($commissions->result() as $sl_commissions) {
		 if($system[0]->is_half_monthly==1){
			  if($system[0]->half_deduct_month==2){
				  $ecommissions_amount = $sl_commissions->commission_amount/2;
			  } else {
				  $ecommissions_amount = $sl_commissions->commission_amount;
			  }
			  $commissions_amount += $ecommissions_amount;
		  } else {
			  //$ecommissions_amount = $sl_commissions->commission_amount;
			  if($sl_commissions->is_commission_taxable == 1) {
				  if($sl_commissions->amount_option == 0) {
					  $ecommissions_amount = $sl_commissions->commission_amount;
				  } else {
					  $ecommissions_amount = $basic_salary / 100 * $sl_commissions->commission_amount;
				  }
				 $commissions_amount -= $ecommissions_amount; 
			  } else if($sl_commissions->is_commission_taxable == 2) {
				  if($sl_commissions->amount_option == 0) {
					  $ecommissions_amount = $sl_commissions->commission_amount / 2;
				  } else {
					  $ecommissions_amount = ($basic_salary / 100) / 2 * $sl_commissions->commission_amount;
				  }
				 $commissions_amount -= $ecommissions_amount; 
			  } else {
				  if($sl_commissions->amount_option == 0) {
					  $ecommissions_amount = $sl_commissions->commission_amount;
				  } else {
					  $ecommissions_amount = $basic_salary / 100 * $sl_commissions->commission_amount;
				  }
				  $commissions_amount += $ecommissions_amount;
			  }
		  }
		  //$commissions_amount += $ecommissions_amount;
		  //$commissions_amount += $sl_commissions->commission_amount;
	}
endif;
// 6: statutory deductions
$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
$statutory_deductions_amount = 0;
if(!is_null($statutory_deductions)):
	$statutory_deductions_amount = 0;
	foreach($statutory_deductions->result() as $sl_statutory_deductions) {
		if($system[0]->is_half_monthly==1){
			 if($system[0]->half_deduct_month==2){
				$single_sd = $sl_statutory_deductions->deduction_amount/2;
			 } else {
				$single_sd = $sl_statutory_deductions->deduction_amount;
			 }
			 $statutory_deductions_amount += $single_sd;
		  } else {
			  //$single_sd = $sl_statutory_deductions->deduction_amount;
			 if($sl_statutory_deductions->statutory_options == 0) {
				  $single_sd = $sl_statutory_deductions->deduction_amount;
			  } else {
				  $single_sd = $basic_salary / 100 * $sl_statutory_deductions->deduction_amount;
			  }
			 $statutory_deductions_amount += $single_sd; 
		  }
		//$statutory_deductions_amount += $single_sd;
	}
endif;

// 7: overtime
$salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
$count_overtime = $this->Employees_model->count_employee_overtime($user_id);
$overtime_amount = 0;
if($count_overtime > 0) {
	foreach($salary_overtime as $sl_overtime){
		if($system[0]->is_half_monthly==1){
			if($system[0]->half_deduct_month==2){
				$eovertime_hours = $sl_overtime->overtime_hours/2;
				$eovertime_rate = $sl_overtime->overtime_rate/2;
			} else {
				$eovertime_hours = $sl_overtime->overtime_hours;
				$eovertime_rate = $sl_overtime->overtime_rate;
			}
		} else {
			$eovertime_hours = $sl_overtime->overtime_hours;
			$eovertime_rate = $sl_overtime->overtime_rate;
		}
		$overtime_amount += $eovertime_hours * $eovertime_rate;
		//$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
		//$overtime_amount += $overtime_total;
	}
} else {
	$overtime_amount = 0;
}

// saudi gosi
if($system[0]->enable_saudi_gosi != 0){
	$gois_amn = $basic_salary + $allowance_amount;
	$enable_saudi_gosi = $gois_amn / 100 * $system[0]->enable_saudi_gosi;
	$saudi_gosi = $enable_saudi_gosi;
} else {
	$saudi_gosi = 0;
}
// add amount
$add_salary = $allowance_amount + $basic_salary + $overtime_amount + $all_other_payment + $commissions_amount + $saudi_gosi;
// add amount
$net_salary_default = $add_salary - $loan_de_amount - $statutory_deductions_amount;
$sta_salary = $allowance_amount + $basic_salary;

$estatutory_deductions = $statutory_deductions_amount;
// net salary + statutory deductions
$net_salary = $net_salary_default;
$net_salary = number_format((float)$net_salary, 2, '.', '');
// check
$half_title = '1';
if($system[0]->is_half_monthly==1){
	$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($user_id,$this->input->get('pay_date'));
	if($payment_check->num_rows() > 1) {
		$half_title = '';
	} else if($payment_check->num_rows() > 0){
		$half_title = '('.$this->lang->line('xin_title_second_half').')';
	} else {
		$half_title = '('.$this->lang->line('xin_title_first_half').')';
	}
	$half_title = $half_title;
} else {
	$half_title = '';
}
// get advance salary
$advance_salary = $this->Payroll_model->advance_salary_by_employee_id($user_id);
$emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($user_id);

if(!is_null($advance_salary)){
	$monthly_installment = $advance_salary[0]->monthly_installment;
	$advance_amount = $advance_salary[0]->advance_amount;
	$total_paid = $advance_salary[0]->total_paid;
	//check ifpaid
	$em_advance_amount = $advance_salary[0]->advance_amount;
	$em_total_paid = $advance_salary[0]->total_paid;
	
	if($em_advance_amount > $em_total_paid){
		if($monthly_installment=='' || $monthly_installment==0) {
			
			$ntotal_paid = $emp_value[0]->total_paid;
			$nadvance = $emp_value[0]->advance_amount;
			$total_net_salary = $nadvance - $ntotal_paid;
			$pay_amount = $net_salary - $total_net_salary;
			$advance_amount = $total_net_salary;
		} else {
			//
			$re_amount = $em_advance_amount - $em_total_paid;
			if($monthly_installment > $re_amount){
				$advance_amount = $re_amount;
				$total_net_salary = $net_salary - $re_amount;
				$pay_amount = $net_salary - $re_amount;
			} else {
				$advance_amount = $monthly_installment;
				$total_net_salary = $net_salary - $monthly_installment;
				$pay_amount = $net_salary - $monthly_installment;
			}
		}
		
	} else {
		$total_net_salary = $net_salary - 0;
		$pay_amount = $net_salary - 0;
		$advance_amount = 0;
	}
} else {
	$pay_amount = $net_salary - 0;
	$total_net_salary = $net_salary - 0;	
	$advance_amount = 0;
}
// $net_salary = $net_salary - $advance_amount;
?>

<!-- PT code from here -->



<?php



$pf_pay+=$basic_salary;
if($allow==0){
  $pf_pay=0;
}
$salary = $basic_salary;

// Maharashtra, Rajasthan, Gujarat, Odisha, Andhra Pradesh, Karnataka, Uttar Pradesh, 
function pt($state,$salary,$gender,$des){
	$d_name=strtolower($des);
//$salary=(int)$sal;
$result=[];
//echo $state."<br>";
//echo $sal."<br>";
//echo $salary."<br>";
//echo $gender."<br>";

 if(strcmp($state,"Rajasthan")==0){
 	array_push($result,"0");
 	array_push($result,"0");
 }
 
  if(strcmp($state,"Uttar Pradesh")==0){
 	array_push($result,"0");
 	array_push($result,"0");
 }
 
 if(strcmp($state,"Maharashtra")==0 || strcmp($state,"Maharshtra")==0 ){
	if($salary<=10000 && $gender=="Female"){
		array_push($result,"0");
		array_push($result,"0");
   }
	if($salary<=7500 && $gender=="Male"){
		array_push($result,"0");
		array_push($result,"0");
   }
   if($salary>7500 && $salary<=10000 && $gender=="Male"){
		array_push($result,"175");
		array_push($result,"175");
   }
   if($salary>10000){
		array_push($result,"200");
		array_push($result,"300");
   }
   if(strpos($d_name,"supervisor") !='true' &&(date('m')==6 || date('m')==12)){
	   array_push($result,"12");
   }
   else{
	   array_push($result,"0");
   }
}
 
if(strcmp($state,"Odisha")==0){
	if($salary<=13304){
		array_push($result,"0");
		array_push($result,"0");
   }
   if($salary>13304 && $salary<=25000){
		array_push($result,"125");
		array_push($result,"125");
   }
   if($salary>25000){
		array_push($result,"200");
		array_push($result,"300");
   }
	if(strpos($d_name,"supervisor") !='true' &&(date('m')==6 || date('m')==12)){
	   array_push($result,"20");
   }
   else{
	   array_push($result,"0");
   }
}
 
if(strcmp($state,"Gujarat")==0){
 
	if($salary<=5999){
		array_push($result,"0");
		array_push($result,"0");
   }
   if($salary>5999 && $salary<=8999){
		array_push($result,"80");
		array_push($result,"80");
   }
   if($salary>8999 && $salary<=11999){
		array_push($result,"150");
		array_push($result,"150");
   }
   if($salary>=12000){
		array_push($result,"200");
		array_push($result,"200");
   }
	if(strpos($d_name,"supervisor") !='true' &&(date('m')==6 || date('m')==12)){
	   array_push($result,"6");
   }
   else{
	   array_push($result,"0");
   }
}
 
if(strcmp($state,"Andhra Pradesh")==0){
	if($salary<=15000){
		array_push($result,"0");
		array_push($result,"0");
   }
   if($salary>15000 && $salary<=20000){
		array_push($result,"150");
		array_push($result,"150");
   }
   if($salary>20000){
		array_push($result,"200");
		array_push($result,"200");
   }
	if(strpos($d_name,"supervisor") !='true' &&(date('m')==12)){
	   array_push($result,"30");
   }
   else{
	   array_push($result,"0");
   }
}
 
if(strcmp($state,"Karnataka")==0){
	if($salary<15000){
		array_push($result,"0");
		array_push($result,"0");
   }
   if($salary>=15000){
		array_push($result,"200");
		array_push($result,"200");
   }
	if(date('m')==12){
	   array_push($result,"20");
   }
   else{
	   array_push($result,"0");
   }
}
 
 return $result;
}

$gross=$basic_salary + $allowance_amount;

$list=pt($state,$gross,$gender,$designation);

$current_month=date('m');
$pay_month=0;
$pt_month=0;
//echo $current_date;
//echo "shu";
if($current_month==1){
$pay_month=12;
}
else{
  $pay_month=$current_month-1;
}
if($pay_month==3){
  $pt_month=$list[1];
}
else{
  $pt_month=$list[0];
}
//print_r($pay_month);

$pf_de=0;

$total_deduction=0;

if($pf_pay>15000){
  $pf_de=1800.00;
}
else{
  $pf_de=round($pf_pay*0.12);
//$total_deduction=$pt_month+$pf_de+ceil($gross*0.0075);
}

function income_tax_new($salary){
	$salary*=12;
	$result=0;
   // print_r("Salary = ".$salary);
	//echo "<br>";
	
	//if($salary>250000 && $salary<=500000){
	  //  $result+=($salary-250000)*0.05;
	//}
	
	if($salary>500000 && $salary<=750000){
		$result+=(($salary-500000)*0.1)+12500;
	}
	
	if($salary>750000 && $salary<=1000000){
		$result+=(($salary-750000)*0.15)+12500+25000;
	}
	
	if($salary>1000000 && $salary<=1250000){
		$result+=(($salary-1000000)*0.2)+12500+25000+37500;
	}
	
	if($salary>1250000 && $salary<=1500000){
		$result+=(($salary-1250000)*0.25)+12500+25000+37500+50000;
	}
	
	if($salary>1500000){
		$result+=(($salary-1500000)*0.3)+12500+25000+37500+50000+62500;
	}
	
		return $result+=$result*0.04;
	}
  
	function income_tax_old($salary){
	  $salary*=12;
	  $result=0;
	//   print_r("Salary = ".$salary);
	//   echo "<br>";
	  
	  //if($salary>250000 && $salary<=500000){
		//  $result+=($salary-250000)*0.05;
	  //}
	  
	  if($salary>500000 && $salary<=750000){
		  $result+=(($salary-500000)*0.2)+12500;
	  }
	  
	  if($salary>750000 && $salary<=1000000){
		  $result+=(($salary-750000)*0.2)+12500+50000;
	  }
	  
	  if($salary>1000000 && $salary<=1250000){
		  $result+=(($salary-1000000)*0.3)+12500+50000+50000;
	  }
	  
	  if($salary>1250000 && $salary<=1500000){
		  $result+=(($salary-1250000)*0.3)+12500+50000+50000+75000;
	  }
	  
	  if($salary>1500000){
		  $result+=(($salary-1500000)*0.3)+12500+50000+50000+75000+75000;
	  }
	  
		  return $result+=$result*0.04;
	  }
	  
	
	//echo "shu";
	  $tdsstr="";
	if($tds_type==1){
	  $tax=round(income_tax_new($gross))/12;
	  $tdsstr="TDS(new):";
	}
	else{
	  $tax=round(income_tax_old($gross))/12;
	  $tdsstr="TDS(old):";
	}
 // print_r("Tax yearly= ".$tax);
 // echo "<br>";
 // print_r("Tax monthly= ".$tax/12);


if ($gross < 21000) {
    $esic_deduc = ceil($gross * 0.0075);
    } else {
        $esic_deduc = 0;
        }

// print_r($is_esic);
if($is_esic==0){
	$esic_deduc=0;
}
$total_deduction=$pt_month+$pf_de+ $esic_deduc + $tax + $list[2];
$net_pay = $gross-$total_deduction;
// print_r($gross);
//print_r($);
// print_r($total_deduction);
$net_salary = $gross-$total_deduction;
$estatutory_deductions=$total_deduction;
$salary_total= $allowance_amount+$basic_salary+$overtime_amount;

?>
<?php
//echo $user_id;
$salary_allowances = $this->Employees_model->read_salary_allowances($user_id);
$total=0;
foreach($salary_allowances as $result){
	$total+=$result->allowance_amount;
}
$overall= $total+$total_basic;
?>


<!-- PT code end from here -->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><strong><?php echo $this->lang->line('xin_payment_for');?></strong> <?php echo $half_title;?> <?php echo $p_month;?></h4>
</div>
<div class="modal-body" style="overflow:auto; height:530px;">
<?php $attributes = array('name' => 'pay_monthly', 'id' => 'pay_monthly', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'ADD');?>
<?php echo form_open('admin/payroll/add_pay_monthly/', $attributes, $hidden);?>
   <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
          <input type="hidden" name="designation_id" value="<?php echo $designation_id;?>" />
          <input type="hidden" name="company_id" value="<?php echo $company_id;?>" />
          <input type="hidden" name="location_id" value="<?php echo $location_id;?>" />
          <label for="name"><?php echo $this->lang->line('xin_payroll_basic_salary');?></label>
          <input type="text" readonly="readonly name="gross_salary" class="form-control" value="<?php echo $basic_salary;?>">
          <input type="hidden" id="emp_id" value="<?php echo $user_id?>" name="emp_id">
          <input type="hidden" value="<?php echo $user_id;?>" name="u_id">
          <input type="hidden" value="<?php echo $basic_salary;?>" name="basic_salary">
          <input type="hidden" value="<?php echo $pt_month;?>" name="pt_month">
		  <input type="hidden" value="<?php echo $pf_de;?>" name="pf_de">
		  <input type="hidden" value="<?php echo $esic_deduc;?>" name="esic">
		  <input type="hidden" value="<?php echo $tax ?>" name="tds">
		  <input type="hidden" value="<?php echo $allow_array['HRA']?>" name="HRA">
		  <input type="hidden" value="<?php echo $allow_array['DA']?>" name="DA">
		  <input type="hidden" value="<?php echo $allow_array['Conv. Allow']?>" name="Conv_Allow">
		  <input type="hidden" value="<?php echo $allow_array['Other Allow']?>" name="Other_Allow">
		  <input type="hidden" value="<?php echo $allow_array['LWW']?>" name="LWW">
		  <input type="hidden" value="<?php echo $list[2]?>" name="LWF">






		  <input type="hidden" value="<?php echo $wages_type;?>" name="wages_type">

          <input type="hidden" value="<?php echo $this->input->get('pay_date');?>" name="pay_date" id="pay_date">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance');?></label>
          <input type="text" name="allowance_amount" class="form-control" value="<?php echo $allowance_amount;?>" readonly="readonly">
        </div>
      </div>
	  
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_hr_commissions');?></label>
          <input type="text" name="total_commissions" class="form-control" value="<?php echo $commissions_amount;?>" readonly="readonly">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_total_loan');?></label>
          <input type="text" name="total_loan" class="form-control" value="<?php echo $loan_de_amount;?>" readonly="readonly">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime');?></label>
          <input type="text" name="total_overtime" class="form-control" value="<?php echo $overtime_amount;?>" readonly="readonly">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions');?></label>
          <input type="text" name="total_statutory_deductions" class="form-control" value="<?php echo $estatutory_deductions;?>" readonly="readonly">
        </div>
      </div>
	  <!-- <div class="col-md-4">
        <div class="form-group">
          <label for="name">LWF</label>
          <input type="text" name="LWF" class="form-control" value="<?php echo $list[2];?>" readonly="readonly">
        </div>
      </div> -->
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment');?></label>
          <input type="text" name="total_other_payments" class="form-control" value="<?php echo $all_other_payment;?>" readonly="readonly">
        </div>
      </div>
    </div>
    <div class="row">
     <?php if($system[0]->enable_saudi_gosi != 0){ ?>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_title_saudi_gosi');?></label>
          <input type="text" readonly="readonly" name="saudi_gosi_amount" class="form-control" value="<?php echo $saudi_gosi;?>">
          <input type="hidden" readonly="readonly" name="saudi_gosi_percent" value="<?php echo $system[0]->enable_saudi_gosi;?>">
        </div>
      </div>
      <?php } else {?>
      <input type="hidden" name="saudi_gosi_amount" value="0" />
      <input type="hidden" name="saudi_gosi_percent" value="0" />
      <?php } ?>
      <?php if($advance_amount!=0):?>
          <div class="col-md-4">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_advance_deducted_salary');?></label>
              <input type="text" class="form-control" name="advance_amount" value="<?php echo $advance_amount;?>" readonly>
            </div>
          </div>
        <?php else:?>  
        <input type="hidden" name="advance_amount" value="0" />
        <?php endif;?>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_net_salary');?></label>
          <input type="text" readonly="readonly" name="net_salary" class="form-control" value="<?php echo $overall;?>">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount');?></label>
          <input type="text" readonly="readonly" name="payment_amount" class="form-control" value="<?php echo $net_salary;?>">
        </div>
      </div>
    </div>   
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <span><strong>NOTE:</strong> <?php echo $this->lang->line('xin_payroll_total_allowance');?>,<?php echo $this->lang->line('xin_hr_commissions');?>,<?php echo $this->lang->line('xin_payroll_total_loan');?>,<?php echo $this->lang->line('xin_payroll_total_overtime');?>,<?php echo $this->lang->line('xin_employee_set_statutory_deductions');?>,<?php echo $this->lang->line('xin_employee_set_other_payment');?> are not editable.</span>
        </div>
      </div>
    </div> 
    <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> '.$this->lang->line('xin_pay'))); ?> </div>
  <?php echo form_close(); ?>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	// On page load: datatable					
	$("#pay_monthly").submit(function(e){
	
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		//$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					$('.emo_monthly_pay').modal('toggle');
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : "<?php echo site_url("admin/payroll/payslip_list") ?>?employee_id=0&company_id=<?php echo $company_id;?>&month_year=<?php echo $this->input->get('pay_date');?>",
							type : 'GET'
						},
						"fnDrawCallback": function(settings){
						$('[data-toggle="tooltip"]').tooltip();          
						}
					});
					xin_table.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='hourly_payment' && $_GET['type']=='fhourly_payment'){ ?>
<?php
$system = $this->Xin_model->read_setting_info(1);
$payment_month = strtotime($this->input->get('pay_date'));
$p_month = date('F Y',$payment_month);
$basic_salary = $basic_salary;
?>
<?php
$salary_allowances = $this->Employees_model->read_salary_allowances($user_id);
$count_allowances = $this->Employees_model->count_employee_allowances($user_id);
$allowance_amount = 0;
if($count_allowances > 0) {
	foreach($salary_allowances as $sl_allowances){
		if($sl_allowances->is_allowance_taxable == 1) {
		  if($sl_allowances->amount_option == 0) {
			  $iallowance_amount = $sl_allowances->allowance_amount;
		  } else {
			  $iallowance_amount = $basic_salary / 100 * $sl_allowances->allowance_amount;
		  }
		 $allowance_amount -= $iallowance_amount; 
	  } else if($sl_allowances->is_allowance_taxable == 2) {
		  if($sl_allowances->amount_option == 0) {
			  $iallowance_amount = $sl_allowances->allowance_amount / 2;
		  } else {
			  $iallowance_amount = ($basic_salary / 100) / 2 * $sl_allowances->allowance_amount;
		  }
		 $allowance_amount -= $iallowance_amount; 
	  } else {
		  if($sl_allowances->amount_option == 0) {
			  $iallowance_amount = $sl_allowances->allowance_amount;
		  } else {
			  $iallowance_amount = $basic_salary / 100 * $sl_allowances->allowance_amount;
		  }
		  $allowance_amount += $iallowance_amount;
	  }
		  //$allowance_amount += $sl_allowances->allowance_amount;
	}
} else {
	$allowance_amount = 0;
}
// 3: all loan/deductions
$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($user_id);
$count_loan_deduction = $this->Employees_model->count_employee_deductions($user_id);
$loan_de_amount = 0;
if($count_loan_deduction > 0) {
	foreach($salary_loan_deduction as $sl_salary_loan_deduction){
		$loan_de_amount += $sl_salary_loan_deduction->loan_deduction_amount;
	}
} else {
	$loan_de_amount = 0;
}
// 4: other payment
$other_payments = $this->Employees_model->set_employee_other_payments($user_id);
$other_payments_amount = 0;
if(!is_null($other_payments)):
	foreach($other_payments->result() as $sl_other_payments) {
		//$other_payments_amount += $sl_other_payments->payments_amount;
		if($sl_other_payments->is_otherpayment_taxable == 1) {
			  if($sl_other_payments->amount_option == 0) {
				  $epayments_amount = $sl_other_payments->payments_amount;
			  } else {
				  $epayments_amount = $basic_salary / 100 * $sl_other_payments->payments_amount;
			  }
			 $other_payments_amount -= $epayments_amount; 
		  } else if($sl_other_payments->is_otherpayment_taxable == 2) {
			  if($sl_other_payments->amount_option == 0) {
				  $epayments_amount = $sl_other_payments->payments_amount / 2;
			  } else {
				  $epayments_amount = ($basic_salary / 100) / 2 * $sl_other_payments->payments_amount;
			  }
			 $other_payments_amount -= $epayments_amount; 
		  } else {
			  if($sl_other_payments->amount_option == 0) {
				  $epayments_amount = $sl_other_payments->payments_amount;
			  } else {
				  $epayments_amount = $basic_salary / 100 * $sl_other_payments->payments_amount;
			  }
			  $other_payments_amount += $epayments_amount;
		  }
	}
endif;
// all other payment
$all_other_payment = $other_payments_amount;
// 5: commissions
$commissions = $this->Employees_model->set_employee_commissions($user_id);
if(!is_null($commissions)):
	$commissions_amount = 0;
	foreach($commissions->result() as $sl_commissions) {
		//$commissions_amount += $sl_commissions->commission_amount;
		if($sl_commissions->is_commission_taxable == 1) {
			  if($sl_commissions->amount_option == 0) {
				  $ecommissions_amount = $sl_commissions->commission_amount;
			  } else {
				  $ecommissions_amount = $basic_salary / 100 * $sl_commissions->commission_amount;
			  }
			 $commissions_amount -= $ecommissions_amount; 
		  } else if($sl_commissions->is_commission_taxable == 2) {
			  if($sl_commissions->amount_option == 0) {
				  $ecommissions_amount = $sl_commissions->commission_amount / 2;
			  } else {
				  $ecommissions_amount = ($basic_salary / 100) / 2 * $sl_commissions->commission_amount;
			  }
			 $commissions_amount -= $ecommissions_amount; 
		  } else {
			  if($sl_commissions->amount_option == 0) {
				  $ecommissions_amount = $sl_commissions->commission_amount;
			  } else {
				  $ecommissions_amount = $basic_salary / 100 * $sl_commissions->commission_amount;
			  }
			  $commissions_amount += $ecommissions_amount;
		  }
	}
endif;
// 6: statutory deductions
$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id);
if(!is_null($statutory_deductions)):
	$statutory_deductions_amount = 0;
	foreach($statutory_deductions->result() as $sl_statutory_deductions) {
		/*if($system[0]->statutory_fixed!='yes'):
			$sta_salary = $basic_salary;
			$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
			$statutory_deductions_amount += $st_amount;
		else:
			$statutory_deductions_amount += $sl_statutory_deductions->deduction_amount;
		endif;*/
		if($sl_statutory_deductions->statutory_options == 0) {
			  $single_sd = $sl_statutory_deductions->deduction_amount;
		  } else {
			  $single_sd = $basic_salary / 100 * $sl_statutory_deductions->deduction_amount;
		  }
		 $statutory_deductions_amount += $single_sd; 
	}
endif;

// 7: overtime
$salary_overtime = $this->Employees_model->read_salary_overtime($user_id);
$count_overtime = $this->Employees_model->count_employee_overtime($user_id);
$overtime_amount = 0;
if($count_overtime > 0) {
	foreach($salary_overtime as $sl_overtime){
		$overtime_total = $sl_overtime->overtime_hours * $sl_overtime->overtime_rate;
		$overtime_amount += $overtime_total;
	}
} else {
	$overtime_amount = 0;
}

//overtime request
$overtime_count = $this->Overtime_request_model->get_overtime_request_count($euser_id,$this->input->get('pay_date'));
$re_hrs_old_int1 = 0;
$re_hrs_old_seconds =0;
$re_pcount = 0;
foreach ($overtime_count as $overtime_hr){
	// total work			
	$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
	$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
	$re_interval_late = $request_clock_in->diff($request_clock_out);
	$re_hours_r  = $re_interval_late->format('%h');
	$re_minutes_r = $re_interval_late->format('%i');			
	$re_total_time = $re_hours_r .":".$re_minutes_r.":".'00';
	
	$re_str_time = $re_total_time;

	$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
	sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
	$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
	$re_hrs_old_int1 += $re_hrs_old_seconds;
	
	$re_pcount = gmdate("H", $re_hrs_old_int1);			
}
// saudi gosi
if($system[0]->enable_saudi_gosi != 0){
	$gois_amn = $basic_salary + $allowance_amount;
	$enable_saudi_gosi = $gois_amn / 100 * $system[0]->enable_saudi_gosi;
	$saudi_gosi = $enable_saudi_gosi;
} else {
	$saudi_gosi = 0;
}
// add amount
$add_salary = $allowance_amount + $overtime_amount + $all_other_payment + $commissions_amount + $saudi_gosi;
// add amount
$net_salary_default = $add_salary - $loan_de_amount - $statutory_deductions_amount;
$sta_salary = $allowance_amount + $basic_salary;

$estatutory_deductions = $statutory_deductions_amount;
// net salary + statutory deductions
$pay_date = $_GET['pay_date'];
$result = $this->Payroll_model->total_hours_worked($euser_id,$pay_date);
$hrs_old_int1 = 0;
$pcount = 0;
$Trest = 0;
$total_time_rs = 0;
$hrs_old_int_res1 = 0;
foreach ($result->result() as $hour_work){
	// total work			
	$clock_in =  new DateTime($hour_work->clock_in);
	$clock_out =  new DateTime($hour_work->clock_out);
	$interval_late = $clock_in->diff($clock_out);
	$hours_r  = $interval_late->format('%h');
	$minutes_r = $interval_late->format('%i');			
	$total_time = $hours_r .":".$minutes_r.":".'00';
	
	$str_time = $total_time;

	$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
	$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
	$hrs_old_int1 += $hrs_old_seconds;
	
	$pcount = gmdate("H", $hrs_old_int1);			
}
$pcount = $pcount + $re_pcount;
if($pcount > 0){
	$total_count = $pcount * $basic_salary;
	$fsalary = $total_count + $net_salary_default;
} else {
	$fsalary = $pcount;
}
$net_salary = $fsalary;
$net_salary = number_format((float)$net_salary, 2, '.', '');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><strong><?php echo $this->lang->line('xin_payment_for');?></strong> <?php echo $p_month;?></h4>
</div>
<div class="modal-body" style="overflow:auto; height:530px;">
<?php $attributes = array('name' => 'pay_hourly', 'id' => 'pay_hourly', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'ADD');?>
<?php echo form_open('admin/payroll/add_pay_hourly/', $attributes, $hidden);?>
   <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
          <input type="hidden" name="designation_id" value="<?php echo $designation_id;?>" />
          <input type="hidden" name="company_id" value="<?php echo $company_id;?>" />
          <input type="hidden" name="location_id" value="<?php echo $location_id;?>" />
          <label for="name"><?php echo $this->lang->line('xin_payroll_hourly_rate');?></label>
          <input type="text" readonly="readonly" name="gross_salary" class="form-control" value="<?php echo $basic_salary;?>">
          <input type="hidden" id="emp_id" value="<?php echo $user_id?>" name="emp_id">
          <input type="hidden" value="<?php echo $user_id;?>" name="u_id">
          <input type="hidden" value="<?php echo $basic_salary;?>" name="basic_salary">
          <input type="hidden" value="<?php echo $wages_type;?>" name="wages_type">
          <input type="hidden" value="<?php echo $this->input->get('pay_date');?>" name="pay_date" id="pay_date">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
         <label for="name"><?php echo $this->lang->line('xin_payroll_hours_worked_total');?></label>
         <input type="text" readonly="readonly" name="hours_worked" class="form-control" value="<?php echo $pcount;?>">
        </div>
      </div>
    </div>
   <?php
	
	?>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_total_allowance');?></label>
          <input type="text" name="total_allowances" class="form-control" value="<?php echo $allowance_amount;?>" readonly="readonly">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_hr_commissions');?></label>
          <input type="text" name="total_commissions" class="form-control" value="<?php echo $commissions_amount;?>" readonly="readonly">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_total_loan');?></label>
          <input type="text" name="total_loan" class="form-control" value="<?php echo $loan_de_amount;?>" readonly="readonly">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_total_overtime');?></label>
          <input type="text" name="total_overtime" class="form-control" value="<?php echo $overtime_amount;?>" readonly="readonly">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_employee_set_statutory_deductions');?></label>
          <input type="text" name="total_statutory_deductions" class="form-control" value="<?php echo $estatutory_deductions;?>" readonly="readonly">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_employee_set_other_payment');?></label>
          <input type="text" name="total_other_payments" class="form-control" value="<?php echo $all_other_payment;?>" readonly="readonly">
        </div>
      </div>
    </div>
    <div class="row">
    <?php if($system[0]->enable_saudi_gosi != 0){ ?>
      <div class="col-md-4">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_title_saudi_gosi');?></label>
          <input type="text" readonly="readonly" name="saudi_gosi_amount" class="form-control" value="<?php echo $saudi_gosi;?>">
          <input type="hidden" readonly="readonly" name="saudi_gosi_percent" value="<?php echo $system[0]->enable_saudi_gosi;?>">
        </div>
      </div>
      <?php } else {?>
      <input type="hidden" name="saudi_gosi_amount" value="0" />
      <input type="hidden" name="saudi_gosi_percent" value="0" />
      <?php } ?>
      <div class="col-md-6">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_net_salary');?></label>
          <input type="text" readonly="readonly" name="net_salary" class="form-control" value="<?php echo $net_salary;?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount');?></label>
          <input type="text" readonly="readonly" name="payment_amount" class="form-control" value="<?php echo $net_salary;?>">
        </div>
      </div>
    </div>   
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <span><strong>NOTE:</strong> <?php echo $this->lang->line('xin_payroll_total_allowance');?>,<?php echo $this->lang->line('xin_hr_commissions');?>,<?php echo $this->lang->line('xin_payroll_total_loan');?>,<?php echo $this->lang->line('xin_payroll_total_overtime');?>,<?php echo $this->lang->line('xin_employee_set_statutory_deductions');?>,<?php echo $this->lang->line('xin_employee_set_other_payment');?> are not editable.</span>
        </div>
      </div>
    </div> 
    <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> '.$this->lang->line('xin_pay'))); ?> </div>
  <?php echo form_close(); ?>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	// On page load: datatable					
	$("#pay_hourly").submit(function(e){
	
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		//$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=11&data=hourly&add_type=add_pay_hourly&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					$('.emo_hourly_pay').modal('toggle');
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : "<?php echo site_url("admin/payroll/payslip_list") ?>?employee_id=0&company_id=<?php echo $company_id;?>&month_year=<?php echo $this->input->get('pay_date');?>",
							type : 'GET'
						},
						"fnDrawCallback": function(settings){
						$('[data-toggle="tooltip"]').tooltip();          
						}
					});
					xin_table.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php }?>

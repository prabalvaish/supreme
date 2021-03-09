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
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payroll_approve' && $_GET['type'] == 'payroll_approve') { ?>
  <div class="modal-header animated fadeInRight">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data">Approve Payroll</h4>
  </div>
  <div class="modal-body animated fadeInRight">
    Testt...
  </div>
<?php }
if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'payroll_template' && $_GET['type'] == 'payroll_template') { ?>
  <?php
  $system = $this->Xin_model->read_setting_info(1);
  $salary_allowances = $this->Employees_model->read_salary_allowances($employee_id);
  $count_allowances = $this->Employees_model->count_employee_allowances($employee_id);
  $details = $this->Employees_model->sallary_emp_detail($employee_id);
  $get_userid = $this->Employees_model->userid($employee_id);
  foreach ($get_userid as $userId) {
    $user = $userId->user_id;
    $allow = $userId->pf_allow;
  }
  $first_day= date('Y-m-01');
  $last_day  = date('Y-m-t');
  $total_days = $this->Employees_model->total_days($user,$first_day,$last_day);
  foreach ($details as $detail) {
    $gender = $detail->gender;
    $state = $detail->department_name;
    $location = $detail->location_id;
    $designation = $detail->designation_name;
    $tds_type = $detail->tds_type;
  }
  // echo $designation;
  $esic = $this->Employees_model->getEsic($location);
  foreach ($esic as $result) {
    $is_esic = $result->is_esic;
  }
  // echo $tds_type;
  // echo  $is_esic;
  foreach ($total_days as $present) {
    $present_days = $present->Total;
  }

  
  $salary_type = $this->Employees_model->salary_type($location);
foreach($salary_type as $result){
  $employee_salary_type=$result->salary_type;
}
// echo $employee_salary_type;

  $pf_pay = 0;
  $allowance_amount = 0;
  $eps=0;
  $epf=0;
  $edli=0;
  $admin_charge=0;
  if ($count_allowances > 0) {
    foreach ($salary_allowances as $sl_allowances) {
      $allowance_amount += $sl_allowances->allowance_amount;
    }
  } else {
    $allowance_amount = 0;
  }
  $sta_salary = $allowance_amount + $basic_salary;
  ?>
  <?php
  if ($profile_picture != '' && $profile_picture != 'no file') {
    $u_file = 'uploads/profile/' . $profile_picture;
  } else {
    if ($gender == 'Male') {
      $u_file = 'uploads/profile/default_male.jpg';
    } else {
      $u_file = 'uploads/profile/default_female.jpg';
    }
  }
  ?>
  <?php
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
    ?>
  <div class="modal-body">
    <h4 class="text-center font-weight-bol"><?php echo $this->lang->line('xin_payroll_employee_salary_details'); ?></h4>
    <div class="container-m-nx container-m-ny ml-1">
      <div class="media col-md-12 col-lg-8 col-xl-12 py-5 mx-auto">
        <img src="<?php echo base_url() . $u_file; ?>" alt="<?php echo $first_name . ' ' . $last_name; ?>" class="d-block ui-w-100 rounded-circle">
        <div class="media-body ml-3">
          <h4 class="font-weight-bold mb-1"><?php echo $first_name . ' ' . $last_name; ?></h4>
          <div class="text-muted mb-4">
            <?php echo $designation_name; ?>
          </div>
          <a href="javascript:void(0)" class="d-inline-block text-body">
            <strong><?php echo $this->lang->line('xin_emp_id'); ?>: &nbsp;<span class="pull-right"><?php echo $employee_id; ?></span></strong>
          </a>
          <a href="javascript:void(0)" class="d-inline-block text-body">
            <strong><?php echo $this->lang->line('xin_joining_date'); ?>: &nbsp;<span class="pull-right"><?php echo $date_of_joining; ?></span></strong>
          </a>
        </div>
      </div>
    </div>
    <div class="row mb-1">
      <div class="col-sm-12 col-xs-12 col-xl-12">
        <div class="card-header text-uppercase"><b><?php echo $this->lang->line('xin_payroll_salary_details'); ?></b></div>
        <div class="card-block">
          <div id="accordion">
            <div class="card hrsale-payslip">
              <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#basic_salary" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_payroll_basic_salary'); ?></strong> </a> </div>
              <div id="basic_salary" class="collapse" data-parent="#accordion" style="">
                <div class="box-body ml-3 mr-3">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <?php
                    if ($system[0]->is_half_monthly == 1) {
                      //if($half_deduct_month==2){
                      $basic_salary = $basic_salary / 2;
                      //} else {
                      //$basic_salary = $basic_salary;
                      //}
                    } else {
                      $basic_salary = $basic_salary;
                    }

                    // shu
                    // make sure to bring dynamic data
                    // $present_days=$present_days;
                    $fraction = $present_days / $month_working_days;
                    $basic_salary = $basic_salary * $fraction;
                    // shu
                    ?>
                    <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                      <tbody>
                        <tr>
                          <td><strong><?php echo $this->lang->line('xin_payroll_basic_salary'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($basic_salary); ?></span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <?php $allowances = $this->Employees_model->set_employee_allowances($user_id);
            ?>
            <?php if (!is_null($allowances)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_allowances" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_allowances'); ?></strong> </a> </div>
                <div id="set_allowances" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php
                          $allowance_amount = 0;
                          foreach ($allowances->result() as $sl_allowances) { ?>
                            <?php
                            $tax_allowance = $sl_allowances->pf_option;

                            $pg_allowance_amount = $sl_allowances->allowance_amount;
                            if ($sl_allowances->amount_option == 0) {
                              $allowance_amount_opt = $this->lang->line('xin_title_tax_fixed');
                            } else {
                              $allowance_amount_opt = $this->lang->line('xin_title_tax_percent');
                            }
                            if ($sl_allowances->is_allowance_taxable == 0) {
                              $allowance_opt = $this->lang->line('xin_salary_allowance_non_taxable');
                            } else if ($sl_allowances->is_allowance_taxable == 1) {
                              $allowance_opt = $this->lang->line('xin_fully_taxable');
                            } else {
                              $allowance_opt = $this->lang->line('xin_partially_taxable');
                            }
                            if ($system[0]->is_half_monthly == 1) {
                              if ($system[0]->half_deduct_month == 2) {
                                $iallowance_amount = ($sl_allowances->allowance_amount / 2);
                              } else {

                                $iallowance_amount = ($sl_allowances->allowance_amount);
                              }
                              $allowance_amount += $iallowance_amount;
                            } else {
                              //$eallowance_amount = $sl_allowances->allowance_amount;
                              if ($sl_allowances->is_allowance_taxable == 1) {
                                if ($sl_allowances->amount_option == 0) {
                                  $iallowance_amount = $sl_allowances->allowance_amount;
                                } else {
                                  $iallowance_amount = $basic_salary / 100 * $sl_allowances->allowance_amount;
                                }
                                $allowance_amount -= $iallowance_amount;
                              } else if ($sl_allowances->is_allowance_taxable == 2) {
                                if ($sl_allowances->amount_option == 0) {
                                  $iallowance_amount = $sl_allowances->allowance_amount / 2;
                                } else {
                                  $iallowance_amount = ($basic_salary / 100) / 2 * $sl_allowances->allowance_amount;
                                }
                                $allowance_amount -= $iallowance_amount;
                              } else {
                                if ($sl_allowances->amount_option == 0) {
                                  // shu
                                  $iallowance_amount = ($sl_allowances->allowance_amount) * $fraction;
                                } else {
                                  $iallowance_amount = $basic_salary / 100 * ($sl_allowances->allowance_amount) * $fraction;
                                }
                                // shu
                                $allowance_amount += $iallowance_amount;
                              }
                            }
                            //  shu
                            if ($tax_allowance == 1) {
                              $pf_pay += $iallowance_amount;
                            }
                            // shu

                            // $allowance_amount += $eallowance_amount;
                            ?>
                            <tr>
                              <td><strong><?php echo $sl_allowances->allowance_title; ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($iallowance_amount); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($allowance_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <!-- <?php $commissions = $this->Employees_model->set_employee_commissions($user_id); ?>
          <?php if (!is_null($commissions)) : ?> -->
            <!-- <div class="card hrsale-payslip">
            <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_commissions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_hr_commissions'); ?></strong> </a> </div>
            <div id="set_commissions" class="collapse" data-parent="#accordion" style="">
              <div class="box-body ml-3 mr-3">
                <div class="table-responsive" data-pattern="priority-columns">
                  <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                    <tbody>
                      <?php $commissions_amount = 0;
                      foreach ($commissions->result() as $sl_commissions) { ?>
                      <?php
                        $pg_commissions_amount = $sl_commissions->commission_amount;
                        if ($system[0]->is_half_monthly == 1) {
                          if ($system[0]->half_deduct_month == 2) {
                            $ecommissions_amount = $sl_commissions->commission_amount / 2;
                          } else {
                            $ecommissions_amount = $sl_commissions->commission_amount;
                          }
                          $commissions_amount += $ecommissions_amount;
                        } else {
                          // $ecommissions_amount = $sl_commissions->commission_amount;
                          if ($sl_commissions->is_commission_taxable == 1) {
                            if ($sl_commissions->amount_option == 0) {
                              $ecommissions_amount = $sl_commissions->commission_amount;
                            } else {
                              $ecommissions_amount = $basic_salary / 100 * $sl_commissions->commission_amount;
                            }
                            $commissions_amount -= $ecommissions_amount;
                          } else if ($sl_commissions->is_commission_taxable == 2) {
                            if ($sl_commissions->amount_option == 0) {
                              $ecommissions_amount = $sl_commissions->commission_amount / 2;
                            } else {
                              $ecommissions_amount = ($basic_salary / 100) / 2 * $sl_commissions->commission_amount;
                            }
                            $commissions_amount -= $ecommissions_amount;
                          } else {
                            if ($sl_commissions->amount_option == 0) {
                              $ecommissions_amount = $sl_commissions->commission_amount;
                            } else {
                              $ecommissions_amount = $basic_salary / 100 * $sl_commissions->commission_amount;
                            }
                            $commissions_amount += $ecommissions_amount;
                          }
                        }
                        if ($sl_commissions->amount_option == 0) {
                          $commission_amount_opt = $this->lang->line('xin_title_tax_fixed');
                        } else {
                          $commission_amount_opt = $this->lang->line('xin_title_tax_percent');
                        }
                        if ($sl_commissions->is_commission_taxable == 0) {
                          $commission_opt = $this->lang->line('xin_salary_allowance_non_taxable');
                        } else if ($sl_commissions->is_commission_taxable == 1) {
                          $commission_opt = $this->lang->line('xin_fully_taxable');
                        } else {
                          $commission_opt = $this->lang->line('xin_partially_taxable');
                        }

                      ?>
					  <?php //$commissions_amount += $sl_commissions->commission_amount;
            ?>
                      <tr>
                        <td><strong><?php echo $sl_commissions->commission_title; ?> (<?php echo $commission_amount_opt; ?>) (<?php echo $commission_opt; ?>):</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($ecommissions_amount); ?></span></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($commissions_amount); ?></span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?> -->
            <?php $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id); ?>
            <?php if (!is_null($statutory_deductions)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#statutory_deductions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></strong> </a> </div>
                <div id="statutory_deductions" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>


                          <!-- PT code from here -->

                          <?php

                          $pf_pay += $basic_salary;
                          if ($allow == 0) {
                            $pf_pay = 0;
                          }
                          $salary = $basic_salary;
                          // Maharashtra, Rajasthan, Gujarat, Odisha, Andhra Pradesh, Karnataka, Uttar Pradesh, 
                          function pt($state, $salary, $gender, $des)
                          {
                            $d_name = strtolower($des);
                            //$salary=(int)$sal;
                            $result = [];
                            //echo $state."<br>";
                            //echo $sal."<br>";
                            //echo $salary."<br>";
                            //echo $gender."<br>";

                            if (strcmp($state, "Rajasthan") == 0) {
                              array_push($result, "0");
                              array_push($result, "0");
                              array_push($result, "0");
                            }

                            if (strcmp($state, "Uttar Pradesh") == 0) {
                              array_push($result, "0");
                              array_push($result, "0");
                              array_push($result, "0");
                            }

                            if (strcmp($state, "Maharashtra") == 0 || strcmp($state, "Maharshtra") == 0) {
                              if ($salary <= 10000 && $gender == "Female") {
                                array_push($result, "0");
                                array_push($result, "0");
                              }
                              if ($salary <= 7500 && $gender == "Male") {
                                array_push($result, "0");
                                array_push($result, "0");
                              }
                              if ($salary > 7500 && $salary <= 10000 && $gender == "Male") {
                                array_push($result, "175");
                                array_push($result, "175");
                              }
                              if ($salary > 10000) {
                                array_push($result, "200");
                                array_push($result, "300");
                              }
                              if (strpos($d_name, "supervisor") != 'true' && (date('m') == 6 || date('m') == 12)) {
                                array_push($result, "12");
                              } else {
                                array_push($result, "0");
                              }
                            }

                            if (strcmp($state, "Odisha") == 0) {
                              if ($salary <= 13304) {
                                array_push($result, "0");
                                array_push($result, "0");
                              }
                              if ($salary > 13304 && $salary <= 25000) {
                                array_push($result, "125");
                                array_push($result, "125");
                              }
                              if ($salary > 25000) {
                                array_push($result, "200");
                                array_push($result, "300");
                              }
                              if (strpos($d_name, "supervisor") != 'true' && (date('m') == 6 || date('m') == 12)) {
                                array_push($result, "20");
                              } else {
                                array_push($result, "0");
                              }
                            }

                            if (strcmp($state, "Gujarat") == 0) {

                              if ($salary <= 5999) {
                                array_push($result, "0");
                                array_push($result, "0");
                              }
                              if ($salary > 5999 && $salary <= 8999) {
                                array_push($result, "80");
                                array_push($result, "80");
                              }
                              if ($salary > 8999 && $salary <= 11999) {
                                array_push($result, "150");
                                array_push($result, "150");
                              }
                              if ($salary >= 12000) {
                                array_push($result, "200");
                                array_push($result, "200");
                              }
                              if (strpos($d_name, "supervisor") != 'true' && (date('m') == 6 || date('m') == 12)) {
                                array_push($result, "6");
                              } else {
                                array_push($result, "0");
                              }
                            }

                            if (strcmp($state, "Andhra Pradesh") == 0) {
                              if ($salary <= 15000) {
                                array_push($result, "0");
                                array_push($result, "0");
                              }
                              if ($salary > 15000 && $salary <= 20000) {
                                array_push($result, "150");
                                array_push($result, "150");
                              }
                              if ($salary > 20000) {
                                array_push($result, "200");
                                array_push($result, "200");
                              }
                              if (strpos($d_name, "supervisor") != 'true' && (date('m') == 12)) {
                                array_push($result, "30");
                              } else {
                                array_push($result, "0");
                              }
                            }

                            if (strcmp($state, "Karnataka") == 0) {
                              if ($salary < 15000) {
                                array_push($result, "0");
                                array_push($result, "0");
                              }
                              if ($salary >= 15000) {
                                array_push($result, "200");
                                array_push($result, "200");
                              }
                              if (date('m') == 12) {
                                array_push($result, "20");
                              } else {
                                array_push($result, "0");
                              }
                            }

                            return $result;
                          }



                          $gross = $basic_salary + $allowance_amount;

                          $list = pt($state, $gross, $gender, $designation_name);

                          $current_month = date('m');
                          $pay_month = 0;
                          $pt_month = 0;
                          //echo $current_date;
                          //echo "shu";
                          if ($current_month == 1) {
                            $pay_month = 12;
                          } else {
                            $pay_month = $current_month - 1;
                          }
                          if ($pay_month == 3) {
                            $pt_month = $list[1];
                          } else {
                            $pt_month = $list[0];
                          }
                          //print_r($pay_month);

                          $pf_de = 0;

                          $total_deduction = 0;

                          if ($pf_pay > 15000) {
                            $pf_de = 1800.00;
                            $epf=ceil(550.5);
                            $eps= ceil(1249.5);
                          } else {
                            $pf_de = round($pf_pay * 0.12);
                            $epf= round($pf_pay*0.0367);
                            $eps= round($pf_pay*0.0833);
                            
                            //$total_deduction=$pt_month+$pf_de+ceil($gross*0.0075);
                          }
                          $edli=round($pf_pay*0.005);
                          $admin_charge=round($pf_pay*0.005);
                            
                           //just printing 
                          //echo $pf_pay," ",$epf," ", $eps," ",$edli," ",$admin_charge;
                            
                          function income_tax_new($salary)
                          {
                            $salary *= 12;
                            $result = 0;
                            // print_r("Salary = ".$salary);
                            //echo "<br>";

                            //if($salary>250000 && $salary<=500000){
                            //  $result+=($salary-250000)*0.05;
                            //}

                            if ($salary > 500000 && $salary <= 750000) {
                              $result += (($salary - 500000) * 0.1) + 12500;
                            }

                            if ($salary > 750000 && $salary <= 1000000) {
                              $result += (($salary - 750000) * 0.15) + 12500 + 25000;
                            }

                            if ($salary > 1000000 && $salary <= 1250000) {
                              $result += (($salary - 1000000) * 0.2) + 12500 + 25000 + 37500;
                            }

                            if ($salary > 1250000 && $salary <= 1500000) {
                              $result += (($salary - 1250000) * 0.25) + 12500 + 25000 + 37500 + 50000;
                            }

                            if ($salary > 1500000) {
                              $result += (($salary - 1500000) * 0.3) + 12500 + 25000 + 37500 + 50000 + 62500;
                            }

                            return $result += $result * 0.04;
                          }

                          function income_tax_old($salary)
                          {
                            $salary *= 12;
                            $result = 0;
                            // print_r("Salary = ".$salary);
                            // echo "<br>";

                            //if($salary>250000 && $salary<=500000){
                            //  $result+=($salary-250000)*0.05;
                            //}

                            if ($salary > 500000 && $salary <= 750000) {
                              $result += (($salary - 500000) * 0.2) + 12500;
                            }

                            if ($salary > 750000 && $salary <= 1000000) {
                              $result += (($salary - 750000) * 0.2) + 12500 + 50000;
                            }

                            if ($salary > 1000000 && $salary <= 1250000) {
                              $result += (($salary - 1000000) * 0.3) + 12500 + 50000 + 50000;
                            }

                            if ($salary > 1250000 && $salary <= 1500000) {
                              $result += (($salary - 1250000) * 0.3) + 12500 + 50000 + 50000 + 75000;
                            }

                            if ($salary > 1500000) {
                              $result += (($salary - 1500000) * 0.3) + 12500 + 50000 + 50000 + 75000 + 75000;
                            }

                            return $result += $result * 0.04;
                          }


                          //echo "shu";
                          $tdsstr = "";
                          if ($tds_type == 1) {
                            $tax = round(income_tax_new($gross)) / 12;
                            $tdsstr = "TDS(new):";
                          } else {
                            $tax = round(income_tax_old($gross)) / 12;
                            $tdsstr = "TDS(old):";
                          }
                          // print_r("Tax yearly= ".$tax);
                          // echo "<br>";
                          // print_r("Tax monthly= ".$tax/12);

                          if ($gross < 21000) {
                            $esic_deduc = ceil($gross * 0.0075);
                          } else {
                            $esic_deduc = 0;
                          }
                          if ($is_esic == 0) {
                            $esic_deduc = 0;
                          }
                          $total_deduction = $pt_month + $pf_de + $esic_deduc + $tax + $list[2];
                          $net_pay = $gross - $total_deduction;
                          //print_r();

                          ?>


                          <!-- PT code end from here -->


                          <!-- <?php $statutory_deductions_amount = 0;
                                foreach ($statutory_deductions->result() as $sl_statutory_deductions) { ?>
                        <?php
                                  $sta_salary = $basic_salary;
                                  $st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
                                  if ($system[0]->is_half_monthly == 1) {
                                    if ($system[0]->half_deduct_month == 2) {
                                      $single_sd = $st_amount / 2;
                                    } else {
                                      $single_sd = $st_amount;
                                    }
                                    $statutory_deductions_amount += $single_sd;
                                  } else {

                                    if ($sl_statutory_deductions->statutory_options == 0) {
                                      $single_sd = $sl_statutory_deductions->deduction_amount;
                                    } else {
                                      $single_sd = $basic_salary / 100 * $sl_statutory_deductions->deduction_amount;
                                    }
                                    $statutory_deductions_amount += $single_sd;
                                  }
                                  if ($sl_statutory_deductions->statutory_options == 0) {
                                    $sd_amount_opt = $this->lang->line('xin_title_tax_fixed');
                                  } else {
                                    $sd_amount_opt = $this->lang->line('xin_title_tax_percent');
                                  }

                        ?>
                      <tr>
                        <td><strong><?php echo $sl_statutory_deductions->deduction_title; ?> (<?php echo $sd_amount_opt; ?>): </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($single_sd); ?></span></td>
                      </tr>
                      <?php }
                      ?> 

                      <tr>
                        <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($statutory_deductions_amount); ?></span></td>
                      </tr> -->

                          <!-- shu -->

                          <tr>
                            <td><strong><?php echo "PT:"; ?> </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($pt_month); ?></span></td>
                          </tr>
                          <tr>
                            <td><strong><?php echo "PF:"; ?> </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($pf_de); ?></span></td>
                          </tr>
                          <tr>
                            <td><strong><?php echo "ESI:"; ?> </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($esic_deduc); ?></span></td>
                          </tr>
                          <tr>
                            <td><strong><?php echo $tdsstr; ?> </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($tax); ?></span></td>
                          </tr>
                          <tr>
                            <td><strong><?php echo "LWF:"; ?> </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($list[2]); ?></span></td>
                          </tr>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($total_deduction); ?></span></td>
                          </tr>

                          <!-- shu -->

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php $other_payments = $this->Employees_model->set_employee_other_payments($user_id); ?>
            <?php if (!is_null($other_payments)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_other_payments" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></strong> </a> </div>
                <div id="set_other_payments" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $other_payments_amount = 0;
                          foreach ($other_payments->result() as $sl_other_payments) { ?>
                            <?php
                            if ($system[0]->is_half_monthly == 1) {
                              if ($system[0]->half_deduct_month == 2) {
                                $epayments_amount = $sl_other_payments->payments_amount / 2;
                              } else {
                                $epayments_amount = $sl_other_payments->payments_amount;
                              }
                              $other_payments_amount += $epayments_amount;
                            } else {
                              //$epayments_amount = $sl_other_payments->payments_amount;
                              if ($sl_other_payments->is_otherpayment_taxable == 1) {
                                if ($sl_other_payments->amount_option == 0) {
                                  $epayments_amount = $sl_other_payments->payments_amount;
                                } else {
                                  $epayments_amount = $basic_salary / 100 * $sl_other_payments->payments_amount;
                                }
                                $other_payments_amount -= $epayments_amount;
                              } else if ($sl_other_payments->is_otherpayment_taxable == 2) {
                                if ($sl_other_payments->amount_option == 0) {
                                  $epayments_amount = $sl_other_payments->payments_amount / 2;
                                } else {
                                  $epayments_amount = ($basic_salary / 100) / 2 * $sl_other_payments->payments_amount;
                                }
                                $other_payments_amount -= $epayments_amount;
                              } else {
                                if ($sl_other_payments->amount_option == 0) {
                                  $epayments_amount = $sl_other_payments->payments_amount;
                                } else {
                                  $epayments_amount = $basic_salary / 100 * $sl_other_payments->payments_amount;
                                }
                                $other_payments_amount += $epayments_amount;
                              }
                            }
                            if ($sl_other_payments->amount_option == 0) {
                              $other_amount_opt = $this->lang->line('xin_title_tax_fixed');
                            } else {
                              $other_amount_opt = $this->lang->line('xin_title_tax_percent');
                            }
                            if ($sl_other_payments->is_otherpayment_taxable == 0) {
                              $other_opt = $this->lang->line('xin_salary_allowance_non_taxable');
                            } else if ($sl_other_payments->is_otherpayment_taxable == 1) {
                              $other_opt = $this->lang->line('xin_fully_taxable');
                            } else {
                              $other_opt = $this->lang->line('xin_partially_taxable');
                            }
                            ?>
                            <tr>
                              <td><strong><?php echo $sl_other_payments->payments_title; ?> (<?php echo $other_amount_opt; ?>) (<?php echo $other_opt; ?>):</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($epayments_amount); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($other_payments_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <!-- <?php $loan = $this->Employees_model->set_employee_deductions($user_id); ?>
          <?php if (!is_null($loan)) : ?>
          <div class="card hrsale-payslip">
            <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_loan_deductions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_loan_deductions'); ?></strong> </a> </div>
            <div id="set_loan_deductions" class="collapse" data-parent="#accordion" style="">
              <div class="box-body ml-3 mr-3">
                <div class="table-responsive" data-pattern="priority-columns">
                  <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                    <tbody>
                      <?php $loan_de_amount = 0;
                      foreach ($loan->result() as $r_loan) { ?>
                      <?php
                        $pg_r_loan = $r_loan->loan_deduction_amount;
                        if ($system[0]->is_half_monthly == 1) {
                          if ($system[0]->half_deduct_month == 2) {
                            $er_loan = $r_loan->loan_deduction_amount / 2;
                          } else {
                            $er_loan = $r_loan->loan_deduction_amount;
                          }
                        } else {
                          $er_loan = $r_loan->loan_deduction_amount;
                        }
                        $loan_de_amount += $er_loan;
                      ?>
					  <?php //$loan_de_amount += $r_loan->loan_deduction_amount;
            ?>
                      <tr>
                        <td><strong><?php echo $r_loan->loan_deduction_title; ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($er_loan); ?></span></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($loan_de_amount); ?></span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?> -->


            <?php $overtime = $this->Employees_model->set_employee_overtime($user_id); ?>
            <?php if (!is_null($overtime)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#overtime" aria-expanded="false"> <strong><?php echo $this->lang->line('dashboard_overtime'); ?></strong> </a> </div>
                <div id="overtime" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive">
                      <table class="table table-bordered mb-0">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_title'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_no_of_days'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_hour'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_rate'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1;
                          $overtime_amount = 0;
                          foreach ($overtime->result() as $r_overtime) { ?>
                            <?php

                            if ($system[0]->is_half_monthly == 1) {
                              if ($system[0]->half_deduct_month == 2) {
                                $eovertime_hours = $r_overtime->overtime_hours / 2;
                                $eovertime_rate = $r_overtime->overtime_rate / 2;
                              } else {
                                $eovertime_hours = $r_overtime->overtime_hours;
                                $eovertime_rate = $r_overtime->overtime_rate;
                              }
                            } else {
                              $eovertime_hours = $r_overtime->overtime_hours;
                              $eovertime_rate = $r_overtime->overtime_rate;
                            }
                            //$other_payments_amount += $eovertime_total;
                            $overtime_amount += $eovertime_hours * $eovertime_rate;
                            ?>
                            <tr>
                              <th scope="row"><?php echo $i; ?></th>
                              <td><?php echo $r_overtime->overtime_type; ?></td>
                              <td><?php echo $r_overtime->no_of_days; ?></td>
                              <td><?php echo $eovertime_hours; ?></td>
                              <td><?php echo $eovertime_rate; ?></td>
                            </tr>
                          <?php $i++;
                          } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="4" align="right"><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong></td>
                            <td><?php echo $this->Xin_model->currency_sign($overtime_amount); ?></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <div class="card hrsale-payslip">
              <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" aria-expanded="false"> <strong>Total Salary</strong> </a><span class="pull-right"> <?php echo $this->Xin_model->currency_sign($net_pay); ?> </span></div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="modal-footer mt-1">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
  </div>
<?php } else if (isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data'] == 'hourly_payslip' && $_GET['type'] == 'read_hourly_payment') { ?>
  <?php
  $system = $this->Xin_model->read_setting_info(1);
  $salary_allowances = $this->Employees_model->read_salary_allowances($employee_id);
  $count_allowances = $this->Employees_model->count_employee_allowances($employee_id);
  $allowance_amount = 0;
  if ($count_allowances > 0) {
    foreach ($salary_allowances as $sl_allowances) {
      $allowance_amount += $sl_allowances->allowance_amount;
    }
  } else {
    $allowance_amount = 0;
  }
  $sta_salary = $allowance_amount + $basic_salary;
  ?>
  <?php
  if ($profile_picture != '' && $profile_picture != 'no file') {
    $u_file = 'uploads/profile/' . $profile_picture;
  } else {
    if ($gender == 'Male') {
      $u_file = 'uploads/profile/default_male.jpg';
    } else {
      $u_file = 'uploads/profile/default_female.jpg';
    }
  } ?>
  <div class="modal-body animated fadeInRight">
    <h4 class="text-center font-weight-bol"><?php echo $this->lang->line('xin_payroll_employee_salary_details'); ?></h4>
    <div class="container-m-nx container-m-ny ml-1">
      <div class="media col-md-12 col-lg-8 col-xl-12 py-5 mx-auto">
        <img src="<?php echo base_url() . $u_file; ?>" alt="<?php echo $first_name . ' ' . $last_name; ?>" class="d-block ui-w-100 rounded-circle">
        <div class="media-body ml-3">
          <h4 class="font-weight-bold mb-1"><?php echo $first_name . ' ' . $last_name; ?></h4>
          <div class="text-muted mb-4">
            <?php echo $designation_name; ?>
          </div>

          <a href="javascript:void(0)" class="d-inline-block text-body">
            <strong><?php echo $this->lang->line('xin_emp_id'); ?>: &nbsp;<span class="pull-right"><?php echo $employee_id; ?></span></strong>
          </a>
          <a href="javascript:void(0)" class="d-inline-block text-body">
            <strong><?php echo $this->lang->line('xin_joining_date'); ?>: &nbsp;<span class="pull-right"><?php echo $date_of_joining; ?></span></strong>
          </a>
        </div>
      </div>
    </div>
    <div class="row mb-1">
      <div class="col-sm-12 col-xs-12 col-xl-12">
        <div class="card-header text-uppercase"><b><?php echo $this->lang->line('xin_payroll_salary_details'); ?></b></div>
        <div class="card-block">
          <div id="accordion">
            <div class="card hrsale-payslip">
              <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#basic_salary" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_daily_wages'); ?></strong> </a> </div>
              <div id="basic_salary" class="collapse" data-parent="#accordion" style="">
                <div class="box-body ml-3 mr-3">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                      <tbody>
                        <tr>
                          <td><strong><?php echo $this->lang->line('xin_payroll_hourly_rate'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($basic_salary); ?></span></td>
                        </tr>
                        <?php
                        $pay_date = $_GET['pay_date'];
                        //overtime request
                        $overtime_count = $this->Overtime_request_model->get_overtime_request_count($euser_id, $pay_date);
                        $re_hrs_old_int1 = 0;
                        $re_hrs_old_seconds = 0;
                        $re_pcount = 0;
                        foreach ($overtime_count as $overtime_hr) {
                          // total work			
                          $request_clock_in =  new DateTime($overtime_hr->request_clock_in);
                          $request_clock_out =  new DateTime($overtime_hr->request_clock_out);
                          $re_interval_late = $request_clock_in->diff($request_clock_out);
                          $re_hours_r  = $re_interval_late->format('%h');
                          $re_minutes_r = $re_interval_late->format('%i');
                          $re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

                          $re_str_time = $re_total_time;

                          $re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

                          sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                          $re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                          $re_hrs_old_int1 += $re_hrs_old_seconds;

                          $re_pcount = gmdate("H", $re_hrs_old_int1);
                        }
                        $result = $this->Payroll_model->total_hours_worked($euser_id, $pay_date);
                        $hrs_old_int1 = 0;
                        $pcount = 0;
                        $Trest = 0;
                        $total_time_rs = 0;
                        $hrs_old_int_res1 = 0;
                        foreach ($result->result() as $hour_work) {
                          // total work			
                          $clock_in =  new DateTime($hour_work->clock_in);
                          $clock_out =  new DateTime($hour_work->clock_out);
                          $interval_late = $clock_in->diff($clock_out);
                          $hours_r  = $interval_late->format('%h');
                          $minutes_r = $interval_late->format('%i');
                          $total_time = $hours_r . ":" . $minutes_r . ":" . '00';

                          $str_time = $total_time;

                          $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

                          sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                          $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                          $hrs_old_int1 += $hrs_old_seconds;

                          $pcount = gmdate("H", $hrs_old_int1);
                        }
                        $pcount = $pcount + $re_pcount;
                        ?>
                        <tr>
                          <td><strong><?php echo $this->lang->line('xin_payroll_hours_worked_total'); ?>:</strong> <span class="pull-right"><?php echo $pcount; ?></span></td>
                        </tr>
                        <?php $total_count = $pcount * $basic_salary; ?>
                        <tr>
                          <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($total_count); ?></span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <?php $allowances = $this->Employees_model->set_employee_allowances($user_id); ?>
            <?php if (!is_null($allowances)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_allowances" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_allowances'); ?></strong> </a> </div>
                <div id="set_allowances" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $allowance_amount = 0;
                          foreach ($allowances->result() as $sl_allowances) { ?>
                            <?php $allowance_amount += $sl_allowances->allowance_amount; ?>
                            <tr>
                              <td><strong><?php echo $sl_allowances->allowance_title; ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($sl_allowances->allowance_amount); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($allowance_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <?php $commissions = $this->Employees_model->set_employee_commissions($user_id); ?>
            <?php if (!is_null($commissions)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_commissions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_hr_commissions'); ?></strong> </a> </div>
                <div id="set_commissions" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $commissions_amount = 0;
                          foreach ($commissions->result() as $sl_commissions) { ?>
                            <?php $commissions_amount += $sl_commissions->commission_amount; ?>
                            <tr>
                              <td><strong><?php echo $sl_commissions->commission_title; ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($sl_commissions->commission_amount); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($commissions_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <?php $loan = $this->Employees_model->set_employee_deductions($user_id); ?>
            <?php if (!is_null($loan)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_loan_deductions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_loan_deductions'); ?></strong> </a> </div>
                <div id="set_loan_deductions" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $loan_de_amount = 0;
                          foreach ($loan->result() as $r_loan) { ?>
                            <?php $loan_de_amount += $r_loan->loan_deduction_amount; ?>
                            <tr>
                              <td><strong><?php echo $r_loan->loan_deduction_title; ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($r_loan->loan_deduction_amount); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($loan_de_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <?php $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($user_id); ?>
            <?php if (!is_null($statutory_deductions)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#statutory_deductions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></strong> </a> </div>
                <div id="statutory_deductions" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $statutory_deductions_amount = 0;
                          foreach ($statutory_deductions->result() as $sl_statutory_deductions) { ?>
                            <?php
                            if ($system[0]->statutory_fixed != 'yes') :
                              $sta_salary = $basic_salary;
                              $st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
                              $statutory_deductions_amount += $st_amount;
                              $single_sd = $st_amount;
                            else :
                              $statutory_deductions_amount += $sl_statutory_deductions->deduction_amount;
                              $st_amount = $statutory_deductions_amount;
                              $single_sd = $sl_statutory_deductions->deduction_amount;
                            endif;
                            ?>
                            <tr>
                              <td><strong><?php echo $sl_statutory_deductions->deduction_title; ?>: </strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($single_sd); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($statutory_deductions_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php $other_payments = $this->Employees_model->set_employee_other_payments($user_id); ?>
            <?php if (!is_null($other_payments)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_other_payments" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_other_payment'); ?></strong> </a> </div>
                <div id="set_other_payments" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body ml-3 mr-3">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $other_payments_amount = 0;
                          foreach ($other_payments->result() as $sl_other_payments) { ?>
                            <?php $other_payments_amount += $sl_other_payments->payments_amount; ?>
                            <tr>
                              <td><strong><?php echo $sl_other_payments->payments_title; ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($sl_other_payments->payments_amount); ?></span></td>
                            </tr>
                          <?php } ?>
                          <tr>
                            <td><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($other_payments_amount); ?></span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php $overtime = $this->Employees_model->set_employee_overtime($user_id); ?>
            <?php if (!is_null($overtime)) : ?>
              <div class="card hrsale-payslip">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#overtime" aria-expanded="false"> <strong><?php echo $this->lang->line('dashboard_overtime'); ?></strong> </a> </div>
                <div id="overtime" class="collapse" data-parent="#accordion" style="">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered mb-0">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_title'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_no_of_days'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_hour'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_rate'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1;
                          $overtime_amount = 0;
                          foreach ($overtime->result() as $r_overtime) { ?>
                            <?php
                            $overtime_total = $r_overtime->overtime_hours * $r_overtime->overtime_rate;
                            $overtime_amount += $overtime_total;
                            ?>
                            <tr>
                              <th scope="row"><?php echo $i; ?></th>
                              <td><?php echo $r_overtime->overtime_type; ?></td>
                              <td><?php echo $r_overtime->no_of_days; ?></td>
                              <td><?php echo $r_overtime->overtime_hours; ?></td>
                              <td><?php echo $r_overtime->overtime_rate; ?></td>
                            </tr>
                          <?php $i++;
                          } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="4" align="right"><strong><?php echo $this->lang->line('xin_acc_total'); ?>:</strong></td>
                            <td><?php echo $this->Xin_model->currency_sign($overtime_amount); ?></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer mt-1">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
  </div>
<?php }
?>
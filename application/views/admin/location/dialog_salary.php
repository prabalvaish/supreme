<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php $session = $this->session->userdata('username'); ?>
<?php $system = $this->Xin_model->read_setting_info(1); ?>
<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header('Access-Control-Allow-Headers: *');
?>
<form id="dropdown">
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <?php $companies = $this->Xin_model->get_companies();
        ?>
        <label for="first_name"><?php echo $this->lang->line('left_company'); ?><i class="hrsale-asterisk">*</i></label>
        <select class="form-control" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
          <option value=""><?php echo $this->lang->line('left_company'); ?></option>
          <?php foreach ($companies as $company) { ?>
            <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?></option>
          <?php } ?>
        </select>
      </div>
    </div>


    <div class="col-md-3">
      <div class="form-group">
        <label for="name"><?php echo $this->lang->line('left_location'); ?><i class="hrsale-asterisk">*</i></label>
        <select name="location_id" id="location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location'); ?>">
          <option value=""><?php echo $this->lang->line('left_location'); ?></option>
        </select>
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group" id="department_ajax">
        <label for="department"><?php echo $this->lang->line('xin_hr_main_department'); ?><i class="hrsale-asterisk">*</i></label>
        <select class="form-control" name="department_id" id="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department'); ?>">
          <option value=""><?php echo $this->lang->line('xin_employee_department'); ?></option>
        </select>
      </div>
    </div>
    <div class="col-md-2" id="subdepartment_ajax">
      <div class="form-group">
        <label for="designation"><?php echo $this->lang->line('xin_hr_sub_department'); ?><i class="hrsale-asterisk">*</i></label>
        <select class="form-control" name="subdepartment_id" id="subdepartment_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_hr_sub_department'); ?>">
          <option value=""><?php echo $this->lang->line('xin_hr_sub_department'); ?></option>
        </select>
      </div>
    </div>
    <div class="col-md-2" id="designation_ajax">
      <div class="form-group">
        <label for="designation"><?php echo $this->lang->line('xin_designation'); ?><i class="hrsale-asterisk">*</i></label>
        <select class="form-control" name="designation_id" id="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation'); ?>">
          <option value=""><?php echo $this->lang->line('xin_designation'); ?></option>
        </select>
      </div>
    </div>
  </div>
</form>

<div class="mb-3 sw-container tab-content">


  <div id="smartwizard-2-step-2" class="animated fadeIn tab-pane step-content mt-3" style="display: block;">
    <div class="cards-body">
      <div class="card overflow-hidden">
        <div class="row no-gutters row-bordered row-border-light">
          <div class="col-md-3 pt-0">
            <div class="list-group list-group-flush account-settings-links">
              <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-allowances">
                <i class="lnr lnr-car text-lightest"></i> &nbsp; <?php echo $this->lang->line('xin_employee_set_allowances'); ?></a>
              <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-statutory_deductions">
                <i class="lnr lnr-store text-lightest"></i> &nbsp; <?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></a>
              <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-overtime">
                <i class="lnr lnr-tag text-lightest"></i> &nbsp; <?php echo $this->lang->line('dashboard_overtime'); ?></a>
              <a class="list-group-item list-group-item-action" data-toggle="list" href="#tab_summary">
                <i class="lnr lnr-location text-lightest"></i> &nbsp; <?php echo $this->lang->line('xin_Summary'); ?></a>
            </div>
          </div>
          <div class="col-md-9">
            <div class="tab-content">

              <div class="tab-pane fade show active" id="account-allowances">
                <div class="box">
                  <div class="card-header with-elements"> <span class="card-header-title mr-2"> <strong> <?php echo $this->lang->line('xin_list_all'); ?></strong> <?php echo $this->lang->line('xin_employee_set_allowances'); ?> </span> </div>
                  <div class="card-body">
                    <div class="box-datatable table-responsive">
                      <table class="table table-striped table-bordered dataTable" id="xin_table_all_allowances" style="width:100%;">
                        <thead>
                          <tr>
                            <th><?php echo $this->lang->line('dashboard_xin_title'); ?></th>
                            <th><?php echo $this->lang->line('xin_amount'); ?></th>
                            <th><?php echo $this->lang->line('xin_action'); ?></th>

                          </tr>
                        </thead>
                        <tbody id="table">

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="card-header with-elements"> <span class="card-header-title mr-2"> <strong> <?php echo $this->lang->line('xin_employee_set_allowances'); ?></strong> </span> </div>
                <div class="card-body pb-2">
                  <?php $attributes = array('name' => 'company_update_allowance', 'id' => 'company_update_allowance', 'autocomplete' => 'off'); ?>
                  <?php $hidden = array('basic_info' => 'INSERT'); ?>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="pf_option"><?php echo $this->lang->line('xin_salary_allowance_options'); ?><i class="hrsale-asterisk">*</i></label>
                        <select name="pf_option" id="pf_option" class="form-control" data-plugin="select_hrm">
                          <option value="0">Exclude In PF</option>
                          <option value="1">Include In PF</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="account_title"><?php echo $this->lang->line('dashboard_xin_title'); ?><i class="hrsale-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>" name="allowance_title" type="text" value="" id="allowance_title">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="account_number"><?php echo $this->lang->line('xin_amount'); ?><i class="hrsale-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="allowance_amount" type="text" value="" id="allowance_amount">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hrsale_form', 'name' => 'btn1', 'id' => 'btn1', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> ' . $this->lang->line('xin_save'))); ?> </div>

                      </div>
                    </div>
                  </div>
                  <?php echo form_close(); ?>
                </div>
              </div>
              <div class="tab-pane fade" id="account-statutory_deductions">
                <div class="box">
                  <div class="card-header with-elements"> <span class="card-header-title mr-2"> <strong> <?php echo $this->lang->line('xin_list_all'); ?></strong> <?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?> </span> </div>
                  <div class="card-body">
                    <div class="box-datatable table-responsive">
                      <table class="table table-striped table-bordered dataTable" id="xin_table_all_statutory_deductions" style="width:100%;">
                        <thead>
                          <tr>
                            <th><?php echo $this->lang->line('xin_action'); ?></th>
                            <th><?php echo $this->lang->line('dashboard_xin_title'); ?></th>
                            <th><?php echo $this->lang->line('xin_amount'); ?></th>
                            <th><?php echo $this->lang->line('xin_salary_sd_options'); ?></th>
                          </tr>
                        </thead>
                        <tbody id="table_deduction">

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card-header with-elements"> <span class="card-header-title mr-2"> <strong> <?php echo $this->lang->line('xin_employee_set_statutory_deductions'); ?></strong> </span> </div>
                <div class="card-body pb-2">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="statutory_options" id="statutory_options"><?php echo $this->lang->line('xin_salary_sd_options'); ?><i class="hrsale-asterisk">*</i></label>
                        <select name="statutory_options" class="form-control" data-plugin="select_hrm">
                          <option value="0"><?php echo $this->lang->line('xin_title_tax_fixed'); ?></option>
                          <option value="1"><?php echo $this->lang->line('xin_title_tax_percent'); ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <label for="title"><?php echo $this->lang->line('dashboard_xin_title'); ?><i class="hrsale-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title'); ?>" name="title" type="text" value="" id="title">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="amount"><?php echo $this->lang->line('xin_amount'); ?>
                          <i class="hrsale-asterisk">*</i> </label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount'); ?>" name="amount" type="text" value="" id="amount">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'btn_deduction', 'id' => 'btn_deduction', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> ' . $this->lang->line('xin_save'))); ?> </div>
                      </div>
                    </div>
                  </div>
                  <?php echo form_close(); ?>
                </div>
              </div>
              <div class="tab-pane fade" id="account-overtime">
                <div class="box">
                  <div class="card-header with-elements"> <span class="card-header-title mr-2"> <strong> <?php echo $this->lang->line('xin_list_all'); ?></strong> <?php echo $this->lang->line('dashboard_overtime'); ?> </span> </div>
                  <div class="card-body">
                    <div class="box-datatable table-responsive">
                      <table class="table table-striped table-bordered dataTable" id="xin_table_emp_overtime" style="width:100%;">
                        <thead>
                          <tr>
                            <th><?php echo $this->lang->line('xin_employee_overtime_title'); ?></th>
                            <th><?php echo $this->lang->line('xin_employee_overtime_rate'); ?></th>
                            <th><?php echo $this->lang->line('xin_action'); ?></th>

                          </tr>
                        </thead>
                        <tbody id="tbl_overtime"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card-header with-elements"> <span class="card-header-title mr-2"> <strong> <?php echo $this->lang->line('dashboard_overtime'); ?></strong> </span> </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="overtime_type"><?php echo $this->lang->line('xin_employee_overtime_title'); ?><i class="hrsale-asterisk">*</i></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_title'); ?>" name="overtime_type" type="text" value="" id="overtime_type">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="overtime_rate"><?php echo $this->lang->line('xin_employee_overtime_rate'); ?><i class="hrsale-asterisk">*</i></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_rate'); ?>" name="overtime_rate" type="text" value="" id="overtime_rate">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'btn_overtime', 'id' => 'btn_overtime', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> ' . $this->lang->line('xin_save'))); ?> </div>
                    </div>
                  </div>
                </div>
                <?php echo form_close(); ?>
              </div>




              <!-- Summary -->
              <div class="tab-pane fade" id="tab_summary">
                <div class="box">
                  <div class="card-header with-elements"> <span class="card-header-title mr-2"> <?php echo $this->lang->line('xin_summary'); ?> </span> </div>
                  <div class="card-body">
                    <div class="box-datatable table-responsive">
                      <table class="table table-striped table-bordered dataTable" id="xin_table_emp_overtime" style="width:100%;">
                        <thead>
                          <tr>
                            <th>Elements</th>
                            <th>Rate</th>
                            <th>Per Month (Rs)</th>
                            <th>Leave</th>
                            <th>Bonus</th>
                            <th>Gratuaty</th>
                            <th>Overtime</th>
                          </tr>
                        </thead>
                        <tbody id="table_summary">

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ##############################Edit Modal#################################3 -->
  <div class="modal fade" id="editEmpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Allowances</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">

            <label class="col-md-3 col-form-label" for="pf_allow1"><?php echo $this->lang->line('xin_salary_allowance_options'); ?><i class="hrsale-asterisk">*</i></label>
            <div class="col-md-9">
              <select name="pf_option1" id="pf_option1" class="form-control" data-plugin="select_hrm">
                <option value="0">Exclude In PF</option>
                <option value="1">Include In PF</option>
                <!-- <option value="2"><?php echo $this->lang->line('xin_partially_taxable'); ?></option> -->
              </select>
            </div>

          </div>
          <div class="form-group row">
            <label class="col-md-3 col-form-label">Allowance Ammount*</label>
            <div class="col-md-9">
              <input type="number" name="allowance" id="allowance" class="form-control" placeholder="Amount" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-form-label">Allowance Title*</label>
            <div class="col-md-9">
              <input type="text" name="title_allowance" id="title_allowance" class="form-control" placeholder="Allowance Title" required>
            </div>
            <input type="text" name="id_allowance" id="id_allowance" class="form-control" placeholder="Allowance Title" hidden>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'update', 'id' => 'update', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> ' . $this->lang->line('xin_save'))); ?> </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ###############################Edit Deduction Model################################ -->
  <div class="modal fade" id="editDeductionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Deduction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-md-3 col-form-label">Deduction Ammount*</label>
            <div class="col-md-9">
              <input type="number" name="deduction" id="deduction" class="form-control" placeholder="Amount" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 col-form-label">Deduction Title*</label>
            <div class="col-md-9">
              <input type="text" name="title_deduction" id="title_deduction" class="form-control" placeholder="Deduction Title" required>
            </div>
            <input type="hidden" name="id_deduction" id="id_deduction" class="form-control" placeholder="Allowance Title">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'updateDeduction', 'id' => 'updateDeduction', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fas fa-check-square"></i> ' . $this->lang->line('xin_save'))); ?> </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {

      $('#company_id').change(function() {

        var company = $('#company_id').val();
        if (company_id != '') {
          $.ajax({
            url: "<?php echo base_url(); ?>admin/Company_Salary/getCustomer",
            method: "POST",
            data: {
              company_id: company
            },
            success: function(data) {
              $('#location_id').html(data);
            }
          });
        } else {
          $('#department_id').html('<option value="">Select State</option>');
          $('#subdepartment_id').html('<option value="">Select City</option>');
        }
      });

      $('#location_id').change(function() {
        var location = $('#location_id').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/getLocation",
          method: "POST",
          data: {
            location_id: location
          },
          success: function(data) {
            $('#department_id').html(data);
            $('#subdepartment_id').html('<option value="">Select Department</option>')

          }
        });
      });

      $('#department_id').change(function() {
        var department = $('#department_id').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/getDepartment",
          method: "POST",
          data: {
            department_id: department
          },
          success: function(data) {
            $('#subdepartment_id').html(data);
          }
        });
      });

      $('#subdepartment_id').change(function() {
        var department = $('#subdepartment_id').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/getDesignation",
          method: "POST",
          data: {
            subdepartment_id: department
          },
          success: function(data) {
            $('#designation_id').html(data);
          }
        });
      });

      // Load Table Content
      $('#designation_id').change(function() {
        var company_id = $('#company_id').val();
        var customer_id = $('#location_id').val();
        var location_id = $('#department_id').val();
        var department_id = $('#subdepartment_id').val();
        var designation_id = $('#designation_id').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/getAllowancesData",
          method: "POST",
          data: {
            company_id: company_id,
            customer_id: customer_id,
            location_id: location_id,
            department_id: department_id,
            designation_id: designation_id,
          },
          dataType: 'json',
          success: function(data) {
            console.log('Hello' + data[2][0].basic_salary);
            var html = '';
            var html1 = '';
            var html2 = '';
            var html3 = '';
            //var i;
            var temp = data[1];
            temp = temp.slice(0, data[0]);
            var temp1 = data[1];
            temp1 = temp1.slice(data[0]);
            for (let i = 0; i < data[0]; i++) {

              html += '<tr id="' + data.id + '">' +
                '<td>' + temp[i].allowance_title + '</td>' +
                '<td>' + '₹' + temp[i].allowance_amount + '</td>' +
                '<td>' +

                '<a href="javascript:void(0);" class="btn btn-info btn-sm editModal" data-allowance_amount="' + temp[i].allowance_amount + '" data-allowance_title="' + temp[i].allowance_title + '" data-allowance_id="' + temp[i].allowance_id + '" >Edit</a>' +
                '</td>' +
                '</tr>';

            }

            $('#table').html(html);
            for (let i = 0; i < temp1.length; i++) {

              html1 += '<tr id="' + data.id + '">' +
                '<td>' + temp1[i].deduction_title + '</td>' +
                '<td>' + '₹' + temp1[i].deduction_amount + '</td>' +
                '<td>' +

                '<a href="javascript:void(0);" class="btn btn-info btn-sm editDeductionModal" data-deduction_amount="' + temp1[i].deduction_amount + '" data-deduction_title="' + temp1[i].deduction_title + '" data-statutory_deductions_id="' + temp1[i].statutory_deductions_id + '" >Edit</a>' +
                '</td>' +
                '</tr>';

            }

            $('#table_deduction').html(html1);
    
            var allow_total = 0;
            var other_allow_total = 0;
            var standard_salary =0;
            for (i = 0; i < data[2].length; i++) {
              if (data[2][i].pf_option == 1) {
                var allow_total = allow_total + (Number(data[2][i].allowance_amount));
              } else {
                var other_allow_total = other_allow_total + (Number(data[2][i].allowance_amount));
              }
            }
            allow_total += (Number(data[2][0].basic_salary));
standard_salary = (Number(allow_total))+(Number(other_allow_total));

            for (i = 0; i < data[2].length; i++) {
              if (i == 0) {
                html2 += '<tr id="' + data[2][i].id + '">' +
                  '<td>Basic Pay</td>' +
                  '<td>'+ '₹' +(Number(data[2][0].basic_salary)/26)+'</td>' +
                  '<td>' + '₹' + data[2][0].basic_salary + '</td>' +
                  '</tr>'

              }
              if (data[2][i].pf_option == 1) {
                html2 +=
                  '<tr id="' + data[2][i].id + '">' +
                  '<td>' + data[2][i].allowance_title + '</td>' +
                  '<td>'+ '₹' +(Number(data[2][i].allowance_amount)/26)+'</td>' +
                  '<td>' + '₹' + data[2][i].allowance_amount + '</td>' +

                  '</tr>';
              }
            }
            html2 +=
              '<tr id="' + data[2][0].id + '">' +
              '<td><strong>' + 'PF Salary (A)' + '</strong></td>' +
              '<td> </td>' +
              '<td><strong>' + '₹' + allow_total + '</strong></td>' +
              '</tr>';
            for (i = 0; i < data[2].length; i++) {
              if (data[2][i].pf_option == 0) {
                html2 += '<tr id="' + data[2][i].id + '">' +
                  '<td>' + data[2][i].allowance_title + '</td>' +
                  '<td>'+'₹'+ (Number(data[2][i].allowance_amount)/26)+'</td>' +
                  '<td>' + '₹' + data[2][i].allowance_amount + '</td>' +
                  '</tr>';
              }
            }
            html2 += '<tr id="' + data[2][0].id + '">' +
              '<td><strong>' + 'Other Salary (B)' + '</strong></td>' +
              '<td> </td>' +
              '<td><strong>' + '₹' + other_allow_total + '</strong></td>' +
              '</tr>';
            html2 += '<tr id="' + data[2][0].id + '">' +
              '<td><strong>' + 'Standard Salary (A + B)' + '</strong></td>' +
              '<td> </td>' +
              '<td><strong>' + '₹' + standard_salary + '</strong></td>' +
              '</tr>';
            $('#table_summary').html(html2);


            html2 += '<tr id="' + data.id + '">' +
              '<td>' + data[4][0].overtime_title + '</td>' +
              '<td>' + '₹' + data[4][0].overtime_rate + '</td>' +
              '<td>' +
              '<a href="javascript:void(0);" class="btn btn-info btn-sm editDeductionModal"  >Edit</a>' +

              '</td>' +
              '</tr>';



            $('#tbl_overtime').html(html3);


          }
        });
      });


      // edit Allowance Modal
      $('#table').on('click', '.editModal', function() {
        $('#editEmpModal').modal('show');
        $("#allowance").val($(this).data('allowance_amount'));
        $("#title_allowance").val($(this).data('allowance_title'));
        $("#id_allowance").val($(this).data('allowance_id'));
      });

      // edit Deduction Modal
      $('#table_deduction').on('click', '.editDeductionModal', function() {
        $('#editDeductionModal').modal('show');
        $("#deduction").val($(this).data('deduction_amount'));
        $("#title_deduction").val($(this).data('deduction_title'));
        $("#id_deduction").val($(this).data('statutory_deductions_id'));
      });


      // Button add Allowances
      $('#btn1').click(function(e) {
        var company_id = $('#company_id').val();
        var customer_id = $('#location_id').val();
        var location_id = $('#department_id').val();
        var department_id = $('#subdepartment_id').val();
        var designation_id = $('#designation_id').val();
        var is_allowance_taxable = 0;
        var amount_option = 0;
        var allowance_title = $('#allowance_title').val();
        var allowance_amount = $('#allowance_amount').val();
        var pf_option = $('#pf_option').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/location/company_allowance_option",
          method: "POST",
          data: {
            company_id: company_id,
            customer_id: customer_id,
            location_id: location_id,
            department_id: department_id,
            designation_id: designation_id,
            is_allowance_taxable: is_allowance_taxable,
            amount_option: amount_option,
            allowance_title: allowance_title,
            allowance_amount: allowance_amount,
            pf_option: pf_option
          },
          success: function(data) {
            alert("Your bookmark has been added.");
          }
        });
        e.preventDefault();
      });

      // Update Allowances
      $('#update').click(function(e) {
        var allowance_id = $('#id_allowance').val();
        var allowance_title = $('#title_allowance').val();
        var allowance_amount = $('#allowance').val();
        var pf_option = $('#pf_option1').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/update_allowance_option",
          method: "POST",
          data: {
            allowance_id: allowance_id,
            allowance_title: allowance_title,
            allowance_amount: allowance_amount,
            pf_option: pf_option
          },
          success: function(data) {
            alert("Your bookmark has been added.");
          }
        });
        e.preventDefault();
      });

      // Update Deduction Amount
      $('#updateDeduction').click(function(e) {
        var statutory_deductions_id = $('#id_deduction').val();
        var deduction_title = $('#title_deduction').val();
        var deduction_amount = $('#deduction').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/update_deduction_option",
          method: "POST",
          data: {
            statutory_deductions_id: statutory_deductions_id,
            deduction_title: deduction_title,
            deduction_amount: deduction_amount
          },
          success: function(data) {
            alert("Your bookmark has been added.");
          }
        });
        e.preventDefault();
      });

      // Add Deduction
      $('#btn_deduction').click(function(e) {
        var company_id = $('#company_id').val();
        var customer_id = $('#location_id').val();
        var location_id = $('#department_id').val();
        var department_id = $('#subdepartment_id').val();
        var designation_id = $('#designation_id').val();
        var statutory_options = $('#statutory_options').val();
        var deduction_title = $('#title').val();
        var deduction_amount = $('#amount').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/location/company_detection_option",
          method: "POST",
          data: {
            company_id: company_id,
            customer_id: customer_id,
            location_id: location_id,
            department_id: department_id,
            designation_id: designation_id,
            statutory_options: statutory_options,
            deduction_title: deduction_title,
            deduction_amount: deduction_amount
          },
          success: function(data) {
            alert("Your bookmark has been added.");
          }
        });
        e.preventDefault();
      });
      // Add Overtime Rate
      $('#btn_overtime').click(function(e) {
        var company_id = $('#company_id').val();
        var customer_id = $('#location_id').val();
        var location_id = $('#department_id').val();
        var department_id = $('#subdepartment_id').val();
        var designation_id = $('#designation_id').val();
        var overtime_title = $('#overtime_type').val();
        var overtime_rate = $('#overtime_rate').val();
        $.ajax({
          url: "<?php echo base_url(); ?>admin/Company_Salary/company_overtime_rate",
          method: "POST",
          data: {
            company_id: company_id,
            customer_id: customer_id,
            location_id: location_id,
            department_id: department_id,
            designation_id: designation_id,
            overtime_title: overtime_title,
            overtime_rate: overtime_rate
          },
          success: function(data) {
            alert("Your bookmark has been added.");
          }
        });
        e.preventDefault();
      });
    });
  </script>
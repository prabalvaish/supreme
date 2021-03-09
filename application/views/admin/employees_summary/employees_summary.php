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
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="form-group">
                <label for="name"><?php echo $this->lang->line('left_location'); ?><i class="hrsale-asterisk">*</i></label>
                <select name="location_id" id="location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location'); ?>">
                    <option value=""><?php echo $this->lang->line('left_location'); ?></option>
                </select>
            </div>
        </div>
        <div class="col-md mb-4">
            <label class="form-label"><?php echo $this->lang->line('xin_select_month'); ?></label>
            <input class="form-control hr_month_year" placeholder="<?php echo $this->lang->line('xin_select_month'); ?>" id="month_year" name="month_year" type="text" value="<?php echo date('Y-m'); ?>">
        </div>
    </div>
    <div class="box-datatable table-responsive">
        <table class="table table-striped table-bordered dataTable" id="xin_table_emp_overtime" style="width:100%;">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Pincode</th>
                    <th>Date Of Birth</th>
                    <th>Gender</th>
                    <th>Company Name</th>
                    <th>Customer Name</th>
                    <th>Department Name</th>
                    <th>Designation Name</th>
                    <th>Date Of Joining</th>
                    <th>Address</th>
                    <th>Salary Month</th>
                    <th>Gross Salary</th>
                    <th>Paid Salary</th>
                    <th>PT</th>
                    <th>PF</th>
                    <th>ESIC</th>
                    <th>TDS</th>
                    <th>LWF</th>
                    <th>HRA</th>
                    <th>Conv_Allow</th>
                    <th>Other_Allow</th>
                    <th>LWW</th>
                </tr>
            </thead>
            <tbody id="table_summary">

            </tbody>
        </table>
    </div>
</form>

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
            }
        });
        $('#month_year').change(function() {
            var company = $('#company_id').val();
            var customer = $('#location_id').val();
            var month = $('#month_year').val();
            if (company_id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/employees_summary/getSummary",
                    method: "POST",
                    data: {
                        company_id: company,
                        location_id: customer,
                        salary_month: month
                    },
                    
                    success: function(dat) {
                        //console.log(dat);
                        data=JSON.parse(dat);
                        // console.log(data[0]);
                        // console.log(data.length);
                        var html = '';
                        for (let i = 0; i < data.length; i++) {
                            
                            html += '<tr id="' + data.id + '">' +
                                '<td>' + data[i].emp_id + '</td>' +
                                '<td>'  + data[i].first_name +'&nbsp'+ data[i].last_name+'</td>' +
                                 '<td>' +  data[i].email+ '</td>'+
                                '<td>'  + data[i].pincode+ '</td>'+
                                '<td>'  + data[i].date_of_birth + '</td>'+
                                '<td>'  + data[i].gender + '</td>'
                                +'<td>' + data[i].name + '</td>'+
                                '<td>'  + data[i].location_name + '</td>'+
                                '<td>'  + data[i].department_name + '</td>'+
                                '<td>'  + data[i].designation_name + '</td>'+
                                '<td>'  + data[i].date_of_joining + '</td>'+
                                '<td>'  + data[i].address + '</td>'+
                                '<td>'  + data[i].salary_month + '</td>'+
                                '<td>' +'₹' + data[i].net_salary + '</td>'+
                                '<td>' +'₹' + data[i].grand_net_salary +'</td>'+
                                '<td>' +'₹' + data[i].PT + '</td>'+
                                '<td>' +'₹' + data[i].PF + '</td>'+
                                '<td>' +'₹' + data[i].ESIC + '</td>'+
                                '<td>' +'₹' + data[i].TDS + '</td>'+
                                '<td>' +'₹' + data[i].LWF + '</td>'+
                                '<td>' +'₹' + data[i].HRA + '</td>'+
                                '<td>' +'₹' + data[i].Conv_Allow + '</td>'+

                                '<td>' +'₹' + data[i].Other_Allow + '</td>'+

                                '<td>' +'₹' + data[i].LWW + '</td>'+
                                '</tr>';
                        }

                        $('#table_summary').html(html);
                    }
                });
            }
        });
    })
</script>
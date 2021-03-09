<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $result = $this->Designation_model->ajax_is_salary_information($id);
?>


<div class="form-group" id="salary_ajax">
  <!-- <label class="form-label" ><?php echo $this->lang->line('dashboard_salary');?><i class="hrsale-asterisk">*</i></label> -->
  <?php foreach($result as $designation) {?>
  <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_salary'); ?>" name="basic_salary"  value="<?php echo $designation->basic_salary?>" type="hidden">
  <?php } ?>

  <!-- <select class="form-control" name="basic_salary" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_salary');?>">
    <option value=""><?php echo $this->lang->line('dashboard_salary');?></option>
    <?php foreach($result as $designation) {?>
    <option value="<?php echo $designation->basic_salary?>"><?php echo $designation->basic_salary?></option>
    <?php } ?>
  </select> -->
</div>
<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>
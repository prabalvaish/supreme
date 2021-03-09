<?php $result = $this->Designation_model->ajax_company_designation_info($company_id);?>
<?php
?>
<div class="form-group">
<label for="designation"><?php echo $this->lang->line('dashboard_designation');?></label>
  <select class="form-control" name="designation_id"  id="designation_ajax_select" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_designation');?>">
    <option value=""></option>
    <?php foreach($result as $designation) {?>
    <option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
    <?php } ?>
  </select>
</div>
<?php
//}
?>
<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
  $('[data-plugin="select_hrm"]').select2({ width:'100%' });
  jQuery("#designation_ajax select").change(function(){
		// jQuery(this).val()
		// console.log("ok")
		// alert("ok")
		jQuery.get(base_url+"/get_salary/"+jQuery(this).val(), function(data, status){
			jQuery('#salary_ajax').html(data);
		});
	});
	
});
</script>

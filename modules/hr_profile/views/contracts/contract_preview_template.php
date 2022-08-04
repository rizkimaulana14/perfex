<div class="">
	<div class="">
		<?php
		$contract_status = (isset($contracts) ? $contracts->contract_status : '');
		?>

		<?php if($contract_status == 'draft'){ ?>
			<div class="wrap">
				<div class="ribbonc contract-ribbonc-warning" ><span><?php echo _l('hr_hr_draft'); ?></span></div>
			</div>
		<?php }elseif($contract_status == 'valid'){ ?> 
			<div class="wrap">
				<div class="ribbonc contract-ribbonc-success" ><span><?php echo _l('hr_hr_valid'); ?></span></div>
			</div>
		<?php }elseif($contract_status == 'invalid'){ ?>
			<div class="wrap">
				<div class="ribbonc contract-ribbonc-danger" ><span><?php echo _l('hr_hr_expired'); ?></span></div>
			</div>
		<?php } elseif($contract_status == 'finish'){ ?>
			<div class="wrap">
				<div class="ribbonc contract-ribbonc-primary" ><span><?php echo _l('hr_hr_finish'); ?></span></div>
			</div>
		<?php }?>


		<?php $value = (isset($contracts) ? $contracts->name_contract : ''); ?>
		<?php $attrs = (isset($contracts) ? array() : array('autofocus'=>true)); ?>

	</div>

	<div class="col-md-12">
		<h5 class="h5-color"><?php echo _l('general_info') ?></h5>
		<hr class="hr-color">
	</div>

	<div class="col-md-6" >
		<table class="table border table-striped ">
			<tbody>
				<?php 
				$contract_code = (isset($contracts) ? $contracts->contract_code : ''); ?>

				<tr class="project-overview">
					<td class="bold" width="30%"><?php echo _l('hr_contract_code'); ?></td>
					<td class="text-right"><?php echo html_entity_decode($contract_code); ?></td>
				</tr>
				<tr class="project-overview">
					<td class="bold" width="30%"><?php echo _l('hr_name_contract'); ?></td>
					<?php foreach($contract_type as $c){
						if(isset($contracts) && $contracts->name_contract == $c['id_contracttype'] ){
							?>
							<td class="text-right"><?php echo html_entity_decode($c['name_contracttype']); ?></td>
						<?php }?>
					<?php }?>
				</tr>
				
			</tbody>
		</table>

	</div>

	<div class="col-md-6" >
		<table class="table table-striped">

			<tbody>
				<tr class="project-overview">
					<td class="bold" width="40%"><?php echo _l('staff'); ?></td>
					<?php foreach($staff as $s){
						if(isset($contracts) && $contracts->staff == $s['staffid'] ){
							?>
							<td class="text-right">
								<a href="<?php echo admin_url('profile/'.$s['staffid']); ?>">
									<?php echo staff_profile_image($s['staffid'],[
										'staff-profile-image-small mright5',
									], 'small', [
										'data-toggle' => 'tooltip',
										'data-title'  =>  get_staff_full_name($s['staffid']),
									]); ?>
								</a><?php echo html_entity_decode($s['firstname'].' '.$s['lastname']); ?></td>
							<?php }?>
						<?php }?>
					</tr>
					<tr class="project-overview">
						<?php $start_valid = (isset($contracts) ? $contracts->start_valid : '');
						?>
						<?php $end_valid = (isset($contracts) ? $contracts->end_valid : '');
						?>
						<td class="bold"><?php echo _l('time'); ?></td>
						<td class="text-right"><?php echo _d($start_valid) ." - "._d($end_valid); ?></td>
					</tr>
					<tr class="project-overview">
						<td class="bold" width="30%"><?php echo _l('hr_hourly_rate_month'); ?></td>
						<td class="text-right"><?php echo _l($contracts->hourly_or_month); ?></td>
					</tr>
					<tr class="project-overview hide">
						<?php
						$contract_status = (isset($contracts) ? $contracts->contract_status : '');
						$_data='';
						?>
						<td class="bold"><?php echo _l('hr_status_label'); ?></td>
						<td class="text-right">
							<?php if($contract_status == 'draft' ){
								$_data .= ' <span class="label label-warning" > '._l('hr_hr_draft').' </span>';
							}elseif($contract_status == 'valid'){
								$_data .= ' <span class="label label-success"> '._l('hr_hr_valid').' </span>';
							}elseif($contract_status == 'invalid'){
								$_data .= ' <span class="label label-danger"> '._l('hr_hr_expired').' </span>';
							}elseif($contract_status == 'finish'){
								$_data .= ' <span class="label label-primary"> '._l('hr_hr_finish').' </span>';
							}

							echo html_entity_decode($_data);
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="col-md-12">
			<h5 class="h5-color"><?php echo _l('hr_wages_allowances') ?></h5>
			<hr class="hr-color">
		</div>

		<div class="col-md-12">
			<table class="table border table-striped ">
				<thead>
					<th class="th-color"><?php echo _l('hr_hr_contract_rel_type'); ?></th>
					<th class="text-center th-color"><?php echo _l('hr_hr_contract_rel_value'); ?></th>
					<th class="th-color"><?php echo _l('hr_start_month'); ?></th>
					<th class="th-color"><?php echo _l('note'); ?></th>
				</thead>
				<tbody>
					<?php foreach($contract_details as $contract_detail){ ?>
						<?php 
						$type_name ='';
						if(preg_match('/^st_/', $contract_detail['rel_type'])){
							$rel_value = str_replace('st_', '', $contract_detail['rel_type']);
							$salary_type = $this->hr_profile_model->get_salary_form($rel_value);

							$type = 'salary';
							if($salary_type){
								$type_name = $salary_type->form_name;
							}

						}elseif(preg_match('/^al_/', $contract_detail['rel_type'])){
							$rel_value = str_replace('al_', '', $contract_detail['rel_type']);
							$allowance_type = $this->hr_profile_model->get_allowance_type($rel_value);

							$type = 'allowance';
							if($allowance_type){
								$type_name = $allowance_type->type_name;
							}
						}
						?>
						<tr>
							<td><?php echo html_entity_decode($type_name); ?></td>
							<td class="text-right"><?php echo app_format_money((float)$contract_detail['rel_value'],''); ?></td>
							<td><?php echo _d($contract_detail['since_date']); ?></td>
							<td><?php echo html_entity_decode($contract_detail['contract_note']); ?></td>

						</tr>
					<?php } ?>
				</tbody>
			</table>  
		</div>

		<div class="col-md-12">
			<h5 class="h5-color"><?php echo _l('hr_sign_day') ?></h5>
			<hr class="hr-color">
		</div>

		<div class="col-md-6" >
			<table class="table border table-striped " >
				<tbody>
					<?php
					$sign_day = (isset($contracts) ? $contracts->sign_day : '');
					?>
					<tr class="project-overview">
						<td class="bold" width="30%"><?php echo _l('hr_sign_day'); ?></td>
						<td class="text-right"><?php echo _d($sign_day); ?></td>
					</tr>
					<tr class="project-overview">
						<?php 
						if(isset($staff_delegate_role) && $staff_delegate_role != null){
							$staff_role = $staff_delegate_role->name ; }else{
								$staff_role = '';   
							} ?>

							<td class="bold" width="30%"><?php echo _l('hr_hr_job_position'); ?></td>
							<td class="text-right"><?php echo html_entity_decode($staff_role); ?></td>

						</tr>
					</tbody>
				</table>

			</div>

			<div class="col-md-6">
				<table class="table table-striped">

					<tbody>
						<tr class="project-overview">
							<td class="bold" width="40%"><?php echo _l('hr_staff_delegate'); ?></td>
							<?php foreach($staff as $s){ 
								if(isset($contracts) && $contracts->staff_delegate == $s['staffid'] ){
									?>
									<td class="text-right">
										<a href="<?php echo admin_url('profile/'.$s['staffid']); ?>">
											<?php echo staff_profile_image($s['staffid'],[
												'staff-profile-image-small mright5',
											], 'small', [
												'data-toggle' => 'tooltip',
												'data-title'  =>  get_staff_full_name($s['staffid']),
											]); ?>
										</a><?php echo html_entity_decode($s['firstname'].''.$s['lastname']); ?></td>
									<?php }?>
								<?php }?>
							</tr>

						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<div id="contract_attachments" class="mt-2">
						<?php
						$data = '<div class="row" id="attachment_file">';
						foreach($contract_attachment as $attachment) {
							$href_url = site_url('modules/hr_profile/uploads/contracts/'.$attachment['rel_id'].'/'.$attachment['file_name']).'" download';
							if(!empty($attachment['external'])){
								$href_url = $attachment['external_link'];
							}
							$data .= '<div class="mt-1 mb-1 row inline-block full-width att-background-color" >';
							$data .= '<div class="col-md-12 pt-4" >';
							$data .= '<div class="col-md-1 mr-5">';
							$data .= '<a name="preview-btn" onclick="preview_file_staff(this); return false;" rel_id = "'.$attachment['rel_id'].'" id = "'.$attachment['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'._l("preview_file").'">';
							$data .= '<i class="fa fa-eye"></i>'; 
							$data .= '</a>';
							$data .= '</div>';
							$data .= '<div class=col-md-9>';
							$data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
							$data .= '<a href="'.$href_url.'>'.$attachment['file_name'].'</a>';
							$data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
							$data .= '</div>';
							$data .= '</div>';

							$data .= '<div class="clearfix"></div><hr/>';
							$data .= '</div>';
						}
						$data .= '</div>';
						echo html_entity_decode($data);
						?>

					</div>
				</div>

			</div>
		</div>
		<div id="contract_file_data"></div>
		<?php 
		require('modules/hr_profile/assets/js/contracts/preview_contract_file_js.php');
		?>

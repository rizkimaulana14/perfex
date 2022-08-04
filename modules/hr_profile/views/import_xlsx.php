<?php defined('BASEPATH') or exit('No direct script access allowed'); 
?>
<?php 
$file_header = array();
$file_header[] = _l('hr_staff_code');
$file_header[] = _l('hr_firstname');
$file_header[] = _l('hr_lastname');
$file_header[] = _l('email');
$file_header[] = _l('hr_sex');
$file_header[] = _l('hr_hr_birthday');
$file_header[] = _l('staff_add_edit_phonenumber');
$file_header[] = _l('hr_hr_nation');
$file_header[] = _l('hr_hr_religion');
$file_header[] = _l('hr_hr_birthplace');
$file_header[] = _l('hr_domicile');
$file_header[] = _l('hr_permanent_address');
$file_header[] = _l('hr_current_address');
$file_header[] = _l('hr_hr_marital_status');
$file_header[] = _l('citizen_identification');
$file_header[] = _l('hr_license_date');
$file_header[] = _l('hr_register_place');
$file_header[] = _l('hr_hr_literacy');
$file_header[] = _l('hr_hr_job_position');
$file_header[] = _l('hr_job_rank');
$file_header[] = _l('hr_hr_workplace');
$file_header[] = _l('hr_department');
$file_header[] = _l('hr_bank_account_number');
$file_header[] = _l('hr_bank_account_name');
$file_header[] = _l('hr_bank_name');
$file_header[] = _l('hr_Personal_tax_code');
$file_header[] = _l('hr_status_label');
$file_header[] = _l('password');

?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div id ="dowload_file_sample">


						</div>
						<input type="hidden" name="language" value="<?php echo html_entity_decode($active_language); ?>">
						<?php
						if(!isset($simulate)) { ?>
							<ul>
								<li>1. <?php echo _l('hr_import_excel_1'); ?></li>
								<li class="text-danger">2. <?php echo _l('hr_file_xlsx_hr_profile'); ?></li>
							</ul>
							<div class="table-responsive no-dt">
								<table class="table table-hover table-bordered">
									<thead>
										<tr>
											<?php
											$total_fields = 0;
											
											for($i=0;$i<count($file_header);$i++){
												if($i == 0 || $i == 1 ||$i == 2 ||$i == 3){
													?>
													<th class="bold"><span class="text-danger">*</span> <?php echo html_entity_decode($file_header[$i]); ?> </th>
													<?php 
												} else {
													?>
													<th class="bold"><?php echo html_entity_decode($file_header[$i]); ?> </th>
													
													<?php

												} 
												$total_fields++;
											}

											?>
										</tr>
									</thead>
									<tbody>
										<?php for($i = 0; $i<1;$i++){
											echo '<tr>';
											for($x = 0; $x<count($file_header);$x++){
												echo '<td>- </td>';
											}
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
							<hr>
							<div class="row table-responsive no-dt">

								<div class="col-md-3">
									<table class="table table-hover table-bordered">
										<thead>
											<h5> <?php echo _l('hr_department'); ?></h5>
											<tr>
												<th class="bold"><?php echo _l('hr_hr_id'); ?> </th>
												<th class="bold"><?php echo _l('department_name'); ?> </th>
											</tr>
										</thead>
										<tbody>
											<?php
											if(count($departments) > 0 ){
												foreach($departments as $value){ 

													?> 
													<tr>
														<td><?php echo html_entity_decode($value['departmentid']); ?></td>
														<td><?php echo html_entity_decode($value['name']); ?></td>
													</tr>
												<?php } } ?>
											</tbody>
										</table>
									</div>
									<div class="col-md-3">
										<table class="table table-hover table-bordered">
											<thead>
												<h5> <?php echo _l('hr_hr_job_position'); ?></h5>
												<tr>
													<th class="bold"><?php echo _l('hr_hr_id'); ?> </th>
													<th class="bold"><?php echo _l('hr_hr_job_position'); ?> </th>
												</tr>
											</thead>
											<tbody>
												<?php
												if(count($job_positions) > 0) {
													foreach($job_positions as $value){ 
														?> 
														<tr>
															<td><?php echo html_entity_decode($value['position_id']); ?></td>
															<td><?php echo html_entity_decode($value['position_name']); ?></td>
														</tr>
													<?php } }?>
												</tbody>
											</table>
										</div>



										<div class="col-md-3">
											<table class="table table-hover table-bordered">
												<thead>
													<h5> <?php echo _l('hr_sex'); ?></h5>
													<tr>
														<th class="bold"><?php echo _l('hr_hr_id'); ?> </th>
														<th class="bold"><?php echo _l('hr_sex'); ?> </th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>0</td>
														<td><?php echo _l('male') ?></td>
													</tr>
													<tr>
														<td>1</td>
														<td><?php echo _l('female') ?></td>
													</tr>
												</tbody>
											</table>
										</div>



										<div class="col-md-3">
											<table class="table table-hover table-bordered">
												<thead>
													<h5> <?php echo _l('hr_hr_marital_status'); ?></h5>
													<tr>
														<th class="bold"><?php echo _l('hr_hr_id'); ?> </th>
														<th class="bold"><?php echo _l('hr_hr_marital_status'); ?> </th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>0</td>
														<td><?php echo _l('hr_single') ?></td>
													</tr>
													<tr>
														<td>1</td>
														<td><?php echo _l('married') ?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3">
											<table class="table table-hover table-bordered">
												<thead>
													<h5> <?php echo _l('hr_status_work'); ?></h5>
													<tr>
														<th class="bold"><?php echo _l('hr_hr_id'); ?> </th>
														<th class="bold"><?php echo _l('hr_status_work'); ?> </th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>0</td>
														<td><?php echo _l('hr_working') ?></td>
													</tr>
													<tr>
														<td>1</td>
														<td><?php echo _l('hr_maternity_leave') ?></td>
													</tr>
													<tr>
														<td>2</td>
														<td><?php echo _l('hr_inactivity') ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-3">
											<table class="table table-hover table-bordered">
												<thead>
													<h5> <?php echo _l('hr_hr_workplace'); ?></h5>
													<tr>
														<th class="bold"><?php echo _l('id'); ?> </th>
														<th class="bold"><?php echo _l('name'); ?> </th>
														<th class="bold"><?php echo _l('address'); ?> </th>
													</tr>
												</thead>
												<tbody>
													<?php
													if(count($workplaces) > 0 ){
														foreach($workplaces as $value){ 

															?> 
															<tr>
																<td><?php echo html_entity_decode($value['id']); ?></td>
																<td><?php echo html_entity_decode($value['name']); ?></td>
																<td><?php echo html_entity_decode($value['workplace_address']); ?></td>
															</tr>
														<?php } } ?>
													</tbody>
												</table>
											</div>
										</div>



									<?php } ?>

									<hr>
									<div class="row">
										<div class="col-md-4">
											<?php echo form_open_multipart(admin_url('hr_profile/importxlsx2'),array('id'=>'import_form')) ;?>
											<?php echo form_hidden('leads_import','true'); ?>
											<?php echo render_input('file_csv','choose_excel_file','','file'); ?> 

											<div class="form-group">
												<button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv();" ><?php echo _l('import'); ?></button>
											</div>
											<?php echo form_close(); ?>
										</div>
										<div class="col-md-8">
											<div class="form-group" id="file_upload_response">

											</div>
											
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php init_tail(); ?>
			<?php 
			require('modules/hr_profile/assets/js/hr_record/importxlsx_js.php');
			?>
		</body>
		</html>

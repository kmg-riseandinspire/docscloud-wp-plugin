<?php 

global $wpdb;
$tablename = $wpdb->prefix."docscloud";
// Add record
if(isset($_POST['but_submit'])){

	$auth_id = sanitize_text_field( $_POST['auth_id'] );
	$auth_token = sanitize_text_field( $_POST['auth_token'] );
	//$tablename = $wpdb->prefix."docscloud";

	if($auth_id != '' && $auth_token != ''){
		$check_data = $wpdb->get_results("SELECT * FROM ".$tablename." WHERE auth_id='".$auth_id."' ");
	    if(count($check_data) == 0){
	        $insert_sql = "INSERT INTO ".$tablename."(auth_id,auth_token) values('".$auth_id."','".$auth_token."') ";
	        $wpdb->query($insert_sql);
	        echo "Authentication save sucessfully.";
	    }
	}
}
?>
<div class="wpbody" role="main">
	<div id="wpbody-content">
		<div class="wrap">
			<h1 class="add-new-user">Add Auth Details</h1>
			<?php
			$check_data_e = $wpdb->get_results("SELECT * FROM ".$tablename."");
			if(count($check_data_e) == 0){
			?>
			<form method='post' action='' class="validate" novalidate="novalidate" id="createuser">
				<table class="form-table" role="presentation">
					<tbody>
						<tr class="form-field form-required">
							<th scope="row">
								<label for="auth_id">Auth ID <span class="description">(required)</span></label>
							</th>
							<td>
								<input name="auth_id" type="text" id="auth_id" value="" aria-required="true" autocapitalize="none" autocorrect="off" autocomplete="off" maxlength="100">
							</td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row">
								<label for="auth_token">Auth Token <span class="description">(required)</span></label>
							</th>
							<td>
								<input name="auth_token" type="text" id="auth_token" value="" aria-required="true" autocapitalize="none" autocorrect="off" autocomplete="off" maxlength="250">
							</td>
						</tr>

					</tbody>
				</table>
				<p class="submit">
					<button type="submit" class="button button-primary" id="but_submit" name="but_submit">Submit</button>
				</p>
			</form>
			<?php
				}else{
					echo '<p>Already Data exsist.</p>';
				}
			?>
		</div>
	</div>
</div>
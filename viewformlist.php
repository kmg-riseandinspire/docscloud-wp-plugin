<?php 

global $wpdb;
$tablename = $wpdb->prefix."dcforms";
$auth_tbl = $wpdb->prefix."docscloud";
// Add record
if(isset($_POST['ref_form_list'])){
	$entriesList = $wpdb->get_results("SELECT * FROM ".$auth_tbl." order by id desc");
	if(count($entriesList) > 0){
		foreach($entriesList as $entry){
			$url = 'https://app.docscloud.io/api/v2/get-form-list';
			$data = array(
				"auth_id" => $entry->auth_id,
				"auth_token" => $entry->auth_token
			);

			$encodedData = json_encode($data);

			$args = array(
				'body'        => $encodedData,
				'timeout'     => '5',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'cookies'     => array(),
			);
			$response = wp_remote_post( $url, $args );

			$result = json_decode($response['body'], true);
			if(count(@$result['data'])>0){
				foreach($result['data'] as $key=>$val){
					$url = 'https://app.docscloud.io/f/embed/'.$val['form_code'];
					//$embed_url = '<link rel="stylesheet" href="https://app.docscloud.io/css/bootstrap.min.css"><div class="embed-responsive embed-responsive-1by1"><embed class="embed-responsive-item"  src="'.$url.'" ></embed></div>';
					$embed_url = '';
					$form_id = $val['id'];
					$form_name = $val['form_name'];
					$form_code = $val['form_code'];
					$form_url = $val['custom_submit_url'];
					$form_embded_code = $embed_url;
					$short_code = '[docscloud_form form_code="'.$form_code.'"]';
					$entriesListget = $wpdb->get_results("SELECT * FROM ".$tablename." where form_code =".$form_code."");
					if(count($entriesListget) == 0){
						$insert_sql = "INSERT INTO ".$tablename."(form_id, form_name, form_code, form_url, form_embded_code, short_code) values('".$form_id."','".$form_name."','".$form_code."','".$form_url."','".$form_embded_code."','".$short_code."') ";
						$wpdb->query($insert_sql);
						//echo "Authentication save sucessfully.";
					}
				}
				
			}
			
		}

	}else{
		echo '<script>alert("Invalid Authentication");</script>';
	}
	
}

// Delete record
if(isset($_GET['delid'])){
	$delid = $_GET['delid'];
	if ( ! ctype_alnum( $delid ) ) {
		wp_die( "Invalid format" );
	}
	$wpdb->query("DELETE FROM ".$tablename." WHERE id=".$delid);
}
?>
<div class="wrap">	
	<form method='post' action=''>
		<h1 class="wp-heading-inline">Form List</h1>
		<button type="submit" class="page-title-action" name="ref_form_list">Refresh List</button>
	</form>
	<hr class="wp-header-end">
	<br/>
	<table class="wp-list-table widefat fixed striped table-view-list pages">
	<thead>
		<tr>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>S.No</span></a></th>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Form Code</span></a></th>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Form Name</span></a></th>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Short Code</span></a></th>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Action</span></a></th>
		</tr>
	</thead>
	<tbody>
		<?php
		// Select records
		$entriesList = $wpdb->get_results("SELECT * FROM ".$tablename." order by id desc");
		if(count($entriesList) > 0){
			$count = 1;
			foreach($entriesList as $entry){
				$id = $entry->id;
				$form_code = $entry->form_code;
				$form_name = $entry->form_name;
				$short_code = $entry->short_code;
				echo "<tr>
					<td scope='row'>".$count."</td>
					<td>".$form_code."</td>
					<td>".$form_name."</td>
					<td>".$short_code."</td>
					<td><span class=' class='trash''><a class='submitdelete' href='?page=formlist&delid=".$id."' style='color:red;'>Delete</a></span></td>
				</tr>
				";
				$count++;
			}
		}else{
			echo "<tr><td colspan='5'>No record found</td></tr>";
		}
		

		?>
	</tbody>
	</table>
	<div class="clear"></div>
</div>
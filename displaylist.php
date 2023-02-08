<?php 

global $wpdb;
$tablename = $wpdb->prefix."docscloud";

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
	<h1 class="wp-heading-inline">Auth Details</h1><a href='?page=addauth' class='page-title-action'>Add New</a>
	<hr class="wp-header-end">
	<br/>

	<table class="wp-list-table widefat fixed striped table-view-list pages">
	<thead>
		<tr>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>S.No</span></a></th>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Auth ID</span></a></th>
			<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Auth Token</span></a></th>
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
				$auth_id = $entry->auth_id;
				$auth_token = $entry->auth_token;

				echo "<tr>
					<td scope='row'>".$count."</td>
					<td>".$auth_id."</td>
					<td>".$auth_token."</td>
					<td><a href='?page=authdetails&delid=".$id."' class='btn btn-danger' style='color:red;'>Delete</a></td>
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
</div>
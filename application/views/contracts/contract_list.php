<?php 
$stopHtml = "<input name='paginationdiv__contract_stop' id='paginationdiv__contract_stop' type='hidden' value='1' />";
$listCount = count($list);
$i = 0;

echo "<table>";

if(!empty($list)){
	echo "<tr><th style='width:1%;'>&nbsp;</th>".($this->native_session->get('__user_type') == 'pde'? "": "<th>PDE</th>")."<th>Name</th><th>Amount</th><th>Progress</th><th>Date Started</th><th>Status</th><th>Last Updated</th><th>Last Updated By</th></tr>";
	foreach($list AS $row) {
		$i++;
		echo "<tr> 
		<td>";
		if($this->native_session->get('__user_type') != 'provider'){
			echo "<input id='select_".$row['contract_id']."' name='selectall[]' type='checkbox' value='".$row['contract_id']."' class='bigcheckbox'><label for='select_".$row['contract_id']."'></label>";
		} else { echo "&nbsp;";}
		echo "</td>";
		if($this->native_session->get('__user_type') != 'pde'){
			echo "<td><a href='".base_url()."accounts/view_pde/d/".$row['pde_id']."' class='shadowbox closable'>".html_entity_decode($row['pde'], ENT_QUOTES)."</a></td>";
		}
		echo "<td><a href='".base_url()."tenders/view_one/d/".$row['tender_id']."' class='shadowbox closable'>".html_entity_decode($row['tender_notice'], ENT_QUOTES)."</a></td>
		<td>".$row['contract_currency'].format_number($row['contract_amount'],3)."</td>
		<td><div style='white-space:nowrap;'>".$row['progress_percent'].'%'
		.($row['has_notes'] == 'Y'? " <a href='".base_url()."contracts/notes/d/".$row['contract_id']."' class='shadowbox closable'>Notes</a>": '')."</div>";
		
		# allow a provider to add a note only when the contract is active
		if($this->native_session->get('__user_type') != 'provider' || ($row['status'] == 'active' && $this->native_session->get('__user_type') == 'provider')) echo "<div class='green-box btn shadowbox' data-url='".base_url()."contracts/add_note/d/".$row['contract_id']."'>Add Note</div>";
		
		echo "</td>
		<td>".date(SHORT_DATE_FORMAT, strtotime($row['date_started']))."</td>
		<td>".strtoupper($row['status'])."</td>
		<td>".date(FULL_DATE_FORMAT, strtotime($row['last_updated']))."</td>
		<td>".html_entity_decode($row['last_updated_by'], ENT_QUOTES);
		
		 # Check whether you need to stop the loading of the next pages
		if($i == $listCount && ((!empty($n) && $listCount < $n) || (empty($n) && $listCount < NUM_OF_ROWS_PER_PAGE))){
		 echo $stopHtml;
		}
		  echo "</td>
		</tr>";
	}
}

else {
	echo "<tr><td>".format_notice($this, 'WARNING: There are no contracts in this list.').$stopHtml."</td></tr>";
}
echo "</table>";
?>

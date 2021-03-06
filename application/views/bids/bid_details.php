<?php 
echo "<link rel='stylesheet' href='".base_url()."assets/css/pss.css' type='text/css' media='screen' />";
echo "<link rel='stylesheet' href='".base_url()."assets/css/pss.list.css' type='text/css' media='screen' />";
echo "<link rel='stylesheet' href='".base_url()."assets/css/pss.form.css' type='text/css' media='screen' />"; 


if(!empty($msg)){
	echo format_notice($this, $msg);
}
else
{
	echo "<table>
<tr><td colspan='2' class='h2 bold'>".$bid['tender_notice']."</td></tr>
<tr><td colspan='2'>".$bid['summary'];

	if(!empty($bid['documents'])){
		$documents = explode(',',$bid['documents']);
		foreach($documents AS $document) echo "<br><a href='".base_url()."pages/download/file/".$document."'>".$document."</a>"; 
	}

	echo "</td></tr>
<tr><td class='label'>PDE</td><td>".$bid['pde']."</td></tr>
<tr><td class='label'>Validity Period</td><td>".($bid['valid_start_date'] != '0000-00-00'? date(SHORT_DATE_FORMAT, strtotime($bid['valid_start_date'])):'NONE')." TO ".($bid['valid_end_date'] != '0000-00-00'? date(SHORT_DATE_FORMAT, strtotime($bid['valid_end_date'])):'NONE')."</td></tr>
<tr><td class='label'>Bid Amount</td><td>".$bid['bid_currency'].format_number($bid['bid_amount'],3)."</td></tr>";

	if($this->native_session->get('__user_type')){
		echo "<tr><td class='label' style='vertical-align: top;'>Status Trail</td><td>".$bid['status_trail']."</td></tr>";
	}
	echo "</table>";
}
?>
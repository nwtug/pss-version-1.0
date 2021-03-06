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
<tr><td colspan='2' class='h2 bold'>".$tender['name']."</td></tr>
<tr><td colspan='2'>".$tender['description']."</td></tr>
<tr><td class='label'>PDE</td><td>".$tender['pde']."</td></tr>
<tr><td class='label'>Procurement Plan</td><td>".$tender['procurement_plan']."</td></tr>
<tr><td class='label'>Type</td><td>".ucfirst($tender['type'])."</td></tr>
<tr><td class='label'>Method</td><td>".ucwords(str_replace('_', ' ', $tender['method']))."</td></tr>
<tr><td class='label'>Documents</td><td>";

if(!empty($tender['document_url'])){
	$documents = explode(',',$tender['document_url']);
	foreach($documents AS $file) echo "<a href='".base_url()."pages/download/file/".$file."'>".$file."</a><br>";
}
else echo "&nbsp;";

echo "</td></tr>
<tr><td class='label'>Deadline</td><td>".date(SHORT_DATE_FORMAT, strtotime($tender['deadline']))."</td></tr>";

if($this->native_session->get('__user_type') && $this->native_session->get('__user_type') != 'provider'){
echo "<tr><td class='label long'>Display Start Date</td><td>".(strpos($tender['display_start_date'],'0000-00-00') === FALSE? date(SHORT_DATE_FORMAT, strtotime($tender['display_start_date'])): 'NONE')."</td></tr>
<tr><td class='label long'>Display End Date</td><td>".(strpos($tender['display_end_date'],'0000-00-00') === FALSE? date(SHORT_DATE_FORMAT, strtotime($tender['display_end_date'])): 'NONE')."</td></tr>
<tr><td class='label'>Status</td><td>".strtoupper($tender['status'])."</td></tr>
<tr><td class='label'>Date Entered</td><td>".date(SHORT_DATE_FORMAT, strtotime($tender['date_entered']))."</td></tr>
<tr><td class='label'>Entered By</td><td>".$tender['entered_by']."</td></tr>
<tr><td class='label'>Last Updated</td><td>".date(SHORT_DATE_FORMAT, strtotime($tender['last_updated']))."</td></tr>
<tr><td class='label'>Last Updated By</td><td>".$tender['last_updated_by']."</td></tr>";
}
else if(!$this->native_session->get('__user_id')) {
echo "<tr><td colspan='2' style='padding-top:30px;'>".format_notice($this, "WARNING: You need to <a href='javascript:;' onclick=\"window.top.location.href='".base_url()."accounts/register'\" class='bold'>register with us</a> to bid on this notice. 
<BR><BR>If you are already registered, you are not required to register again. You simply need to <a href='javascript:;' onclick=\"window.top.location.href='".base_url()."accounts/login'\" class='bold'>login and bid</a>.")."</td></tr>";
}

echo "</table>";
}
?>
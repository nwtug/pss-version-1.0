<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo IMAGE_URL;?>favicon.ico">
	<title><?php echo SITE_TITLE.': Users';?></title>
    
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/external-fonts.css" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.list.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.shadowbox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.pagination.css" type="text/css" media="screen" />
</head>

<body>
<table class='body-table water-mark-bg'>
<?php 
$this->load->view('addons/secure_header', array('__page'=>'Users' ));
$this->load->view('addons/'.$this->native_session->get('__user_type').'_top_menu', array('__page'=>'users' ));
?>

<tr>
  <td>&nbsp;</td>
  <td class='one-column fill-page'>

<table class='home-list-table'> 
<tr><th class='h3 dark-grey' style='padding-left:10px;border-bottom:1px solid #999;'>Users List</th>
<?php 
if($this->native_session->get('__user_type') == 'admin' 
|| ($this->native_session->get('__user_type') == 'pde' && $this->native_session->get('__is_owner') == 'Y')){ 
	
	$colspan = '3';?>
<th style='border-bottom:1px solid #999; width:1%;padding:0px; padding-right:15px;'><button type='button' id='newitem' name='newitem' class='btn smallbtn green' data-rel='users/add'>New</button></th>
<?php 
} else {
	$colspan = '2';	
}?>
<th style='border-bottom:1px solid #999; width:1%;padding:0px;'><div id='user_actions' class='actions-list-btn list-actions' data-url='users/list_actions/t/users' data-width='300' data-targetdiv='paginationdiv__user_list'><div class='settings'>&nbsp;</div><div>&nbsp;</div></div></th>
</tr>

<tr><td colspan='<?php echo $colspan;?>'><div id='paginationdiv__user_list' class='page-list-div'>
<div id="user__1"><?php $this->load->view('users/user_list',array('list'=>$list));?></div>
</div>
<button type='button' id='refreshlist' name='refreshlist' style='display:none;'></button></td></tr>
<tr><td colspan='<?php echo $colspan;?>'>
<table><tr><td>
         
<div id='user_pagination_div' class='pagination' style="margin:0px;padding:0px; display:inline-block;"><div id="user" class="paginationdiv no-scroll"><div class="previousbtn" style='display:none;'>&#x25c4;</div><div class="selected">1</div><div class="nextbtn">&#x25ba;</div></div><input name="paginationdiv__user_action" id="paginationdiv__user_action" type="hidden" value="<?php echo base_url()."lists/load/t/user";?>" />
<input name="paginationdiv__user_maxpages" id="paginationdiv__user_maxpages" type="hidden" value="<?php echo NUM_OF_LISTS_PER_VIEW;?>" />
<input name="paginationdiv__user_noperlist" id="paginationdiv__user_noperlist" type="hidden" value="<?php echo NUM_OF_ROWS_PER_PAGE;?>" />
<input name="paginationdiv__user_showdiv" id="paginationdiv__user_showdiv" type="hidden" value="paginationdiv__user_list" />
<input name="paginationdiv__user_extrafields" id="paginationdiv__user_extrafields" type="hidden" value="" /></div>
          

</td><td width='1%' class='filter-list shadowbox closable' data-url='<?php echo base_url().'users/list_filter';?>'>FILTER</td></tr></table>
</td></tr>
</table>







</td>
<td>&nbsp;</td>
</tr>

<?php $this->load->view('addons/secure_footer');?>

</table>
<?php echo minify_js('home', array('jquery-2.1.1.min.js', 'jquery-ui.js', 'jquery.form.js', 'pss.js', 'pss.shadowbox.js', 'pss.pagination.js', 'pss.fileform.js', 'pss.list.js'));?>

<?php if(!empty($action)){ ?>
<script>
$(function() { 
	$(document).find('.filter-list').last().click(); 
});
</script>
<?php }?>
</body>
</html>
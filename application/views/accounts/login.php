<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo IMAGE_URL;?>favicon.ico">
    <title><?php echo SITE_TITLE.': Login';?></title>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/external-fonts.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.list.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.shadowbox.css" type="text/css" media="screen" />
</head>

<body>
<table class='body-table water-mark-bg'>

<?php $this->load->view('addons/public_header');

$this->load->view('addons/public_top_menu', array('__page'=>'login'));

if($this->native_session->get('__step') && $this->native_session->get('__step') == 3){
?>
<tr>
  <td>&nbsp;</td><td class='body-title' style='padding:15px;'>&nbsp;</td><td>&nbsp;</td>
</tr>
<?php $this->load->view('addons/step_ribbon', array('step'=>'4')); 

}?>

<tr>
  <td>&nbsp;</td>
  <td style='height:calc(85vh - 214px);'>

<div class='center-block body-table-border'>
<form method="post">
<table class='normal-table'>
<tr><td class='body-title'>Login</td></tr>
<?php if(!empty($msg)) echo "<tr><td style='text-align:left;'>".format_notice($this,$msg)."</td></tr>";?>
<tr><td><input type='text' id='loginusername' name='loginusername' autocapitalize='off' placeholder="User Name" value='' style='width:calc(100% - 20px);' /></td></tr>
<tr><td><input type='password' id='loginpassword' name='loginpassword' autocapitalize='off' placeholder="Password" value='' style='width:calc(100% - 20px);' /></td></tr>
<tr><td style="padding-bottom:0px;"><button type="button" id="submitlogin" name="submitlogin" class="btn grey" style="width:100%;">Login</button>
<?php
if(!empty($a)) {
	echo "<input type='hidden' id='redirect' name='redirect' value='".$a."' />";
}
?></td></tr>

<tr><td class='row-divs'><div class='left-div'><?php if(!$this->native_session->get('__step')){?><a href="<?php echo base_url().'accounts/register';?>">New Account</a><?php }?></div>
<div class='right-div'><a href="<?php echo base_url().'accounts/forgot';?>">Forgot Password</a></div>

</td></tr>
</table>
</form>
</div>


</td>
  <td>&nbsp;</td>
</tr>

<?php $this->load->view('addons/public_footer');?>


</table>
<?php echo minify_js('accounts__login', array('jquery-2.1.1.min.js', 'jquery-ui.js', 'jquery.form.js', 'pss.js', 'pss.shadowbox.js', 'pss.fileform.js'));?>
</body>
</html>
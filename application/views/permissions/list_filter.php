<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/external-fonts.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.shadowbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.datepicker.css" type="text/css" media="screen" />


<table class='normal-table filter-container'>

<tr><td><input type='text' id='phrase' name='phrase' placeholder='Name Search Phrase' data-final='phrase' value='<?php echo $this->native_session->get('permission__phrase');?>' style='width:100%;'/></td></tr>

<tr><td><select id='search__grouptypes' name='search__grouptypes' data-final='type' class='drop-down' style='width:100%;'>
<?php echo get_option_list($this, 'grouptypes', 'select', '', array('selected'=>$this->native_session->get('permission__status')));?>
</select></td></tr>

<tr><td><button type="button" id="applyfilterbtn" name="applyfilterbtn" class="btn blue" onClick="applyFilter('permission')" style="width:100%;">Apply Filter</button>
<input name="layerid" id="layerid" type="hidden" value="" /></td></tr>
</table>
<?php echo minify_js('permissions__list_filter', array('jquery-2.1.1.min.js', 'jquery-ui.js', 'jquery.form.js', 'jquery.datepick.js', 'pss.js', 'pss.shadowbox.js', 'pss.fileform.js', 'pss.datepicker.js', 'pss.pagination.js'));?>

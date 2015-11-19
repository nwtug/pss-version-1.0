<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/external-fonts.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.shadowbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.datepicker.css" type="text/css" media="screen" />

<table class='normal-table filter-container'>

<tr><td><span style="width:50%;padding:0px;">
  <select id='category__documents' name='category__documents' data-final='category' class='drop-down' style='width:100%;' >
    <?php echo get_option_list($this, 'documents', 'select', '', array('selected'=>$this->native_session->get('documents__category') )); ?>
  </select>
</span></td></tr>

<tr><td><input type='text' id='phrase' name='phrase' placeholder='Name Search Phrase' data-final='phrase' value='<?php echo $this->native_session->get('document__phrase');?>' style='width:100%;'/></td></tr>
<tr>
  <td><input type='text' id='date' name='date' data-final='date' class='calendar clickactivated' onclick='setDatePicker()' placeholder='Posted Before' value='<?php echo $this->native_session->get('dateadded__date');?>' style='width:100%;'/></td></tr>

<tr><td><button type="button" id="applyfilterbtn" name="applyfilterbtn" class="btn blue" onClick="applyFilter('resources')" style="width:100%;">Apply Filter</button>
<input name="layerid" id="layerid" type="hidden" value="" /></td></tr>
</table>
<?php echo minify_js('apply_audit_filter', array('jquery-2.1.1.min.js', 'jquery-ui.js', 'jquery.form.js', 'jquery.datepick.js', 'pss.js', 'pss.shadowbox.js', 'pss.fileform.js','pss.datepicker.js', 'pss.pagination.js'));?>

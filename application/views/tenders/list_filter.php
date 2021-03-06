<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/external-fonts.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.shadowbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.datepicker.css" type="text/css" media="screen" />


<table class='normal-table filter-container'>
<tr><td>
<input type='text' id='by_deadline' name='by_deadline' data-final='by_deadline' class='calendar clickactivated' onclick='setDatePicker(this)' placeholder='Deadline Before' value='<?php echo $this->native_session->get('tender__by_deadline');?>' style='width:100%;'/>
</td></tr>

<tr><td><select id='search__procurementtypes' name='search__procurementtypes' data-final='procurement_type' class='drop-down' style='width:100%;'>
<?php echo get_option_list($this, 'procurementtypes', 'select', '', array('selected'=>$this->native_session->get('tender__procurement_type')));?>
</select></td></tr>

<tr><td><select id='search__procurementmethods' name='search__procurementmethods' data-final='procurement_method' class='drop-down' style='width:100%;'>
<?php echo get_option_list($this, 'procurementmethods', 'select', '', array('type'=>'public', 'selected'=>$this->native_session->get('tender__procurement_method')));?>
</select></td></tr>

<tr><td><input type="text" id="search__pdes" name="search__pdes" placeholder="Search PDE Name" data-final='pde' class="drop-down searchable clear-on-empty" data-clearfield='pde_id' value="<?php echo $this->native_session->get('tender__pde');?>" style='width:100%;'/>
<input type='hidden' name='pde_id' id='pde_id' data-final='pde_id' value='<?php echo $this->native_session->get('tender__pde_id');?>' /></td></tr>


<tr><td><input type='text' id='phrase' name='phrase' placeholder='Subject Search Phrase' data-final='phrase' value='<?php echo $this->native_session->get('tender__phrase');?>' style='width:100%;'/></td></tr>


<tr><td><button type="button" id="applyfilterbtn" name="applyfilterbtn" class="btn blue" onClick="applyFilter('tender')" style="width:100%;">Apply Filter</button>
<input name="layerid" id="layerid" type="hidden" value="" />
<?php 
if(!empty($t)){ 
	echo "<input name='status' id='status' data-final='status' type='hidden' value='published' />
	<input name='area' id='area' data-final='area' type='hidden' value='".$t."' />";
}?></td></tr>
</table>

<?php echo minify_js('tenders__list_filter', array('jquery-2.1.1.min.js', 'jquery-ui.js', 'jquery.form.js', 'jquery.datepick.js', 'pss.js', 'pss.shadowbox.js', 'pss.fileform.js', 'pss.datepicker.js', 'pss.pagination.js'));?>

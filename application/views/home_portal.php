<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo IMAGE_URL;?>favicon.ico">
    <title><?php echo SITE_TITLE.': Procurement Portal for Southern Sudan';?></title>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/external-fonts.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.list.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.shadowbox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pss.pagination.css" type="text/css" media="screen" />
</head>

<body>
<table class='body-table water-mark-bg'>
    <?php
    $this->load->view('addons/public_header');

    $this->load->view('addons/public_top_menu', array('__page'=>'home_portal'));
    ?>



    <tr>
        <td>&nbsp;</td>
        <td class='two-columns multiple' style='height:calc(85vh - 255px); padding-bottom: 20px;'>



            <div><table class='home-list-table'>
                    <tr><th class='h3 blue tender-icon'>Tender Notices</th></tr>
                    <tr><td>
                            <table class='list-tabs' data-type='paginationdiv__tender' data-page='pages/home_list'><tr>
                                    <td id='procurement_plans' class='active'>Procurement Plans</td>
                                    <td id='active_notices'>Active Notices</td>
                                    <td id='best_evaluated_bidders'>Best Evaluated Bidders</td>
                                    <td id='contract_awards'>Contract Awards</td>
                                </tr></table>
                        </td></tr>
                    <tr><td>

                            <div id='paginationdiv__tender_list' class='home-list-div'>
                                <?php $this->load->view('pages/home_list',array('type'=>'procurement_plans','list'=>$procurementPlanList));?>
                            </div>
                            <button type='button' id='refreshlist' name='refreshlist' style='display:none;'></button>
                            <div id='tender_pagination_div' class='pagination' style="margin:0px;padding:0px; display:inline-block;"><div id="tender" class="paginationdiv no-scroll"></div><input name="paginationdiv__tender_action" id="paginationdiv__tender_action" type="hidden" value="<?php echo base_url()."lists/load/t/tender";?>" />
                                <input name="paginationdiv__tender_maxpages" id="paginationdiv__tender_maxpages" type="hidden" value="<?php echo NUM_OF_LISTS_PER_VIEW;?>" />
                                <input name="paginationdiv__tender_noperlist" id="paginationdiv__tender_noperlist" type="hidden" value="<?php echo NUM_OF_ROWS_PER_PAGE;?>" />
                                <input name="paginationdiv__tender_showdiv" id="paginationdiv__tender_showdiv" type="hidden" value="paginationdiv__tender_list" />
                                <input name="paginationdiv__tender_extrafields" id="paginationdiv__tender_extrafields" type="hidden" value="" /></div>
                            <table><tr><td class='h6' width='98%'>Last Updated: <span class='dark-grey'>28/10/2015</span></td><td width='1%' class='filter-list shadowbox closable' data-url='<?php echo base_url().'tenders/home_filter/t/procurement_plans';?>'>FILTER</td><td width='1%' class='btn load-more' data-rel='tenders/index/a/procurement_plans'>MORE</td></tr></table>
                        </td></tr>
                </table></div>




            <div><table class='home-list-table'>
                    <tr><th class='h3 blue rop-icon'>Registry of Providers</th></tr>
                    <tr><td>
                            <table class='list-tabs' data-type='rop' data-page='pages/home_list'><tr>
                                    <td id='active_providers' class='active'>Active Providers</td>
                                    <td id='suspended_providers'>Suspended Providers</td>
                                </tr></table>
                        </td></tr>
                    <tr><td><div id='rop_list' class='home-list-div'><div id="provider__1">
                                    <?php $this->load->view('pages/home_list',array('type'=>'active_providers','list'=>$activeProvidersList));?>
                                </div></div><button type='button' id='refreshlist' name='refreshlist' style='display:none;'></button></td></tr>
                    <tr><td>
                            <div id='provider_pagination_div' class='pagination' style="margin:0px;padding:0px; display:inline-block;"><input name="paginationdiv__provider_action" id="paginationdiv__provider_action" type="hidden" value="<?php echo base_url()."lists/load/t/provider";?>" />
                                <input name="paginationdiv__provider_maxpages" id="paginationdiv__provider_maxpages" type="hidden" value="<?php echo NUM_OF_LISTS_PER_VIEW;?>" />
                                <input name="paginationdiv__provider_noperlist" id="paginationdiv__provider_noperlist" type="hidden" value="<?php echo NUM_OF_ROWS_PER_PAGE;?>" />
                                <input name="paginationdiv__provider_showdiv" id="paginationdiv__provider_showdiv" type="hidden" value="paginationdiv__provider_list" />
                                <input name="paginationdiv__provider_extrafields" id="paginationdiv__provider_extrafields" type="hidden" value="" /></div>
                            <table><tr><td class='h6' width='98%'>Last Updated: <span class='dark-grey'>28/10/2015</span></td><td width='1%' class='filter-list shadowbox closable' data-url='<?php echo base_url().'providers/home_filter/t/active_providers';?>'>FILTER</td><td width='1%' class='btn load-more' data-rel='providers/index/a/active_providers' >MORE</td></tr></table>
                        </td></tr>
                </table></div>




            <div><table class='home-list-table'>
                    <tr><th class='h3 blue resources-icon'>Resources</th></tr>
                    <tr><td>
                            <table class='list-tabs' data-type='resources' data-page='pages/home_list'><tr>
                                    <td id='documents' class='active'>Documents</td>
                                    <td id='important_links'>Important Links</td>
                                    <td id='standards'>Standards</td>
                                    <td id='training_activities'>Training Activities</td>
                                </tr></table>
                        </td></tr>
                    <tr><td><div id='resources_list' class='home-list-div'>
                                <?php $this->load->view('pages/home_list',array('type'=>'documents','list'=>$documentsList));?>
                            </div><button type='button' id='refreshlist' name='refreshlist' style='display:none;'></button></td></tr>
                    <tr><td>
                            <div id='resources_pagination_div' class='pagination' style="margin:0px;padding:0px; display:inline-block;"><input name="paginationdiv__resources_action" id="paginationdiv__resources_action" type="hidden" value="<?php echo base_url()."lists/load/t/resources";?>" />
                                <input name="paginationdiv__resources_maxpages" id="paginationdiv__resources_maxpages" type="hidden" value="<?php echo NUM_OF_LISTS_PER_VIEW;?>" />
                                <input name="paginationdiv__resources_noperlist" id="paginationdiv__resources_noperlist" type="hidden" value="<?php echo NUM_OF_ROWS_PER_PAGE;?>" />
                                <input name="paginationdiv__resources_showdiv" id="paginationdiv__resources_showdiv" type="hidden" value="paginationdiv__resources_list" />
                                <input name="paginationdiv__resources_extrafields" id="paginationdiv__resources_extrafields" type="hidden" value="" /></div>
                            <table><tr><td class='h6' width='98%'>Last Updated: <span class='dark-grey'>08/10/2015</span></td><td width='1%' class='filter-list shadowbox closable' data-url='<?php echo base_url().'resources/home_filter/t/documents';?>'>FILTER</td><td width='1%' class='btn load-more'  data-rel='resources/index/a/documents'>MORE</td></tr></table>
                        </td></tr>
                </table></div>




            <div><table class='home-list-table'>
                    <tr><th class='h3 blue forums-icon'>Forums</th></tr>
                    <tr><td>
                            <table class='list-tabs' data-type='forums' data-page='pages/home_list'><tr>
                                    <td id='public_forums' class='active'>Public</td>
                                    <td id='secure_forums'>Secure</td>
                                    <td id='frequently_asked_questions'>Frequently Asked Questions</td>
                                </tr></table>
                        </td></tr>
                    <tr><td><div id='forums_list' class='home-list-div'>
                                <?php $this->load->view('pages/home_list',array('type'=>'public_forums','list'=>$publicForumsList));?>
                            </div><button type='button' id='refreshlist' name='refreshlist' style='display:none;'></button></td></tr>
                    <tr><td>

                            <div id='forums_pagination_div' class='pagination' style="margin:0px;padding:0px; display:inline-block;"><div id="forums" class="paginationdiv no-scroll"></div><input name="paginationdiv__forums_action" id="paginationdiv__forums_action" type="hidden" value="<?php echo base_url()."lists/load/t/forums";?>" />
                                <input name="paginationdiv__forums_maxpages" id="paginationdiv__forums_maxpages" type="hidden" value="<?php echo NUM_OF_LISTS_PER_VIEW;?>" />
                                <input name="paginationdiv__faqsearch_noperlist" id="paginationdiv__faqsearch_noperlist" type="hidden" value="<?php echo NUM_OF_ROWS_PER_PAGE;?>" />
                                <input name="paginationdiv__forums_showdiv" id="paginationdiv__forums_showdiv" type="hidden" value="paginationdiv__forums_list" />
                                <input name="paginationdiv__forums_extrafields" id="paginationdiv__forums_extrafields" type="hidden" value="" /></div>

                            <table><tr><td class='h6' width='98%'>Last Updated: <span class='dark-grey'>08/10/2015</span></td><td width='1%' class='filter-list shadowbox closable' data-url='<?php echo base_url().'forums/home_filter/t/public_forums';?>'>FILTER</td><td width='1%' class='btn load-more'  data-rel='forums/index/a/public_forums'>MORE</td></tr></table>
                        </td></tr>
                </table></div>








        </td>
        <td>&nbsp;</td>
    </tr>

    <?php $this->load->view('addons/public_footer');?>

</table>
<?php echo minify_js('home_portal', array('jquery-2.1.1.min.js', 'jquery-ui.js', 'jquery.form.js', 'pss.js', 'pss.shadowbox.js', 'pss.list.js', 'pss.pagination.js'));?>
</body>
</html>
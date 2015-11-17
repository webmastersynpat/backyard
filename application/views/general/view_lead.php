<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable.js"></script>

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-bootstrap.js"></script>

<script type="text/javascript" src="<?php echo $Layout->baseUrl; ?>public/widgets/datatable/datatable-tabletools.js"></script>



<script type="text/javascript">

	jQuery(document).ready(function() {

        jQuery('#manageLeads').DataTable( {

            "paging": false

        });

    });

</script>



<div id="page-title">

    <h2>View Leads</h2>

    <p></p>

</div>

<div class="panel">

    <div class="panel-body">

		<div class="example-box-wrapper">
            
            <?php
            foreach($lead_list as $lead_list)
            {
            if($lead_list->type=="Litigation")
            {
                ?>
                <table class="table">
                    <tr>
                        <td>
                            <strong>Case Name</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->case_name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>No of patents</strong> 
                        </td>
                        <td>
                            <?php  echo $lead_list->no_of_patent; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Case Number</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->case_number; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Filling Date</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->filling_date; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong>Active Defendants</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->active_defendants; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Litigation Stage</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->litigation_stage; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>No of original defandant</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->original_defendants; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Market Industry</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->market_industry; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Plaintiff's Name</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->plantiffs_name; ?>
                        </td>
                    </tr>
                </table>
                <?php
                 
            }
            if($lead_list->type=="Market")
            {
                ?>
                <table class="table">
                    <tr>
                        <td>
                           <strong>Seller</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->plantiffs_name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>No of Prospects</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->no_of_prospects; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Expected Price</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->expected_price; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Technologies/Markets</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->market_industry; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong>Prospect Name</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->prospects_name; ?>
                        </td>
                    </tr>
                </table>
                <?php
            }
            if($lead_list->type=="Proactive General")
            {
                ?>
                <table class="table">
                    <tr>
                        <td>
                            <strong>Owner</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->plantiffs_name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>No of patents </strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->no_of_patent; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Relates To</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->relates_to; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Person Name1</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->person_name_1; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong>Person Title1</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->person_title_1; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong>Person Name2</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->person_name_2; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Person Title2</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->person_title_2; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Person Name3</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->person_name_3; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Person Title3</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->person_title_3; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Address</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->address; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Portfolio Number</strong>
                        </td>
                        <td>
                            <?php  echo $lead_list->portfolio_number; ?>
                        </td>
                    </tr>
                </table>
                <?php
            }}
            ?>
		

		</div>

	</div>

</div>


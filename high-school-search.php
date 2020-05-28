<?php

const UNITED_STATES_COUNTRY_CODE = 224;

$pdo = new PDO("mysql:host=" . $_SESSION['db_host'] . ";dbname=" . $_SESSION['db_name'] . ";charset=utf8", $_SESSION['db_user'], $_SESSION['db_pass']);
$stmt = $pdo->prepare('SELECT id,state as `name`,code FROM states WHERE country_id = :country ORDER BY state ASC');
$stmt->bindValue(':country', UNITED_STATES_COUNTRY_CODE);
$stmt->execute();

//$_SESSION['school_type'] = 'high-school';
?>

<style type="text/css">
    .bg-mantis {
        background: -moz-linear-gradient(90deg, #42b574 0%, #84c450 100%);
        background: -webkit-linear-gradient(90deg, #42b574 0%, #84c450 100%);
        background: -o-linear-gradient(90deg, #42b574 0%, #84c450 100%);
        background: -ms-linear-gradient(90deg, #42b574 0%, #84c450 100%);
        background: linear-gradient(90deg, #42b574 0%, #84c450 100%);
        color: #FFF;
    }
    .bg-mantis-link {
        color: #FFF;
    }
	.bg-mantis2 {
		color: #000;
	}
</style>

<div class="row align-items-start bg-mantis" style="margin-right: -21%; padding: 1rem 2.5rem;">
    <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6 offset-lg-1 margin-top-lg">
		<h4>High School Search</h4>
        <h5>Please Select your State, then County, then School</h5>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 pb-3">
        <select name="hs-state-id" id="hs-state-id" class="form-control" onchange="HSStateSelect();">
            <option value="false">Select a State ...</option>
            <?php
				if( $stmt->rowCount() > 0 ) {
					while ($row = $stmt->fetch(PDO::FETCH_OBJ))
					{
						echo '<option value="'.$row->id.'">'.$row->name.'</option>';
					}
				}
			?>
        </select>
        <div>&nbsp;</div>
        <div class="progress " id="hs-county-loading-progress" style="display: none; height: 2em;">
            <div class="progress-bar bg-secondary progress-bar-striped progress-bar-animated " role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <div style="white-space: nowrap">Searching <span id="hs-state-name-target"></span> for Counties</div>
            </div>
        </div>
		<select name="hs-county-id" id="hs-county-id" class="form-control" style="display: none;" onchange="HSCountySelect();">
			
        </select>
        <div>&nbsp;</div>
        <div class="progress " id="hs-school-loading-progress" style="display: none; height: 2em;">
            <div class="progress-bar bg-secondary progress-bar-striped progress-bar-animated " role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <div style="white-space: nowrap">Searching <span id="hs-county-name-target"></span> for Schools</div>
            </div>
        </div>
		<select name="hs-school-id" id="hs-school-id" class="form-control" style="display: none;" onchange="HSSchoolSelect();">
			
        </select>
		<div class="progress margin-top-md" id="hs-loading-progress" style="display: none; height: 2em;">
            <div class="progress-bar bg-secondary progress-bar-striped progress-bar-animated " role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <div style="white-space: nowrap">Retrieving your School's information</div>
            </div>
        </div>
	</div>
	<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 pb-1">
        <a id="find-order-button" class="btn btn-light" href="/?site=pages&page=find_order" data-toggle="tooltip"
           data-placement="top" title="">
            Click to resume or find a previous order.
        </a>
    </div>
    <div class="col-9 offset-lg-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="hs-school-search-error"
             style="display:none;">
            Error Div
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-lg-6 offset-1 offset-sm-1 offset-lg-3 text-center">
        <a
                href="JavaScript:void(0);"
                title="College Search" class="siteButton" onclick="SwitchSearch('College');">Click Here to Search for College Graduation</a>
        <div class="margin-top-md"><strong>Need help finding your school</strong> <a id="help-text" onclick="helpText('Find High School Help','find_high_school.html');" data-toggle="modal" data-target="#descriptionModalCenter"><i class="fa fa-question-circle" style=""></i></a></div>
    </div>
</div>
<script type="text/javascript">
function HSStateSelect() {
	jQuery('#hs-county-id').hide();
    jQuery('#county-search-error').hide();
    jQuery('#hs-county-loading-progress').show();
    jQuery('#hs-state-name-target').text($('#hs-state-id option:selected').text());
	jQuery.ajax({
        url: 'includes/Counties.php',
        method: 'POST',
        data: {state_id:jQuery('#hs-state-id option:selected').val()},
        success: StateSelectCallbackSuccess,
        error: StateSelectCallbackError,
    });
}
function StateSelectCallbackSuccess(data) {
	var sel = $('#hs-county-id');
    sel.empty();
	jQuery('#hs-county-id').html(data);
    jQuery('#hs-county-loading-progress').hide();
    sel.fadeIn(200);
}
function StateSelectCallbackError(resp) {
    jQuery('#hs-school-search-error').show();
	jQuery('#hs-school-search-error').html('There was an unknown error processing your State request. Please refresh the page and try again.')
    console.error('There was an unknown error processing your request');
}
function HSCountySelect() {
	jQuery('#hs-school-id').hide();
    jQuery('#hs-school-search-error').hide();
    jQuery('#hs-school-loading-progress').show();
    jQuery('#hs-county-name-target').text($('#hs-county-id option:selected').text());
	jQuery.ajax({
        url: 'includes/High-Schools.php',
        method: 'POST',
        data: {hs_county_id:jQuery('#hs-county-id option:selected').val()},
        success: CountySelectCallbackSuccess,
        error: CountySelectCallbackError,
    });
}
function CountySelectCallbackSuccess(data) {
	var sel = $('#hs-school-id');
    sel.empty();
	jQuery('#hs-school-id').html(data);
    jQuery('#hs-school-loading-progress').hide();
    sel.fadeIn(200);
}
function CountySelectCallbackError(resp) {
	jQuery('#hs-school-search-error').show();
	jQuery('#hs-school-search-error').html('There was an unknown error processing your County request. Please refresh the page and try again.')
    console.error('There was an unknown error processing your request');
}
function HSSchoolSelect() {
	jQuery('#hs-loading-progress').show();
	jQuery.ajax({
		url: '/core/HighSchoolSearch.php',
		type: 'POST',
		data: {ls_query:jQuery('#hs-school-id option:selected').val()},
		success: SchoolSelectCallbackSuccess,
		error: SchoolSelectCallbackError
	});
}
function SchoolSelectCallbackSuccess(resp) {
	window.location.replace("/index.php?site=graduation&page=home");
}
function SchoolSelectCallbackError(resp) {
	jQuery('#hs-school-search-error').show();
	jQuery('#hs-school-search-error').html('There was an unknown error processing your High School request. Please refresh the page and try again.')
    console.error('There was an unknown error processing your request');
}
</script>
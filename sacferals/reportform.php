<?php
	session_start();
	include('authenticate.php');
	$link = connectdb($host, $user, $pass, $db);
?>

<script type="text/javascript">
function displayformdiv(){
	var formdiv = document.getElementById("formdiv");
	var formstatus = formdiv.style.display;
	if(formstatus=="none") formdiv.style.display="block";
	else formdiv.style.display="none";
}

function formatPhone(phoneId) {
	var startCursor = $("#"+phoneId).get(0).selectionStart;
	var	endCursor = $("#"+phoneId).get(0).selectionEnd;
	
	var output;	
    var input = $("#"+phoneId).val();
	input = input.replace(/[^0-9]/g, '');
	var area = input.substr(0, 3);
    var pre = input.substr(3, 3);
    var tel = input.substr(6, 4);
	
	if (input.length >= 10){
		output = input.replace(/^(\d{3})(\d{3})(\d{4})+$/, "($1)$2-$3");		
	} else {
		output = input;
	}
	
	$("#"+phoneId).val(output);
	$("#"+phoneId).get(0).setSelectionRange(startCursor, endCursor);
}
</script>

<?php

if(isset($_POST['submitcolony'])) //this processes after user submits data.
{
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$phone1 = $_POST['phone1'];
	$phone2 = $_POST['phone2'];	
	
	$caregiver = $_POST['caregiver'];
	$feederdescription = $_POST['feederdescription'];
	$colonystreet = $_POST['colonystreet'];
	$city = $_POST['city'];
	$county = $_POST['county'];
	$zipcode = $_POST['zipcode'];
	$trapattempt = $_POST['trapattempt'];
	$numberofcats = $_POST['numberofcats'];
	$kittens = $_POST['kittens'];
	$injured = $_POST['recentlyinjured'];
	$injurydescription = $_POST['injurydescription'];
	$friendlypet = $_POST['friendlypet'];
	$setting = $_POST['setting'];
	$comments = $_POST['comments'];
	
	// Required field names
	// this line should be used, since the 'required' attribute isn't supported in all web browsers
	$required = array('firstname','email','colonystreet','zipcode','city','numberofcats','county');

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) 
	{
		if (empty($_POST[$field])) 
		{
			$error = true;
		}
	}
	
	//re's need updating for all fields. or we can use javascript (better)
	$re = "/^[a-zA-Z]+(([\'\- ][a-zA-Z])?[a-zA-Z]*)*$/";
	list($first, $last) = split(" ", $fullname, 2);
	
	//if user passes re test
	if(!$error)
	{
		if(preg_match($re, $first) && preg_match($re, $last))
		{	//no need to check for duplicates
			$query = "insert into ReportColonyForm values('', '', '', '', Now(), '$fullname', '$email', '$phone1', '$phone2', 
			'$colonystreet', '$city', '$county', '$zipcode', '$trapattempt[0]', '$numberofcats', '$kittens[0]',
			'$caregiver[0]', '$feederdescription', '$injured[0]', '$injurydescription', '$friendlypet[0]', '$setting[0]', '$comments', '', '', '', '', '', '')";
	
			mysqli_query($link, $query); //link query to database
			echo "<script type='text/javascript'> document.location = 'formsubmitted.php'; </script>";
		}
		else
		{
			//print "You did not fill out the form correctly!";
			$result='<div style="padding-bottom:10px">
						<div class="alert">
							<span class="closebtn" onclick="this.parentElement.style.display='."'none'".';">&times;</span>
							"'.$fullname.'" is not a valid name.</div></div>';
		}
	}
	else
	{
		$result='<div style="padding-bottom:10px">
					<div class="alert">
						<span class="closebtn" onclick="this.parentElement.style.display='."'none'".';">&times;</span>
						<b>ERROR!!</b> Please fill out all fields</div></div>';
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>	
	<title>Report Colony/Caregiver Registration</title>
	<style type="text/css">
	body 
	{
		background-color: powderblue;
	}
	form
	{
		margin: auto;
		background-color: white;
		padding: 1em;    
		border-color: #0b61a4;
		border-style: solid;
		border-width: 1px;
		min-width: 580px;
		width: 70%;
	}
	h2
	{
		margin: auto;
		margin-top: 0px;
		margin-bottom: 10px;
		color: white;
		background-color: #0b61a4;
		margin-bottom: 0;
		padding: 14;    
		border-color: #0b61a4;
		border-style: solid;
		border-width: 3px;
		
		min-width: 580px;
		width: 70%;
		font-family: Arial,sans-serif;
	}
	.fieldset-auto-width 
	{
		display: inline-block;
	}
	.todisplay 
	{
		display:none;
	}
	.indent
	{
		padding-left: 2em;
	}

	/*error msg*/
	.alert {
	    padding: 20px;
	    background-color: #f44336; /* Red */
	    color: white;
	    margin-bottom: 15px;
	}
	.closebtn {
	    margin-left: 15px;
	    color: white;
	    font-weight: bold;
	    float: right;
	    font-size: 22px;
	    line-height: 20px;
	    cursor: pointer;
	    transition: 0.3s;
	}
	.closebtn:hover {
	    color: black;
	}

	.tooltip {
	    position: relative;
	    display: inline-block;
	}
	.tooltip .tooltiptext {
	    visibility: hidden;
	    width: auto;
	    white-space: nowrap;
	    background-color: #0b61a4;
	    color: #fff;
	    text-align: left;
	    border-radius: 5px;
	    padding: 10px 15px;
	    font-size: 14px;
	    
	    /* Position the tooltip */
	    position: absolute;
	    z-index: 1;
	    top: -5px;
	    left: 150%;
	}
	.tooltip:hover .tooltiptext {
	    visibility: visible;
	}
	</style>
	
	<!-- This must preceed any code that uses JQuery. It links out to that library so you can use it -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="script.js">
	</script> 
</head>
<body>

	<?php echo $result; ?>

	<h2> Report a Feral Cat Colony & Get TNR Assistance </h2>
	<form method="post" action="reportform.php" id='reportform'>
	<p>Thank you for providing information about a feral cat colony 
	   (a "colony" is a term used to describe a group of cats living together.)</p>
	<p>Please answer the questions below to the best of your ability.  The more 
	   information you provide, the more help we will be able to provide.  It's 
	   important that we are able to get in contact with you regarding this request.  
	   Without your name and contact information, it is unlikely we will be able to 
	   assist the colony you are reporting.</p>

	<input type="checkbox" onclick="displayformdiv()"> I have read all the information required for filling out this form.</input><br>

	<div id="formdiv" style="display: none; padding: 0 4vw">
		<br><b><small><font color="red">* Required Fields</font></small></b><br><br>
		<b>*Full Name</b><br>
		<input type="text" name="fullname" id="fullname" required><br><br>
		<b>*Email Address</b>
		<i class="tooltip"><img src="images/blue_question_mark.png" height="13px"/>
			<span class="tooltiptext">This is our preferred method of contact.</span>
		</i><br>
		<input type="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$" placeholder="email@domain.com" required><br><br>
		<b>Primary Phone</b><br>
		<input type="tel" id="phone1" name="phone1" placeholder="1234567890" pattern=".{10,13}" maxlength="10" onkeyup="formatPhone('phone1');" /><br><br>
		<b>Secondary Phone</b><br>
		<input type="tel" id="phone2" name="phone2" placeholder="1234567890" pattern=".{10,13}" maxlength="10" onkeyup="formatPhone('phone2');" /><br><br>

		<b>Are you the primary caregiver/feeder?</b><br>	
		<input type="radio" name="caregiver[]" value="Yes" onclick="displayForm(this)"> Yes<br>
		<input type="radio" name="caregiver[]" value="No" onclick="displayForm(this)"> No<br><br>
		<div class='indent todisplay' id="feederID">
			<b>Please clarify who is feeding it.</b><br>
			<textarea rows="4" cols="50" name="feederdescription"></textarea><br><br>
		</div>
		
		<b>*Address of Cat Colony</b><br>
		<input type="text" name="colonystreet" id="colonystreet" required><br><br>
		<b>*Zip Code</b><br>
		<input type="text" name="zipcode" id="zipcode" maxlength="5" required><span id="ziperror"></span><br><br>
		<b>*City</b><br>
		<span id="city_wrap"><input type="text" name="city" id="city" required></span><br><br>
		<b>County</b><br>
		<input type="text" name="county" id="county" readonly><br><br>
		<b>State</b><br>
		<input type="text" value="CA" readonly><br><br>
		
		<b>Has trapping been attempted or are any of the cats' ears tipped?</b>
		<i class="tooltip"><img src="images/blue_question_mark.png" height="13px"/>
			<span class="tooltiptext">If the cat has the tip of one ear cut off or "tipped", this <br>means this
				cat has already been trapped and is altered. <br>Release this cat immediately.</span>
		</i><br>
		<input type="radio" name="trapattempt[]" value="Yes" id="trapattemtyes"> Yes<br>
		<input type="radio" name="trapattempt[]" value="No" id="trapattemptno"> No<br><br>
		
		<b>*Approx # of Cats (including Kittens)</b><br>
		<input type="number" name="numberofcats" min="1" max="99" id="numberofcats" required><br><br>
		<b>If there are kittens, are they under 8 weeks old and nursing?</b>
		<i class="tooltip"><img src="images/blue_question_mark.png" height="13px"/>
			<span class="tooltiptext">Kitten Description<br>
			Will be provided by SacFerals later.</span>
		</i><br>
		<input type="radio" name="kittens[]" value="Yes" id="kittensyes"> Yes<br>
		<input type="radio" name="kittens[]" value="No" id="kittensno"> No<br><br>
		
		<b>Injured or Pregnant Cats?</b>
		<i class="tooltip"><img src="images/blue_question_mark.png" height="13px"/>
			<span class="tooltiptext">Signs of injured cats can include inflammation/swelling, <br>limping, 
				rapid breathing or other signs of stress, and blood.<br>
				Signs of a pregnant cat can include nesting <br> activities, vomiting,
				and an enlarged abdomen.</span>
		</i><br>
		<input type="radio" name="recentlyinjured[]" value="Yes" id="recentlyinjuredinjuredyes" onClick="displayForm(this)"> Yes<br>
		<input type="radio" name="recentlyinjured[]" value="No" id="recentlyinjuredinjuredno" onClick="displayForm(this)"> No<br><br>
		<div class='indent todisplay' id="recentlyinjuredID">
			<b>Describe Condition</b><br>
			<textarea rows="4" cols="50" name="injurydescription"></textarea><br><br>
		</div>
		
		<b>Is the cat friendly or a pet?</b><br>
		<input type="radio" name="friendlypet[]" value="Yes" id="friendlypetyes"> Yes<br>
		<input type="radio" name="friendlypet[]" value="No" id="friendlypetno"> No<br><br>
		
		<b>What is the setting of this colony?</b><br>
		<input type="radio" name="setting[]" value="Residential" id="residentialsetting"> Residential<br>
		<input type="radio" name="setting[]" value="Commercial" id="commercialsetting"> Commercial<br>
		<input type="radio" name="setting[]" value="Industrial"id="industrialsetting"> Industrial<br><br>

		<b>Additional Comments</b><br>
		<textarea rows="4" cols="50" name="comments"></textarea><br><br>
		
		<input type="submit" name="submitcolony" value="Submit"  > <!-- button itself -->
	</div>
</form>
<br>

<script>
//when user clicks off of the zip field:
$(document).ready(function(){
	$('#zipcode').keyup(function(){
		if($(this).val().length==5){
			var zip = $(this).val();
			var city = '';
			var county = '';
			var state = '';
			
			//make a request to the google geocode api
			$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='
					+zip+'&key=AIzaSyDz2ZSC6IJEf38QeSbLwIxTEohm4ATem9M').success(function(response){
				//find the city and county
				var address_components = response.results[0].address_components;
				$.each(address_components, function(index, component){
					var types = component.types;
					$.each(types, function(index, type){
						if(type == 'locality') city = component.long_name;
						if(type == 'administrative_area_level_2') county = component.long_name;
						if(type == 'administrative_area_level_1') state = component.short_name;
					});
				});
				if(state=='CA'){
					$('#zipcode').attr('style','');
					$('#ziperror').html('');
					//pre-fill the city and state
					var cities = response.results[0].postcode_localities;
					if(cities){
						//turn city into a dropdown if necessary
						var $select = $(document.createElement('select'));
						console.log(cities);
						$.each(cities, function(index,locality){
							var $option = $(document.createElement('option'));
							$option.html(locality);
							$option.attr('value',locality);
							if(city == locality) $option.attr('selected','selected');
							$select.append($option);
						});
						$select.attr('id','city');
						$select.attr('name','city');
						$('#city_wrap').html($select);
					}
					else {
						$('#city_wrap').html('<input type="text" name="city" id="city">');
						$('#city').val(city);
					}
					$('#county').val(county);
				}
				else{
					$('#zipcode').attr('style','border: 1px solid #d66');
					$('#ziperror').attr('style','color: RED');
					$('#ziperror').html(' Must be in California');
					$('#city').val('');
					$('#county').val('');
				}
			});
		}
	});
});
</script>

</body>
</html>

<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    header('Location: /beta/login.php');
    exit;
}
require_once(__DIR__.'/vendor/loader.php');
require_once(__DIR__.'/mailchimp/MailChimp.php');

use \DrewM\MailChimp\MailChimp;


if (isset($_POST['state'])) {
    $db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);
    if ($_POST['c_review'] == '1') $_POST['c_review'] = 1;
    else  $_POST['c_review'] = 0;
    $_POST['u_creator_id']=$user_id;

    $val_email = $db->select("*")->from("data")->where('email = ?', $_POST['email'])->execute()->fetchCollection()[0];

    if($val_email){
        $error = '<div class="ui message"><i class="close icon"></i>You can\'t add this client, they are already assigned to another agent.</div>';
    }
    else{
        $db->insert("data", $_POST);

        $MailChimp = new MailChimp('acf1079b00451144e6f1fe3594015a9d-us7');

        $list_id = '120763049c';

        $result = $MailChimp->post("lists/$list_id", array(
            "members" => array(
                array(
                    'email_address' => $_POST['email'],
                    'status'        => 'subscribed',
                    'merge_fields'  =>  array(
                        'NAME' => $_POST['name'],
                        'LAST_NAME' => $_POST['last_name'],
                        'ADDRESS' => $_POST['address'],
                        'CITY' => $_POST['city'],
                        'COUNTRY' => $_POST['country'],
                        'ZIP' => $_POST['zip'],
                        'STATE' => $_POST['state'],
                        'B_MONTH' => $_POST['b_month'],
                        'B_DAY' => $_POST['b_day'],
                        'P_TYPE' => $_POST['p_type'],
                        'P_VALUE' => $_POST['p_value'],
                        'NOTE' => $_POST['note'],
                        'C_REVIEW' => ($_POST['c_review'])? "1" : "0",
                        'C_BIRTHDAY' => ($_POST['c_birthday'])? "1" : "0",
                        'C_HOLIDAYS' => ($_POST['c_holidays'])? "1" : "0",
                        'C_TEXT_INTRO' => $_POST['c_email_intro_text'],
                        'C_CREATOR' => $user_id
                    )
                )
            ),
            'update_existing'  => true
        ));
        header('Location: /beta/list.php');

    }



}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Scavolini</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/icon.min.css">
	<link rel="stylesheet" href="css/semantic.min.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="#" type="image/x-icon">
</head>
<body>
<div class="wrapper">
<main>
	<a href = "index.php"> <img src="img/logo-small.jpg" alt="" class="logo-2"> </a>
	<br>
	<div class="ui one column stackable center aligned page grid">
		<h2 class="ui header">Add new contact</h2>
	</div>
    <?php
        if($error){
            echo $error;
        }
    ?>
	<form class="ui big form" method="post">
		<div class="field">
			<label>Name</label>
			<input type="text" name="name" placeholder="Insert customer's name">
		</div>
		<div class="field">
			<label>Surname</label>
			<input type="text" name="last_name" placeholder="Insert customer's surname">
		</div>
		<div class="field">
			<label>email</label>
			<input type="email" name="email" placeholder="Insert customer's email">
		</div>
        <div class="field">
            <label>Country</label>
            <select class="ui fluid dropdown" name="country">
                <option value="">Select country</option>
                <option value="1">United States</option>
                <option value="2">United Kingdom</option>
                <option value="3">Afghanistan</option>
                <option value="4">Albania</option>
                <option value="5">Algeria</option>
                <option value="6">American Samoa</option>
                <option value="7">Andorra</option>
                <option value="8">Angola</option>
                <option value="9">Anguilla</option>
                <!--					<option value="Antarctica">Antarctica</option>
                          <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                          <option value="Argentina">Argentina</option>
                          <option value="Armenia">Armenia</option>
                          <option value="Aruba">Aruba</option>
                          <option value="Australia">Australia</option>
                          <option value="Austria">Austria</option>
                          <option value="Azerbaijan">Azerbaijan</option>
                          <option value="Bahamas">Bahamas</option>
                          <option value="Bahrain">Bahrain</option>
                          <option value="Bangladesh">Bangladesh</option>
                          <option value="Barbados">Barbados</option>
                          <option value="Belarus">Belarus</option>
                          <option value="Belgium">Belgium</option>
                          <option value="Belize">Belize</option>
                          <option value="Benin">Benin</option>
                          <option value="Bermuda">Bermuda</option>
                          <option value="Bhutan">Bhutan</option>
                          <option value="Bolivia">Bolivia</option>
                          <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                          <option value="Botswana">Botswana</option>
                          <option value="Bouvet Island">Bouvet Island</option>
                          <option value="Brazil">Brazil</option>
                          <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                          <option value="Brunei Darussalam">Brunei Darussalam</option>
                          <option value="Bulgaria">Bulgaria</option>
                          <option value="Burkina Faso">Burkina Faso</option>
                          <option value="Burundi">Burundi</option>
                          <option value="Cambodia">Cambodia</option>
                          <option value="Cameroon">Cameroon</option>
                          <option value="Canada">Canada</option>
                          <option value="Cape Verde">Cape Verde</option>
                          <option value="Cayman Islands">Cayman Islands</option>
                          <option value="Central African Republic">Central African Republic</option>
                          <option value="Chad">Chad</option>
                          <option value="Chile">Chile</option>
                          <option value="China">China</option>
                          <option value="Christmas Island">Christmas Island</option>
                          <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                          <option value="Colombia">Colombia</option>
                          <option value="Comoros">Comoros</option>
                          <option value="Congo">Congo</option>
                          <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                          <option value="Cook Islands">Cook Islands</option>
                          <option value="Costa Rica">Costa Rica</option>
                          <option value="Cote D'ivoire">Cote D'ivoire</option>
                          <option value="Croatia">Croatia</option>
                          <option value="Cuba">Cuba</option>
                          <option value="Cyprus">Cyprus</option>
                          <option value="Czech Republic">Czech Republic</option>
                          <option value="Denmark">Denmark</option>
                          <option value="Djibouti">Djibouti</option>
                          <option value="Dominica">Dominica</option>
                          <option value="Dominican Republic">Dominican Republic</option>
                          <option value="Ecuador">Ecuador</option>
                          <option value="Egypt">Egypt</option>
                          <option value="El Salvador">El Salvador</option>
                          <option value="Equatorial Guinea">Equatorial Guinea</option>
                          <option value="Eritrea">Eritrea</option>
                          <option value="Estonia">Estonia</option>
                          <option value="Ethiopia">Ethiopia</option>
                          <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                          <option value="Faroe Islands">Faroe Islands</option>
                          <option value="Fiji">Fiji</option>
                          <option value="Finland">Finland</option>
                          <option value="France">France</option>
                          <option value="French Guiana">French Guiana</option>
                          <option value="French Polynesia">French Polynesia</option>
                          <option value="French Southern Territories">French Southern Territories</option>
                          <option value="Gabon">Gabon</option>
                          <option value="Gambia">Gambia</option>
                          <option value="Georgia">Georgia</option>
                          <option value="Germany">Germany</option>
                          <option value="Ghana">Ghana</option>
                          <option value="Gibraltar">Gibraltar</option>
                          <option value="Greece">Greece</option>
                          <option value="Greenland">Greenland</option>
                          <option value="Grenada">Grenada</option>
                          <option value="Guadeloupe">Guadeloupe</option>
                          <option value="Guam">Guam</option>
                          <option value="Guatemala">Guatemala</option>
                          <option value="Guinea">Guinea</option>
                          <option value="Guinea-bissau">Guinea-bissau</option>
                          <option value="Guyana">Guyana</option>
                          <option value="Haiti">Haiti</option>
                          <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                          <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                          <option value="Honduras">Honduras</option>
                          <option value="Hong Kong">Hong Kong</option>
                          <option value="Hungary">Hungary</option>
                          <option value="Iceland">Iceland</option>
                          <option value="India">India</option>
                          <option value="Indonesia">Indonesia</option>
                          <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                          <option value="Iraq">Iraq</option>
                          <option value="Ireland">Ireland</option>
                          <option value="Israel">Israel</option>
                          <option value="Italy">Italy</option>
                          <option value="Jamaica">Jamaica</option>
                          <option value="Japan">Japan</option>
                          <option value="Jordan">Jordan</option>
                          <option value="Kazakhstan">Kazakhstan</option>
                          <option value="Kenya">Kenya</option>
                          <option value="Kiribati">Kiribati</option>
                          <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                          <option value="Korea, Republic of">Korea, Republic of</option>
                          <option value="Kuwait">Kuwait</option>
                          <option value="Kyrgyzstan">Kyrgyzstan</option>
                          <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                          <option value="Latvia">Latvia</option>
                          <option value="Lebanon">Lebanon</option>
                          <option value="Lesotho">Lesotho</option>
                          <option value="Liberia">Liberia</option>
                          <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                          <option value="Liechtenstein">Liechtenstein</option>
                          <option value="Lithuania">Lithuania</option>
                          <option value="Luxembourg">Luxembourg</option>
                          <option value="Macao">Macao</option>
                          <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                          <option value="Madagascar">Madagascar</option>
                          <option value="Malawi">Malawi</option>
                          <option value="Malaysia">Malaysia</option>
                          <option value="Maldives">Maldives</option>
                          <option value="Mali">Mali</option>
                          <option value="Malta">Malta</option>
                          <option value="Marshall Islands">Marshall Islands</option>
                          <option value="Martinique">Martinique</option>
                          <option value="Mauritania">Mauritania</option>
                          <option value="Mauritius">Mauritius</option>
                          <option value="Mayotte">Mayotte</option>
                          <option value="Mexico">Mexico</option>
                          <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                          <option value="Moldova, Republic of">Moldova, Republic of</option>
                          <option value="Monaco">Monaco</option>
                          <option value="Mongolia">Mongolia</option>
                          <option value="Montserrat">Montserrat</option>
                          <option value="Morocco">Morocco</option>
                          <option value="Mozambique">Mozambique</option>
                          <option value="Myanmar">Myanmar</option>
                          <option value="Namibia">Namibia</option>
                          <option value="Nauru">Nauru</option>
                          <option value="Nepal">Nepal</option>
                          <option value="Netherlands">Netherlands</option>
                          <option value="Netherlands Antilles">Netherlands Antilles</option>
                          <option value="New Caledonia">New Caledonia</option>
                          <option value="New Zealand">New Zealand</option>
                          <option value="Nicaragua">Nicaragua</option>
                          <option value="Niger">Niger</option>
                          <option value="Nigeria">Nigeria</option>
                          <option value="Niue">Niue</option>
                          <option value="Norfolk Island">Norfolk Island</option>
                          <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                          <option value="Norway">Norway</option>
                          <option value="Oman">Oman</option>
                          <option value="Pakistan">Pakistan</option>
                          <option value="Palau">Palau</option>
                          <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                          <option value="Panama">Panama</option>
                          <option value="Papua New Guinea">Papua New Guinea</option>
                          <option value="Paraguay">Paraguay</option>
                          <option value="Peru">Peru</option>
                          <option value="Philippines">Philippines</option>
                          <option value="Pitcairn">Pitcairn</option>
                          <option value="Poland">Poland</option>
                          <option value="Portugal">Portugal</option>
                          <option value="Puerto Rico">Puerto Rico</option>
                          <option value="Qatar">Qatar</option>
                          <option value="Reunion">Reunion</option>
                          <option value="Romania">Romania</option>
                          <option value="Russian Federation">Russian Federation</option>
                          <option value="Rwanda">Rwanda</option>
                          <option value="Saint Helena">Saint Helena</option>
                          <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                          <option value="Saint Lucia">Saint Lucia</option>
                          <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                          <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                          <option value="Samoa">Samoa</option>
                          <option value="San Marino">San Marino</option>
                          <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                          <option value="Saudi Arabia">Saudi Arabia</option>
                          <option value="Senegal">Senegal</option>
                          <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                          <option value="Seychelles">Seychelles</option>
                          <option value="Sierra Leone">Sierra Leone</option>
                          <option value="Singapore">Singapore</option>
                          <option value="Slovakia">Slovakia</option>
                          <option value="Slovenia">Slovenia</option>
                          <option value="Solomon Islands">Solomon Islands</option>
                          <option value="Somalia">Somalia</option>
                          <option value="South Africa">South Africa</option>
                          <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                          <option value="Spain">Spain</option>
                          <option value="Sri Lanka">Sri Lanka</option>
                          <option value="Sudan">Sudan</option>
                          <option value="Suriname">Suriname</option>
                          <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                          <option value="Swaziland">Swaziland</option>
                          <option value="Sweden">Sweden</option>
                          <option value="Switzerland">Switzerland</option>
                          <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                          <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                          <option value="Tajikistan">Tajikistan</option>
                          <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                          <option value="Thailand">Thailand</option>
                          <option value="Timor-leste">Timor-leste</option>
                          <option value="Togo">Togo</option>
                          <option value="Tokelau">Tokelau</option>
                          <option value="Tonga">Tonga</option>
                          <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                          <option value="Tunisia">Tunisia</option>
                          <option value="Turkey">Turkey</option>
                          <option value="Turkmenistan">Turkmenistan</option>
                          <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                          <option value="Tuvalu">Tuvalu</option>
                          <option value="Uganda">Uganda</option>
                          <option value="Ukraine">Ukraine</option>
                          <option value="United Arab Emirates">United Arab Emirates</option>
                          <option value="United Kingdom">United Kingdom</option>
                          <option value="United States">United States</option>
                          <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                          <option value="Uruguay">Uruguay</option>
                          <option value="Uzbekistan">Uzbekistan</option>
                          <option value="Vanuatu">Vanuatu</option>
                          <option value="Venezuela">Venezuela</option>
                          <option value="Viet Nam">Viet Nam</option>
                          <option value="Virgin Islands, British">Virgin Islands, British</option>
                          <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                          <option value="Wallis and Futuna">Wallis and Futuna</option>
                          <option value="Western Sahara">Western Sahara</option>
                          <option value="Yemen">Yemen</option>
                          <option value="Zambia">Zambia</option>
                          <option value="Zimbabwe">Zimbabwe</option>-->
            </select>
        </div>
		<div class="field">
			<label>Address</label>
			<input type="text" name="address" placeholder="Insert customer's address">
		</div>
		<div class="field">
			<label>City</label>
			<input type="text" name="city" placeholder="Insert customer's city">
		</div>

		<div class="field">
			<label>ZIP</label>
			<input type="text" name="zip" placeholder="Insert customer's zip">
		</div>
			<div class="field">
				<label>State</label>
				<select class="ui fluid dropdown" name="state">
					<option value="">Select state</option>
					<option value="AL">Alabama</option>
					<option value="AK">Alaska</option>
					<option value="AZ">Arizona</option>
					<option value="AR">Arkansas</option>
					<option value="CA">California</option>
					<option value="CO">Colorado</option>
					<option value="CT">Connecticut</option>
					<option value="DE">Delaware</option>
					<option value="DC">District Of Columbia</option>
					<option value="FL">Florida</option>
					<option value="GA">Georgia</option>
					<option value="HI">Hawaii</option>
					<option value="ID">Idaho</option>
					<option value="IL">Illinois</option>
					<option value="IN">Indiana</option>
					<option value="IA">Iowa</option>
					<option value="KS">Kansas</option>
					<option value="KY">Kentucky</option>
					<option value="LA">Louisiana</option>
					<option value="ME">Maine</option>
					<option value="MD">Maryland</option>
					<option value="MA">Massachusetts</option>
					<option value="MI">Michigan</option>
					<option value="MN">Minnesota</option>
					<option value="MS">Mississippi</option>
					<option value="MO">Missouri</option>
					<option value="MT">Montana</option>
					<option value="NE">Nebraska</option>
					<option value="NV">Nevada</option>
					<option value="NH">New Hampshire</option>
					<option value="NJ">New Jersey</option>
					<option value="NM">New Mexico</option>
					<option value="NY">New York</option>
					<option value="NC">North Carolina</option>
					<option value="ND">North Dakota</option>
					<option value="OH">Ohio</option>
					<option value="OK">Oklahoma</option>
					<option value="OR">Oregon</option>
					<option value="PA">Pennsylvania</option>
					<option value="RI">Rhode Island</option>
					<option value="SC">South Carolina</option>
					<option value="SD">South Dakota</option>
					<option value="TN">Tennessee</option>
					<option value="TX">Texas</option>
					<option value="UT">Utah</option>
					<option value="VT">Vermont</option>
					<option value="VA">Virginia</option>
					<option value="WA">Washington</option>
					<option value="WV">West Virginia</option>
					<option value="WI">Wisconsin</option>
					<option value="WY">Wyoming</option>
				</select>
			</div>
			<div class="field">
	    <label>Birthday</label>
	    <div class="two fields">
	    	<div class="field">
	        	<select class="ui fluid dropdown" name="b_month">
			    	<option value="">Select Month</option>
			        <option value="1">January</option>
			        <option value="2">February</option>
			        <option value="3">March</option>
			        <option value="4">April</option>
			        <option value="5">May</option>
			        <option value="6">June</option>
			        <option value="7">July</option>
			        <option value="8">August</option>
			        <option value="9">September</option>
			        <option value="10">October</option>
			        <option value="11">November</option>
			        <option value="12">December</option>
			    </select>
	      	</div>
	      	<div class="field">
	        	<select class="ui fluid dropdown" name="b_day">
			      <option value="">Select Day</option>
			      <option value="1">1</option>
		          <option value="2">2</option>
		          <option value="3">3</option>
		          <option value="4">4</option>
		          <option value="5">5</option>
		          <option value="6">6</option>
		          <option value="7">7</option>
		          <option value="8">8</option>
		          <option value="9">9</option>
		          <option value="10">10</option>
		          <option value="11">11</option>
		          <option value="12">12</option>
		          <option value="13">13</option>
		          <option value="14">14</option>
		          <option value="15">15</option>
		          <option value="16">16</option>
		          <option value="17">17</option>
		          <option value="18">18</option>
		          <option value="19">19</option>
		          <option value="20">20</option>
		          <option value="21">21</option>
		          <option value="22">22</option>
		          <option value="23">23</option>
		          <option value="24">24</option>
		          <option value="25">25</option>
		          <option value="26">26</option>
		          <option value="27">27</option>
		          <option value="28">28</option>
		          <option value="29">29</option>
		          <option value="30">30</option>
		          <option value="31">31</option>
			    </select>
	      </div>
	    </div>

	  </div>
		<hr>
		<div class="field">
			<label>Project type</label>
			<select class="ui fluid dropdown" name="p_type">
				<option value="">Select project type</option>
				<option value="1">Kitchen</option>
			  	<option value="2">Bathroom</option>
			  	<option value="3">Living room</option>

			</select>
		</div>
		<div class="field">
			<label>Project value</label>
			<select class="ui fluid dropdown" name="p_value">
				<option value="">Select project value range</option>
				<option value="1">0-15000</option>
			    <option value="2">15001-50000</option>
			    <option value="3">50001-80000</option>
			    <option value="4">over 80001</option>
			</select>
		</div>
		<hr>
		<div class="field">
            <label>Notes</label>
            <textarea placeholder = "Add your notes here ..." name="note"></textarea>
          </div>
        <div class="field ">
            <label>Introduction</label>
            <div class="ui input">
                <textarea class="text_intro" name="c_email_intro_text"></textarea>
            </div>
        </div>
	  <hr>
	  <div class="ui one column stackable center aligned page column">
		  <div class="inline field">
		    <div class="ui checkbox">
		      <input type="checkbox" value="1" tabindex="0" class="hidden" name="c_review">
		      <label>Activate rewiew campaign</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input type="checkbox" value="1" tabindex="0" class="hidden" name="c_birthday">
              <label>Activate birthday campaign</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input type="checkbox" value="1" tabindex="0" class="hidden" name="c_holidays">
              <label>Activate holidays campaign</label>
		    </div>
		  </div>
		</div>
	  <hr>
	  <div class="ui one column stackable center aligned page grid">
			<button class="ui massive secondary button ">Add contact</button>
		</div>
	</form>
	</main>
</div>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/semantic.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

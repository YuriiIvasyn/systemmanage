<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    header('Location: /beta/login.php');
    exit;
}

require_once(__DIR__.'/vendor/loader.php');
require_once(__DIR__.'/mailchimp/MailChimp.php');

use \DrewM\MailChimp\MailChimp;
$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_GET['id'])) exit;


$object = $db->select("*")->from("data")->where('id = ?', $_GET['id'])->execute()->fetchCollection()[0];
if($object==null){
    header('Location: /beta/list.php');
    exit;
}
$user_creator = $db->select("*")->from("users")->where('id = ?', $object->u_creator_id)->execute()->fetchCollection()[0];
$current_user = $db->select("*")->from("users")->where('id = ?', $user_id)->execute()->fetchCollection()[0];
if($object->u_creator_id != $user_id && $current_user->u_access_level != 1) {
    header('Location: /beta/list.php');
    exit;
}

if (isset($_POST['state'])) {
	$object->updated = date("Y-m-d H:i:s");
	$db->update("data", $object, "id = ?", $object->id);

	if ($_POST['c_review'] == '1') $_POST['c_review'] = 1;
	else  $_POST['c_review'] = 0;
    if ($_POST['c_birthday'] == '1') $_POST['c_birthday'] = 1;
    else  $_POST['c_birthday'] = 0;
    if ($_POST['c_holidays'] == '1') $_POST['c_holidays'] = 1;
    else  $_POST['c_holidays'] = 0;
    $_POST['u_creator_id'] = $user_creator->id;
	$db->update("data", $_POST, "id = ?", $_GET['id']);

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
                    'C_CREATOR' => strval($object->u_creator_id)
                )
            )
        ),
        'update_existing'  => true
    ));
	header('Location: /beta/list.php');
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
    <a href = "index.php"> <img src="img/logo-small.jpg" alt="" class="logo-2"></a>
	<div class="ui one column stackable center aligned page grid">
		<h2 class="ui header">Edit contact</h2>
	</div>
	<form class="ui big form" method="post">
		<div class="field">
			<label>Name</label>
			<input type="text" name="name" placeholder="Insert customer's name" value="<?= $object->name ?>">
		</div>
		<div class="field">
			<label>Surame</label>
			<input type="text" name="last_name" placeholder="Insert customer's surname" value="<?= $object->last_name ?>">
		</div>
		<div class="field">
			<label>email</label>
			<input type="email" name="email" placeholder="Insert customer's email" value="<?= $object->email ?>">
		</div>
        <div class="field">
            <label>Country</label>
            <select class="ui fluid dropdown" name="country">
                <option value="">Select country</option>
                <option value="1" <?= $object->state == '1' ? ' selected' : '' ?>>United States</option>
                <option value="2" <?= $object->state == '2' ? ' selected' : '' ?>>United Kingdom</option>
                <option value="3" <?= $object->state == '3' ? ' selected' : '' ?>>Afghanistan</option>
                <option value="4" <?= $object->state == '4' ? ' selected' : '' ?>>Albania</option>
                <option value="5" <?= $object->state == '5' ? ' selected' : '' ?>>Algeria</option>
                <option value="6" <?= $object->state == '6' ? ' selected' : '' ?>>American Samoa</option>
                <option value="7" <?= $object->state == '7' ? ' selected' : '' ?>>Andorra</option>
                <option value="8" <?= $object->state == '8' ? ' selected' : '' ?>>Angola</option>
                <option value="9" <?= $object->state == '9' ? ' selected' : '' ?>>Anguilla</option>
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
			<input type="text" name="address" placeholder="Insert customer's address" value="<?= $object->address ?>">
		</div>
		<div class="field">
			<label>City</label>
			<input type="text" name="city" placeholder="Insert customer's city" value="<?= $object->city ?>">
		</div>
		<div class="field">
			<label>ZIP</label>
			<input type="text" name="zip" placeholder="Insert customer's zip" value="<?= $object->zip ?>">
		</div>
			<div class="field">
				<label>State</label>
				<select class="ui fluid dropdown" name="state">
					<option value="">Select state</option>
					<option value="AL"<?= $object->state == 'AL' ? ' selected' : '' ?>>Alabama</option>
					<option value="AK"<?= $object->state == 'AK' ? ' selected' : '' ?>>Alaska</option>
					<option value="AZ"<?= $object->state == 'AZ' ? ' selected' : '' ?>>Arizona</option>
					<option value="AR"<?= $object->state == 'AR' ? ' selected' : '' ?>>Arkansas</option>
					<option value="CA"<?= $object->state == 'CA' ? ' selected' : '' ?>>California</option>
					<option value="CO"<?= $object->state == 'CO' ? ' selected' : '' ?>>Colorado</option>
					<option value="CT"<?= $object->state == 'CT' ? ' selected' : '' ?>>Connecticut</option>
					<option value="DE"<?= $object->state == 'DE' ? ' selected' : '' ?>>Delaware</option>
					<option value="DC"<?= $object->state == 'DC' ? ' selected' : '' ?>>District Of Columbia</option>
					<option value="FL"<?= $object->state == 'FL' ? ' selected' : '' ?>>Florida</option>
					<option value="GA"<?= $object->state == 'GA' ? ' selected' : '' ?>>Georgia</option>
					<option value="HI"<?= $object->state == 'HI' ? ' selected' : '' ?>>Hawaii</option>
					<option value="ID"<?= $object->state == 'ID' ? ' selected' : '' ?>>Idaho</option>
					<option value="IL"<?= $object->state == 'IL' ? ' selected' : '' ?>>Illinois</option>
					<option value="IN"<?= $object->state == 'IN' ? ' selected' : '' ?>>Indiana</option>
					<option value="IA"<?= $object->state == 'IA' ? ' selected' : '' ?>>Iowa</option>
					<option value="KS"<?= $object->state == 'KS' ? ' selected' : '' ?>>Kansas</option>
					<option value="KY"<?= $object->state == 'KY' ? ' selected' : '' ?>>Kentucky</option>
					<option value="LA"<?= $object->state == 'LA' ? ' selected' : '' ?>>Louisiana</option>
					<option value="ME"<?= $object->state == 'ME' ? ' selected' : '' ?>>Maine</option>
					<option value="MD"<?= $object->state == 'MD' ? ' selected' : '' ?>>Maryland</option>
					<option value="MA"<?= $object->state == 'MA' ? ' selected' : '' ?>>Massachusetts</option>
					<option value="MI"<?= $object->state == 'MI' ? ' selected' : '' ?>>Michigan</option>
					<option value="MN"<?= $object->state == 'MN' ? ' selected' : '' ?>>Minnesota</option>
					<option value="MS"<?= $object->state == 'MS' ? ' selected' : '' ?>>Mississippi</option>
					<option value="MO"<?= $object->state == 'MO' ? ' selected' : '' ?>>Missouri</option>
					<option value="MT"<?= $object->state == 'MT' ? ' selected' : '' ?>>Montana</option>
					<option value="NE"<?= $object->state == 'NE' ? ' selected' : '' ?>>Nebraska</option>
					<option value="NV"<?= $object->state == 'NV' ? ' selected' : '' ?>>Nevada</option>
					<option value="NH"<?= $object->state == 'NH' ? ' selected' : '' ?>>New Hampshire</option>
					<option value="NJ"<?= $object->state == 'NJ' ? ' selected' : '' ?>>New Jersey</option>
					<option value="NM"<?= $object->state == 'NM' ? ' selected' : '' ?>>New Mexico</option>
					<option value="NY"<?= $object->state == 'NY' ? ' selected' : '' ?>>New York</option>
					<option value="NC"<?= $object->state == 'NC' ? ' selected' : '' ?>>North Carolina</option>
					<option value="ND"<?= $object->state == 'ND' ? ' selected' : '' ?>>North Dakota</option>
					<option value="OH"<?= $object->state == 'OH' ? ' selected' : '' ?>>Ohio</option>
					<option value="OK"<?= $object->state == 'OK' ? ' selected' : '' ?>>Oklahoma</option>
					<option value="OR"<?= $object->state == 'OR' ? ' selected' : '' ?>>Oregon</option>
					<option value="PA"<?= $object->state == 'PA' ? ' selected' : '' ?>>Pennsylvania</option>
					<option value="RI"<?= $object->state == 'RI' ? ' selected' : '' ?>>Rhode Island</option>
					<option value="SC"<?= $object->state == 'SC' ? ' selected' : '' ?>>South Carolina</option>
					<option value="SD"<?= $object->state == 'SD' ? ' selected' : '' ?>>South Dakota</option>
					<option value="TN"<?= $object->state == 'TN' ? ' selected' : '' ?>>Tennessee</option>
					<option value="TX"<?= $object->state == 'TX' ? ' selected' : '' ?>>Texas</option>
					<option value="UT"<?= $object->state == 'UT' ? ' selected' : '' ?>>Utah</option>
					<option value="VT"<?= $object->state == 'VT' ? ' selected' : '' ?>>Vermont</option>
					<option value="VA"<?= $object->state == 'VA' ? ' selected' : '' ?>>Virginia</option>
					<option value="WA"<?= $object->state == 'WA' ? ' selected' : '' ?>>Washington</option>
					<option value="WV"<?= $object->state == 'WV' ? ' selected' : '' ?>>West Virginia</option>
					<option value="WI"<?= $object->state == 'WI' ? ' selected' : '' ?>>Wisconsin</option>
					<option value="WY"<?= $object->state == 'WY' ? ' selected' : '' ?>>Wyoming</option>
				</select>
			</div>
			<div class="field">
	    <label>Birthday</label>
	    <div class="two fields">
	      <div class="field">
	        <select class="ui fluid dropdown" name="b_month">
			      <option value="">Select Month</option>
			      <option value="1"<?= $object->b_month == '1' ? ' selected' : '' ?>>January</option>
			        <option value="2"<?= $object->b_month == '2' ? ' selected' : '' ?>>February</option>
			        <option value="3"<?= $object->b_month == '3' ? ' selected' : '' ?>>March</option>
			        <option value="4"<?= $object->b_month == '4' ? ' selected' : '' ?>>April</option>
			        <option value="5"<?= $object->b_month == '5' ? ' selected' : '' ?>>May</option>
			        <option value="6"<?= $object->b_month == '6' ? ' selected' : '' ?>>June</option>
			        <option value="7"<?= $object->b_month == '7' ? ' selected' : '' ?>>July</option>
			        <option value="8"<?= $object->b_month == '8' ? ' selected' : '' ?>>August</option>
			        <option value="9"<?= $object->b_month == '9' ? ' selected' : '' ?>>September</option>
			        <option value="10"<?= $object->b_month == '10' ? ' selected' : '' ?>>October</option>
			        <option value="11"<?= $object->b_month == '11' ? ' selected' : '' ?>>November</option>
			        <option value="12"<?= $object->b_month == '12' ? ' selected' : '' ?>>December</option>
			    </select>
	      </div>
	      <div class="field">
	        <select class="ui fluid dropdown" name="b_day">
			      <option value="">Select Day</option>
			      <option value="1"<?= $object->b_day == '1' ? ' selected' : '' ?>>01</option>
			      <option value="2"<?= $object->b_day == '2' ? ' selected' : '' ?>>02</option>
		          <option value="3"<?= $object->b_day == '3' ? ' selected' : '' ?>>03</option>
		          <option value="4"<?= $object->b_day == '4' ? ' selected' : '' ?>>04</option>
		          <option value="5"<?= $object->b_day == '5' ? ' selected' : '' ?>>05</option>
		          <option value="6"<?= $object->b_day == '6' ? ' selected' : '' ?>>06</option>
		          <option value="7"<?= $object->b_day == '7' ? ' selected' : '' ?>>07</option>
		          <option value="8"<?= $object->b_day == '8' ? ' selected' : '' ?>>08</option>
		          <option value="9"<?= $object->b_day == '9' ? ' selected' : '' ?>>09</option>
		          <option value="10"<?= $object->b_day == '10' ? ' selected' : '' ?>>10</option>
		          <option value="11"<?= $object->b_day == '11' ? ' selected' : '' ?>>11</option>
		          <option value="12"<?= $object->b_day == '12' ? ' selected' : '' ?>>12</option>
		          <option value="13"<?= $object->b_day == '13' ? ' selected' : '' ?>>13</option>
		          <option value="14"<?= $object->b_day == '14' ? ' selected' : '' ?>>14</option>
		          <option value="15"<?= $object->b_day == '15' ? ' selected' : '' ?>>15</option>
		          <option value="16"<?= $object->b_day == '16' ? ' selected' : '' ?>>16</option>
		          <option value="17"<?= $object->b_day == '17' ? ' selected' : '' ?>>17</option>
		          <option value="18"<?= $object->b_day == '18' ? ' selected' : '' ?>>18</option>
		          <option value="19"<?= $object->b_day == '19' ? ' selected' : '' ?>>19</option>
		          <option value="20"<?= $object->b_day == '20' ? ' selected' : '' ?>>20</option>
		          <option value="21"<?= $object->b_day == '21' ? ' selected' : '' ?>>21</option>
		          <option value="22"<?= $object->b_day == '22' ? ' selected' : '' ?>>22</option>
		          <option value="23"<?= $object->b_day == '23' ? ' selected' : '' ?>>23</option>
		          <option value="24"<?= $object->b_day == '24' ? ' selected' : '' ?>>24</option>
		          <option value="25"<?= $object->b_day == '25' ? ' selected' : '' ?>>25</option>
		          <option value="26"<?= $object->b_day == '26' ? ' selected' : '' ?>>26</option>
		          <option value="27"<?= $object->b_day == '27' ? ' selected' : '' ?>>27</option>
		          <option value="28"<?= $object->b_day == '28' ? ' selected' : '' ?>>28</option>
		          <option value="29"<?= $object->b_day == '29' ? ' selected' : '' ?>>29</option>
		          <option value="30"<?= $object->b_day == '30' ? ' selected' : '' ?>>30</option>
		          <option value="31"<?= $object->b_day == '31' ? ' selected' : '' ?>>31</option>
			    </select>
	      </div>
	    </div>
	  </div>
		<hr>
		<div class="field">
			<label>Project type</label>
			<select class="ui fluid dropdown" name="p_type">
				<option value="">Select project type</option>
			  	<option value="1"<?= $object->p_type == '1' ? ' selected' : '' ?>>Kitchen</option>
			  	<option value="2"<?= $object->p_type == '2' ? ' selected' : '' ?>>Bathroom</option>
			  	<option value="3"<?= $object->p_type == '3' ? ' selected' : '' ?>>Living room</option>

			</select>
		</div>
		<div class="field">
			<label>Project value</label>
			<select class="ui fluid dropdown" name="p_value">
				<option value="">Select project value range</option>
				<option value="1"<?= $object->p_value == '1' ? ' selected' : '' ?>>0-15000</option>
			    <option value="2"<?= $object->p_value == '2' ? ' selected' : '' ?>>15001-50000</option>
			    <option value="3"<?= $object->p_value == '3' ? ' selected' : '' ?>>50001-80000</option>
			    <option value="4"<?= $object->p_value == '4' ? ' selected' : '' ?>>over 80001</option>
			</select>
		</div>
		<hr>
		<div class="field">
	    <label>Note</label>
	    <textarea placeholder = "Add your notes here ..." name="note"><?= $object->note ?></textarea>
	  </div>
        <div class="field ">
            <label>Introduction</label>
            <div class="ui input">
                <textarea class="text_intro" name="c_email_intro_text"><?= $object->c_email_intro_text ?></textarea>
            </div>
        </div>
	  <hr>
      <div class="ui one column stackable center aligned page column">
            <div class="inline field">
                <div class="ui checkbox">
                    <input type="checkbox" value="1" tabindex="0" class="hidden" name="c_review" <?= $object->c_review == '1' ? ' checked' : '' ?>>
                    <label>Activate review campaign</label>
                </div>
            </div>
            <div class="inline field">
                <div class="ui checkbox">
                    <input type="checkbox" value="1" tabindex="0" class="hidden" name="c_birthday" <?= $object->c_birthday == '1' ? ' checked' : '' ?>>
                    <label>Activate birthday campaign</label>
                </div>
            </div>
            <div class="inline field">
                <div class="ui checkbox">
                    <input type="checkbox" value="1" tabindex="0" class="hidden" name="c_holidays" <?= $object->c_holidays == '1' ? ' checked' : '' ?>>
                    <label>Activate holidays campaign</label>
                </div>
            </div>
        </div>
	  <hr>
	  <div class="ui one column stackable center aligned page grid">
		 	<button class="ui massive secondary button ">Save contact</button>
      </div>
      <hr>
      <div class="ui one column stackable center aligned page grid">
        <h2 class="ui header"><?=$user_creator->u_name." ".$user_creator->u_last_name ?></h2>
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

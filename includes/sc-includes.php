<?php

//   Copyright 2008 johnboyproductions.com
//
//   Licensed under the Apache License, Version 2.0 (the "License");
//   you may not use this file except in compliance with the License.
//   You may obtain a copy of the License at
//
//       http://www.apache.org/licenses/LICENSE-2.0
//
//   Unless required by applicable law or agreed to in writing, software
//   distributed under the License is distributed on an "AS IS" BASIS,
//   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//   See the License for the specific language governing permissions and
//   limitations under the License.

mysql_select_db($database_contacts, $contacts);

include('includes/functions.php');

session_start();
if (!isset($_SESSION['user'])) {
header('Location: login.php');
}

//GET USER INFORMATION
record_set('userinfo',"SELECT * FROM users WHERE user_email = '".$_SESSION['user']."'");
$user_admin = $row_userinfo['user_level'] == 1 ? 1 : 0;
$userid = $row_userinfo['user_id'];
//

//GET OPTION INFORMATION
function get_option ($opt)  {
   $query =  mysql_query("SELECT option_value FROM options WHERE option_title = '".$opt."'");
   $result = mysql_fetch_array($query);
   return $result['option_value'];
}
//

$contactcount = mysql_query("SELECT * FROM contacts") or die(mysql_error());
$contactcount = mysql_num_rows($contactcount);

//not applicable
$na = '<span style="color:#CCCCCC">N/A</span>';
//

//get tags
record_set('tags',"SELECT * FROM tags INNER JOIN tags_assoc ON itag_tag = tag_id INNER JOIN contacts ON contact_id = itag_contact ORDER BY tag_description ASC");
//

//list of states
$state_list = array('AL'=>"Alabama",
                'AK'=>"Alaska", 
                'AZ'=>"Arizona", 
                'AR'=>"Arkansas", 
                'CA'=>"California", 
                'CO'=>"Colorado", 
                'CT'=>"Connecticut", 
                'DE'=>"Delaware", 
                'DC'=>"District Of Columbia", 
                'FL'=>"Florida", 
                'GA'=>"Georgia", 
                'HI'=>"Hawaii", 
                'ID'=>"Idaho", 
                'IL'=>"Illinois", 
                'IN'=>"Indiana", 
                'IA'=>"Iowa", 
                'KS'=>"Kansas", 
                'KY'=>"Kentucky", 
                'LA'=>"Louisiana", 
                'ME'=>"Maine", 
                'MD'=>"Maryland", 
                'MA'=>"Massachusetts", 
                'MI'=>"Michigan", 
                'MN'=>"Minnesota", 
                'MS'=>"Mississippi", 
                'MO'=>"Missouri", 
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'OH'=>"Ohio", 
                'OK'=>"Oklahoma", 
                'OR'=>"Oregon", 
                'PA'=>"Pennsylvania", 
                'RI'=>"Rhode Island", 
                'SC'=>"South Carolina", 
                'SD'=>"South Dakota",
                'TN'=>"Tennessee", 
                'TX'=>"Texas", 
                'UT'=>"Utah", 
                'VT'=>"Vermont", 
                'VA'=>"Virginia", 
                'WA'=>"Washington", 
                'WV'=>"West Virginia", 
                'WI'=>"Wisconsin", 
                'WY'=>"Wyoming");
//

$state_list_ca = array(
"BC"=>"British Columbia",
"ON"=>"Ontario",
"NF"=>"Newfoundland",
"NS"=>"Nova Scotia",
"PE"=>"Prince Edward Island",
"NB"=>"New Brunswick",
"QC"=>"Quebec",
"MB"=>"Manitoba",
"SK"=>"Saskatchewan",
"AB"=>"Alberta",
"NT"=>"Northwest Territories",
"YT"=>"Yukon Territory"); 
//

$country_list = array(

	'US'=>'United States',
	'CA'=>'Canada',
	'AF'=>'Afghanistan',
	'AL'=>'Albania',
	'DZ'=>'Algeria',
	'AS'=>'American Samoa',
	'AD'=>'Andorra',
	'AO'=>'Angola',
	'AI'=>'Anguilla',
	'AQ'=>'Antarctica',
	'AG'=>'Antigua And Barbuda',
	'AR'=>'Argentina',
	'AM'=>'Armenia',
	'AW'=>'Aruba',
	'AU'=>'Australia',
	'AT'=>'Austria',
	'AZ'=>'Azerbaijan',
	'BS'=>'Bahamas',
	'BH'=>'Bahrain',
	'BD'=>'Bangladesh',
	'BB'=>'Barbados',
	'BY'=>'Belarus',
	'BE'=>'Belgium',
	'BZ'=>'Belize',
	'BJ'=>'Benin',
	'BM'=>'Bermuda',
	'BT'=>'Bhutan',
	'BO'=>'Bolivia',
	'BA'=>'Bosnia And Herzegovina',
	'BW'=>'Botswana',
	'BV'=>'Bouvet Island',
	'BR'=>'Brazil',
	'IO'=>'British Indian Ocean Territory',
	'BN'=>'Brunei',
	'BG'=>'Bulgaria',
	'BF'=>'Burkina Faso',
	'BI'=>'Burundi',
	'KH'=>'Cambodia',
	'CM'=>'Cameroon',
	'CV'=>'Cape Verde',
	'KY'=>'Cayman Islands',
	'CF'=>'Central African Republic',
	'TD'=>'Chad',
	'CL'=>'Chile',
	'CN'=>'China',
	'CX'=>'Christmas Island',
	'CC'=>'Cocos (Keeling) Islands',
	'CO'=>'Columbia',
	'KM'=>'Comoros',
	'CG'=>'Congo',
	'CK'=>'Cook Islands',
	'CR'=>'Costa Rica',
	'CI'=>'Cote D\'Ivorie (Ivory Coast)',
	'HR'=>'Croatia (Hrvatska)',
	'CU'=>'Cuba',
	'CY'=>'Cyprus',
	'CZ'=>'Czech Republic',
	'CD'=>'Democratic Republic Of Congo (Zaire)',
	'DK'=>'Denmark',
	'DJ'=>'Djibouti',
	'DM'=>'Dominica',
	'DO'=>'Dominican Republic',
	'TP'=>'East Timor',
	'EC'=>'Ecuador',
	'EG'=>'Egypt',
	'SV'=>'El Salvador',
	'GQ'=>'Equatorial Guinea',
	'ER'=>'Eritrea',
	'EE'=>'Estonia',
	'ET'=>'Ethiopia',
	'FK'=>'Falkland Islands (Malvinas)',
	'FO'=>'Faroe Islands',
	'FJ'=>'Fiji',
	'FI'=>'Finland',
	'FR'=>'France',
	'FX'=>'France, Metropolitan',
	'GF'=>'French Guinea',
	'PF'=>'French Polynesia',
	'TF'=>'French Southern Territories',
	'GA'=>'Gabon',
	'GM'=>'Gambia',
	'GE'=>'Georgia',
	'DE'=>'Germany',
	'GH'=>'Ghana',
	'GI'=>'Gibraltar',
	'GR'=>'Greece',
	'GL'=>'Greenland',
	'GD'=>'Grenada',
	'GP'=>'Guadeloupe',
	'GU'=>'Guam',
	'GT'=>'Guatemala',
	'GN'=>'Guinea',
	'GW'=>'Guinea-Bissau',
	'GY'=>'Guyana',
	'HT'=>'Haiti',
	'HM'=>'Heard And McDonald Islands',
	'HN'=>'Honduras',
	'HK'=>'Hong Kong',
	'HU'=>'Hungary',
	'IS'=>'Iceland',
	'IN'=>'India',
	'ID'=>'Indonesia',
	'IR'=>'Iran',
	'IQ'=>'Iraq',
	'IE'=>'Ireland',
	'IL'=>'Israel',
	'IT'=>'Italy',
	'JM'=>'Jamaica',
	'JP'=>'Japan',
	'JO'=>'Jordan',
	'KZ'=>'Kazakhstan',
	'KE'=>'Kenya',
	'KI'=>'Kiribati',
	'KW'=>'Kuwait',
	'KG'=>'Kyrgyzstan',
	'LA'=>'Laos',
	'LV'=>'Latvia',
	'LB'=>'Lebanon',
	'LS'=>'Lesotho',
	'LR'=>'Liberia',
	'LY'=>'Libya',
	'LI'=>'Liechtenstein',
	'LT'=>'Lithuania',
	'LU'=>'Luxembourg',
	'MO'=>'Macau',
	'MK'=>'Macedonia',
	'MG'=>'Madagascar',
	'MW'=>'Malawi',
	'MY'=>'Malaysia',
	'MV'=>'Maldives',
	'ML'=>'Mali',
	'MT'=>'Malta',
	'MH'=>'Marshall Islands',
	'MQ'=>'Martinique',
	'MR'=>'Mauritania',
	'MU'=>'Mauritius',
	'YT'=>'Mayotte',
	'MX'=>'Mexico',
	'FM'=>'Micronesia',
	'MD'=>'Moldova',
	'MC'=>'Monaco',
	'MN'=>'Mongolia',
	'MS'=>'Montserrat',
	'MA'=>'Morocco',
	'MZ'=>'Mozambique',
	'MM'=>'Myanmar (Burma)',
	'NA'=>'Namibia',
	'NR'=>'Nauru',
	'NP'=>'Nepal',
	'NL'=>'Netherlands',
	'AN'=>'Netherlands Antilles',
	'NC'=>'New Caledonia',
	'NZ'=>'New Zealand',
	'NI'=>'Nicaragua',
	'NE'=>'Niger',
	'NG'=>'Nigeria',
	'NU'=>'Niue',
	'NF'=>'Norfolk Island',
	'KP'=>'North Korea',
	'MP'=>'Northern Mariana Islands',
	'NO'=>'Norway',
	'OM'=>'Oman',
	'PK'=>'Pakistan',
	'PW'=>'Palau',
	'PA'=>'Panama',
	'PG'=>'Papua New Guinea',
	'PY'=>'Paraguay',
	'PE'=>'Peru',
	'PH'=>'Philippines',
	'PN'=>'Pitcairn',
	'PL'=>'Poland',
	'PT'=>'Portugal',
	'PR'=>'Puerto Rico',
	'QA'=>'Qatar',
	'RE'=>'Reunion',
	'RO'=>'Romania',
	'RU'=>'Russia',
	'RW'=>'Rwanda',
	'SH'=>'Saint Helena',
	'KN'=>'Saint Kitts And Nevis',
	'LC'=>'Saint Lucia',
	'PM'=>'Saint Pierre And Miquelon',
	'VC'=>'Saint Vincent And The Grenadines',
	'SM'=>'San Marino',
	'ST'=>'Sao Tome And Principe',
	'SA'=>'Saudi Arabia',
	'SN'=>'Senegal',
	'SC'=>'Seychelles',
	'SL'=>'Sierra Leone',
	'SG'=>'Singapore',
	'SK'=>'Slovak Republic',
	'SI'=>'Slovenia',
	'SB'=>'Solomon Islands',
	'SO'=>'Somalia',
	'ZA'=>'South Africa',
	'GS'=>'South Georgia And South Sandwich Islands',
	'KR'=>'South Korea',
	'ES'=>'Spain',
	'LK'=>'Sri Lanka',
	'SD'=>'Sudan',
	'SR'=>'Suriname',
	'SJ'=>'Svalbard And Jan Mayen',
	'SZ'=>'Swaziland',
	'SE'=>'Sweden',
	'CH'=>'Switzerland',
	'SY'=>'Syria',
	'TW'=>'Taiwan',
	'TJ'=>'Tajikistan',
	'TZ'=>'Tanzania',
	'TH'=>'Thailand',
	'TG'=>'Togo',
	'TK'=>'Tokelau',
	'TO'=>'Tonga',
	'TT'=>'Trinidad And Tobago',
	'TN'=>'Tunisia',
	'TR'=>'Turkey',
	'TM'=>'Turkmenistan',
	'TC'=>'Turks And Caicos Islands',
	'TV'=>'Tuvalu',
	'UG'=>'Uganda',
	'UA'=>'Ukraine',
	'AE'=>'United Arab Emirates',
	'UK'=>'United Kingdom',
	'UM'=>'United States Minor Outlying Islands',
	'UY'=>'Uruguay',
	'UZ'=>'Uzbekistan',
	'VU'=>'Vanuatu',
	'VA'=>'Vatican City (Holy See)',
	'VE'=>'Venezuela',
	'VN'=>'Vietnam',
	'VG'=>'Virgin Islands (British)',
	'VI'=>'Virgin Islands (US)',
	'WF'=>'Wallis And Futuna Islands',
	'EH'=>'Western Sahara',
	'WS'=>'Western Samoa',
	'YE'=>'Yemen',
	'YU'=>'Yugoslavia',
	'ZM'=>'Zambia',
	'ZW'=>'Zimbabwe'
	);


//search array
$like_where_array = array();

$like_where_array[] = 'contact_first';
$like_where_array[] = 'contact_last';
$like_where_array[] = 'contact_title';
$like_where_array[] = 'contact_street';
$like_where_array[] = 'contact_company';
$like_where_array[] = 'contact_city';
$like_where_array[] = 'contact_zip';
$like_where_array[] = 'contact_phone';
$like_where_array[] = 'contact_profile';
$like_where_array[] = 'contact_cell';
$like_where_array[] = 'contact_web';
$like_where_array[] = 'contact_custom';
$like_where_array[] = 'contact_tags';

$i = 1;
foreach ($like_where_array as $key => $value) {

$and = '';
if ($i > 1) {
$and = 'OR';
}
$like_where .= "$and $value LIKE '%".$_GET[s]."%' ";
$i++;
}

?>
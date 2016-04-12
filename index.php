<?php
/*
 * Copyright (c) PANGAEA - Data Publisher for Earth & Environmental Science
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once('src/config.php');
require_once('src/ORCID.php');

if (isset($_GET['signin'])) {
	header('Location: https://orcid.org/oauth/authorize?' . 
		http_build_query(
			array(
				'response_type' => 'code',
				'scope' => '/authenticate',
				'show_login' => 'true',
           		'client_id' => $config['clientId'],
           		'redirect_uri' => $config['redirectUri']
    		)
		)
	);
}

?>

<html>
<body>
<div>
<?php

if (isset($_GET['code'])) {
	$client = new ORCID($config['clientId'], $config['clientSecret']);
	$response = $client->requestTokenForCode($_GET['code']);
	$orcid = $response->{'orcid'};
	
	echo 'Token: ' . $response->{'access_token'} . '<br/>';
    echo 'Name: ' . $response->{'name'} . '<br/>';
    echo 'ORCID: ' . $orcid . '<br/>';
    
    $response = $client->requestOrcidBio($orcid);
    $profile = $response->{'orcid-profile'};
    $identifier = $profile->{'orcid-identifier'};
    $bio = $profile->{'orcid-bio'};
    $contact = $bio->{'contact-details'};
    
    echo '<br/>';
    echo '<b>ORCID BIO</b><br/>';
    echo 'URI: ' . $identifier->uri . '<br/>';
    
    if (isset($contact->{'email'})) {
    	echo 'Email: ' . $contact->{'email'}[0]->value . '<br/>';
    }
    
    $response = $client->requestOrcidWorks($orcid);
    $profile = $response->{'orcid-profile'};
    $activities = $profile->{'orcid-activities'};
    $works = $activities->{'orcid-works'};
    
    echo '<br/>';
    echo '<b>ORCID WORKS</b><br/>';
    
    foreach($works->{'orcid-work'} as $work) {
		echo 'Title: ' . $work->{'work-title'}->{'title'}->{'value'} . '<br/>';
		if (isset($work->{'journal-title'}->{'value'})) {
			echo 'Journal: ' . $work->{'journal-title'}->{'value'} . '<br/>';
		}
    }
    
} else {
	echo '<a href="?signin=true">ORCID Sing In</a>';
}
 
?>
</div>
</body>
</html>
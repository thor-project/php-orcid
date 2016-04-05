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

class ORCID {
	
	private $clientId;
	private $clientSecret;
	private $redirectUri;
	private $tokenUrl;
	private $pubUrl;
	private $orcidBioRequestType;
	private $orcidWorksRequestType;
	
	public function __construct($clientId, $clientSecret) {
		if (!isset($clientId)) {
			throw new Exception('Client ID is not set');
		}
		if (!isset($clientSecret)) {
			throw new Exception('Client Secret is not set');
		}
		
		global $config;
			
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->redirectUri = $config['redirectUri'];
		$this->tokenUrl = $config['tokenUrl'];
		$this->pubUrl = $config['pubUrl'];
		$this->orcidBioRequestType = $config['orcidBioRequestType'];
		$this->orcidWorksRequestType = $config['orcidWorksRequestType'];
	}
	
	public function getClientId() {
		return $this->clientId;
	}
	
	public function getClientSecret() {
		return $this->clientSecret;
	}
	
	public function requestTokenForCode($authorizationCode) {
		return $this->requestAccessToken(array(
				'client_id' => $this->clientId,
		        'client_secret' => $this->clientSecret,
		        'grant_type' => 'authorization_code',
		        'redirect_uri' => $this->redirectUri . '&code=' . $authorizationCode));
	}
	
	public function requestOrcidBio($orcid) {
		$response = $this->requestAccessToken(array(
				'client_id' => $this->clientId,
				'client_secret' => $this->clientSecret,						
				'grant_type' => 'client_credentials',
				'scope' => '/read-public'));
		
		return $this->requestPublicData($response->{'access_token'}, $orcid, $this->orcidBioRequestType);
	}
	
	public function requestOrcidWorks($orcid) {
		$response = $this->requestAccessToken(array(
				'client_id' => $this->clientId,
				'client_secret' => $this->clientSecret,
				'grant_type' => 'client_credentials',
				'scope' => '/read-public'));
		
		return $this->requestPublicData($response->{'access_token'}, $orcid, $this->orcidWorksRequestType);
	}
	
	private function requestAccessToken($params) {
		$curl = curl_init($this->tokenUrl);
			
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query($params)));
			
		$response = json_decode(curl_exec($curl));
			
		curl_close($curl);
			
		return $response;
	}
	
	private function requestPublicData($token, $orcid, $requestType) {
		$curl = curl_init($this->pubUrl . $orcid . $requestType);
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/orcid+json',
				'Authorization: Bearer ' . $token));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$response = json_decode(curl_exec($curl));
		
		curl_close($curl);
		
		return $response;
	}
	
}

?>
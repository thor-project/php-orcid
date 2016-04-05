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

require_once("src/ORCID.php");

class ORCIDTest extends PHPUnit_Framework_TestCase {
	
	private $orcid;
	private $clientId = "OOW6VPII";
	private $clientSecret = "a175b021-ab64-49c6";
	
	protected function setUp()
	{
		$this->orcid = new ORCID($this->clientId, $this->clientSecret);
	}
	
	
	public function testGetClientId() {
		$this->assertEquals($this->clientId, $this->orcid->getClientId());
	}
	
	public function testGetClientSecret() {
		$this->assertEquals($this->clientSecret, $this->orcid->getClientSecret());
	}
	
}

?>
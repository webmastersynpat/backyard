<?php  
class Google_Contact{
	private $client;
	
	
	function __construct(){
		$this->login();
	}
	
	private function login() 
	{		
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Http_Client');
		Zend_Loader::loadClass('Zend_Gdata_Query');
		Zend_Loader::loadClass('Zend_Gdata_Feed');
		$this->client = new Zend_Gdata(null);
		$this->client->setMajorProtocolVersion(3);
		if ($this->client instanceof Zend_Gdata){
			return TRUE;
		} else{
			return FALSE;
		}
	}
	
	
	function getFeed($query){
		return $this->client->getFeed($query);
	}
	
	
	function findContacts(){
		$feed = array();
		try{
			$query = new Zend_Gdata_Query('http://www.google.com/m8/feeds/contacts/default/full?max-results=500000');
			$feed = $this->client->getFeed($query);
		} catch(Exception $e){
			
		}
		return $feed;
	}
	
	
	
	function deleteContact($ID){
		try{
			$extra_header = array(); 

			$extra_header = array('If-Match'=>'*');
			$entry = $this->client->getEntry($ID); $this->client->delete($ID,null,$extra_header);
			$entry->delete(); 
			 
		} catch(Exception $e){
			echo $e->getCode().": ".$e->getMessage();
		}	
	}
	
	function editContact($ID){
		$feed = "";
		try{
			$query = new Zend_Gdata_Query($ID);
			$feed = $this->client->getEntry($query);

		} catch(Exception $e){
			
		}
		return $feed;
	}
	
	function updateContact($getData){		
		$doc  = new DOMDocument();
		$doc->formatOutput = true;
		$entry = $doc->createElement('atom:entry');
		$entry->setAttributeNS('http://www.w3.org/2000/xmlns/' , 'xmlns:atom', 'http://www.w3.org/2005/Atom');
		$entry->setAttributeNS('http://www.w3.org/2000/xmlns/' , 'xmlns:gd', 'http://schemas.google.com/g/2005');
		$doc->appendChild($entry);
		// add name element
		$name = $doc->createElement('gd:name');
		$entry->appendChild($name);
		$fullName = $doc->createElement('gd:fullName', $getData['invitee']['first_name']." ".$getData['invitee']['last_name']);
		$name->appendChild($fullName);
		// add email element
		$email = $doc->createElement('gd:email');
		$email->setAttribute('address' ,$getData['invitee']['email']);
		$email->setAttribute('rel' ,'http://schemas.google.com/g/2005#home');
		$entry->appendChild($email);	
		// add address
		$address = $doc->createElement('gd:structuredPostalAddress');
		$address->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');		
		$address->setAttribute('primary' ,'true');	
		$street = $doc->createElement('gd:street',$getData['invitee']['street']);
		$address->appendChild($street);	
		$state = $doc->createElement('gd:region',$getData['invitee']['state']);
		$address->appendChild($state);	
		$city = $doc->createElement('gd:city',$getData['invitee']['city']);
		$address->appendChild($city);
		$zipcode = $doc->createElement('gd:postcode',$getData['invitee']['zip']);
		$address->appendChild($zipcode);	
		$country = $doc->createElement('gd:country',$getData['invitee']['country']);
		$address->appendChild($country);	
		$formattedAddress = $doc->createElement('gd:formattedAddress',$getData['invitee']['street']." ".$getData['invitee']['city']);
		$address->appendChild($formattedAddress);	
		$entry->appendChild($address);	
		
		// add phone element
		if(!empty($getData['invitee']['phone'])){
			$phone = $doc->createElement('gd:phoneNumber',$getData['invitee']['phone']);
			$phone->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');				
			$entry->appendChild($phone);	
		}
		
		
		// add telephone element
		if(!empty($getData['invitee']['telephone'])){
			$phone = $doc->createElement('gd:phoneNumber',$getData['invitee']['telephone']);
			$phone->setAttribute('rel' ,'http://schemas.google.com/g/2005#home');				
			$entry->appendChild($phone);
		}
			
		
		$group = $doc->createElement('gContact:groupMembershipInfo');
		$group->setAttribute('href' ,'http://www.google.com/m8/feeds/groups/synpat%40synpat.com/base/6');				
		$group->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');				
		$group->setAttribute('deleted' ,false);				
		$entry->appendChild($group);	
		
		
/*
		$group = $doc->createElement('gContact:groupMembershipInfo');
		$group->setAttribute('href' ,'http://www.google.com/m8/feeds/groups/synpat%40synpat.com/base/5a10704e880144c2');				
		$group->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');				
		$group->setAttribute('deleted' ,false);				
		$entry->appendChild($group);	*/
		
		if(!empty($getData['invitee']['web_address'])){
			$group = $doc->createElement('gContact:website');
			$group->setAttribute('href' ,$getData['invitee']['web_address']);				
			$group->setAttribute('primary' ,true);				
			$group->setAttribute('rel' ,'work');				
			$entry->appendChild($group);	
		}
		
		// add org name element
		$org = $doc->createElement('gd:organization');
		$org->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');
		$entry->appendChild($org);
		$orgName = $doc->createElement('gd:orgName');
		$orgName->appendChild($doc->createTextNode($getData['invitee']['company_name']));
		$orgTitle = $doc->createElement('gd:orgTitle');
		$orgTitle->appendChild($doc->createTextNode($getData['invitee']['job_title']));
		$org->appendChild($orgName);
		$org->appendChild($orgTitle);
		/*$title = $doc->createElement('gd:title', $getData['invitee']['person_in_charge']);
		$entry->appendChild($title);*/
		if(isset($getData['mar']['sector']) && count($getData['mar']['sector'])>0){
			foreach($getData['mar']['sector'] as $sector){
				/*$saveData = $this->opportunity_model->insertInviteesInSector(array('invite_id'=>$invitee_id,'market_id'=>$sector));*/
				$userDefineField = $doc->createElement('gContact:userDefinedField');
				$userDefineField->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');
				$userDefineField->setAttribute('key' ,'Market');
				$userDefineField->setAttribute('value' ,$sector);
				$entry->appendChild($userDefineField);
			}
		}
		$extra_header = array(); 

		$extra_header = array('If-Match'=>'*');
		$entryResult = $this->client->updateEntry($doc->saveXML(), $getData['invitee']['id'],null,$extra_header );
		return $entryResult;
	}
	
	function addContact($getData){
		$doc  = new DOMDocument();
		$doc->formatOutput = true;
		$entry = $doc->createElement('atom:entry');
		$entry->setAttributeNS('http://www.w3.org/2000/xmlns/' , 'xmlns:atom', 'http://www.w3.org/2005/Atom');
		$entry->setAttributeNS('http://www.w3.org/2000/xmlns/' , 'xmlns:gd', 'http://schemas.google.com/g/2005');
		$doc->appendChild($entry);
		// add name element
		$name = $doc->createElement('gd:name');
		$entry->appendChild($name);
		$fullName = $doc->createElement('gd:fullName', $getData['invitee']['first_name']." ".$getData['invitee']['last_name']);
		$name->appendChild($fullName);
		// add email element
		$email = $doc->createElement('gd:email');
		$email->setAttribute('address' ,$getData['invitee']['email']);
		$email->setAttribute('rel' ,'http://schemas.google.com/g/2005#home');
		$entry->appendChild($email);	
		// add address
		$address = $doc->createElement('gd:structuredPostalAddress');
		$address->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');		
		$address->setAttribute('primary' ,'true');	
		$street = $doc->createElement('gd:street',$getData['invitee']['street']);
		$address->appendChild($street);	
		$state = $doc->createElement('gd:region',$getData['invitee']['state']);
		$address->appendChild($state);	
		$city = $doc->createElement('gd:city',$getData['invitee']['city']);
		$address->appendChild($city);
		$zipcode = $doc->createElement('gd:postcode',$getData['invitee']['zip']);
		$address->appendChild($zipcode);	
		$country = $doc->createElement('gd:country',$getData['invitee']['country']);
		$address->appendChild($country);	
		$formattedAddress = $doc->createElement('gd:formattedAddress',$getData['invitee']['street']." ".$getData['invitee']['city']);
		$address->appendChild($formattedAddress);	
		$entry->appendChild($address);	
		
		// add phone element
		if(!empty($getData['invitee']['phone'])){
			$phone = $doc->createElement('gd:phoneNumber',$getData['invitee']['phone']);
			$phone->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');				
			$entry->appendChild($phone);	
		}
		
		
		// add telephone element
		if(!empty($getData['invitee']['telephone'])){
			$phone = $doc->createElement('gd:phoneNumber',$getData['invitee']['telephone']);
			$phone->setAttribute('rel' ,'http://schemas.google.com/g/2005#home');				
			$entry->appendChild($phone);
		}
			
		if(!empty($getData['invitee']['web_address'])){
			$group = $doc->createElement('gContact:website');
			$group->setAttribute('href' ,$getData['invitee']['web_address']);				
			$group->setAttribute('primary' ,true);				
			$group->setAttribute('rel' ,'work');				
			$entry->appendChild($group);	
		}
		$group = $doc->createElement('gContact:groupMembershipInfo');
		$group->setAttribute('href' ,'http://www.google.com/m8/feeds/groups/synpat%40synpat.com/base/6');				
		$group->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');				
		$group->setAttribute('deleted' ,false);				
		$entry->appendChild($group);	
		
		
/*
		$group = $doc->createElement('gContact:groupMembershipInfo');
		$group->setAttribute('href' ,'http://www.google.com/m8/feeds/groups/synpat%40synpat.com/base/5a10704e880144c2');				
		$group->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');				
		$group->setAttribute('deleted' ,false);				
		$entry->appendChild($group);	
		*/
		// add org name element
		$org = $doc->createElement('gd:organization');
		$org->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');
		if(!empty($getData['invitee']['company_name'])){
			$orgName = $doc->createElement('gd:orgName', htmlspecialchars($getData['invitee']['company_name'], ENT_QUOTES, 'UTF-8'));
			$org->appendChild($orgName);
		}		
		if(!empty($getData['invitee']['job_title'])){
			$orgTitle = $doc->createElement('gd:orgTitle', htmlspecialchars($getData['invitee']['job_title'], ENT_QUOTES, 'UTF-8'));
			$org->appendChild($orgTitle);
		}
		$entry->appendChild($org);
		/*$title = $doc->createElement('gd:title', $getData['invitee']['person_in_charge']);
		$entry->appendChild($title);*/
		if(isset($getData['mar']['sector']) && count($getData['mar']['sector'])>0){
			foreach($getData['mar']['sector'] as $sector){
				/*$saveData = $this->opportunity_model->insertInviteesInSector(array('invite_id'=>$invitee_id,'market_id'=>$sector));*/
				$userDefineField = $doc->createElement('gContact:userDefinedField');
				$userDefineField->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');
				$userDefineField->setAttribute('key' ,'Market');
				$userDefineField->setAttribute('value' ,$sector);
				$entry->appendChild($userDefineField);
			}
		}
		$entryResult = $this->client->insertEntry($doc->saveXML(), 'https://www.google.com/m8/feeds/contacts/default/full');
		
		return $entryResult;
	}
}
?>
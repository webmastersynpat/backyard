<?php
require_once realpath(dirname(__FILE__) . '/autoload.php');   
define( 'BACKUP_FOLDER', 'SynPatAPI' );
//define( 'BACKUP_FOLDER', 'Master Documents' );
define('PASTING_FOLDER','Opportunities');
define('MASTER_FOLDER','Master Documents');
define( 'SHARE_WITH_GOOGLE_EMAIL', 'webmaster@synpat.com' );
define( 'SHARE_WITH_GOOGLE_EMAIL_ANOTHER', 'dymmyadsynpat@gmail.com' );
define( 'GOOGLE_EMAIL', 'leads@synpat.com' );
define( 'GOOGLE_EMAIL_NAME', 'Vivek Kapoor' );
$folderID = '0B-7JHq4pougDSE5UR2M0OGY3c00';
define( 'CLIENT_ID',  '671429899926-tvqle2htej2bmq1q55k2tnr1dpf3k5g2.apps.googleusercontent.com' );
define( 'SERVICE_ACCOUNT_NAME', '671429899926-tvqle2htej2bmq1q55k2tnr1dpf3k5g2@developer.gserviceaccount.com' );
define( 'KEY_PATH', realpath(dirname(__FILE__) . '/Backyard Project-bef3d9642631.p12'));
define( 'CLIENT_SECRET', realpath(dirname(__FILE__) . '/client_secret_1036285537673-l8gak1un7a18ql6bq9s3i6uag5umit7b.apps.googleusercontent.com.json'));
    

class GmailServiceHelper{
	private $_service;
	private $_client;
	public function __construct() {
		$this->_client = new Google_Client();
		$this->_client->setAuthConfigFile(CLIENT_SECRET); 
		$this->_client->addScope('email');
		$this->_client->addScope('https://mail.google.com');           
		$this->_client->addScope('https://www.googleapis.com/auth/gmail.compose');           
		$this->_client->addScope('https://www.googleapis.com/auth/gmail.modify');           
		$this->_client->addScope('https://www.googleapis.com/auth/gmail.readonly');       
		$this->_client->addScope('https://www.googleapis.com/auth/userinfo.email');           
		$this->_client->addScope('https://apps-apis.google.com/a/feeds/groups/');           
		$this->_client->addScope('https://apps-apis.google.com/a/feeds/alias/');           
		$this->_client->addScope('https://apps-apis.google.com/a/feeds/user/');           
		$this->_client->addScope('https://www.google.com/m8/feeds');           
		$this->_client->addScope('https://www.google.com/m8/feeds/user/');           
		$this->_client->addScope('https://www.googleapis.com/auth/calendar');     
		$this->_client->setAccessType('offline');
		$this->_client->setApprovalPrompt('force');
		
	}
	
	public function getAccessToken(){
		return $this->_client->getAccessToken();
	}
	
	public function setAccessToken($token){
		$this->_client->setAccessToken($token);
	}
	
	public function clientAuthenticate($code){
		$this->_client->authenticate($code);
	}
	
	public function refreshToken($token){
		$this->_client->refreshToken($token);		
		return $this->_client->getAccessToken();
	}
	public function checkExpiredToken(){
		if ($this->_client->isAccessTokenExpired()) {
			return true;
		} else {
			return false;
		}
	}
	
	function getAllContacts(){
		$oauth = $this->_client->getAuth();	
		$access_token = json_decode($this->getAccessToken())->access_token;
		$pUrl = 'default/full?v=3&max-results=50000000&oauth_token='.$access_token;;
		/*$request = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/".$pUrl."&alt=json");*/
		$request = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/".$pUrl);
		$oauth->sign($request);
		$io = $this->_client->getIo();
		$ass = $io->makeRequest($request);
		$result_json = $io->makeRequest($request)->getResponseBody();
		/*$result = json_decode($result_json, true); */
		return $result_json;
	}
	
	
	
	public function createAuthUrl(){
		return $this->_client->createAuthUrl();
	}
	
	public function encodeRecipients($recipient){
		$recipientsCharset = 'utf-8';
		if (preg_match("/(.*)<(.*)>/", $recipient, $regs)) {
			$recipient = '=?' . $recipientsCharset . '?B?'.base64_encode($regs[1]).'?= <'.$regs[2].'>';
		}
		return $recipient;
	}
	
	public function sendMessage($data){
		$strMailContent = $data['message'];
		$strMailTextVersion = strip_tags($strMailContent, '');
		$strRawMessage = "";
		$boundary = uniqid(rand(), true);
		$boundary1 = uniqid(rand(), true);
		$subjectCharset = $charset = 'utf-8';
		$strToMailName = $data['to_name'];
		$strToMail = $data['to'];
		$strSesFromName = GOOGLE_EMAIL_NAME;
		$strSesFromEmail = GOOGLE_EMAIL;
		$strSubject = $data['subject'];		
		$strToMail = explode(',',$strToMail);
		if(isset($strToMail[0]) && !empty($strToMail[0])){
			$strRawMessage .= 'To: ';
			foreach($strToMail as $email){
				$email= trim($email);
				if(!empty($email)){					
					$strRawMessage .=  $email . " <" . $email . ">, " ;
				}				
			}
			$strRawMessage = substr($strRawMessage,0,-2);
			$strRawMessage .= "\r\n";
		}	
		if(!isset($data['from_email'])){
			$strRawMessage .= 'From: '. $strSesFromName . " <" . $strSesFromEmail . ">" . "\r\n";
		} else{
			$strRawMessage .= 'From: '. $data['from_name'] . " <" . $data['from_email'] . ">" . "\r\n";
		}
		
		if(isset($data['cc'])){
			$cc = explode(",",$data['cc']);
			if(isset($cc[0]) && !empty($cc[0])){
				$strRawMessage .= 'Cc: ';
				foreach($cc as $email){
					$email= trim($email);
					if(!empty($email)){					
						$strRawMessage .=  $email . " <" . $email . ">, " ;
					}
				}
				$strRawMessage = substr($strRawMessage,0,-2);
				$strRawMessage .= "\r\n";
			}
			
		}		
		if(isset($data['bcc'])){
			$bcc = explode(",",$data['bcc']);
			if(isset($bcc[0]) && !empty($bcc[0])){
				$strRawMessage .= 'Bcc: ';
				foreach($bcc as $email){
					$email= trim($email);
					if(!empty($email)){					
						$strRawMessage .=  $email . " <" . $email . ">, " ;
					}
				}
				$strRawMessage = substr($strRawMessage,0,-2);
				$strRawMessage .= "\r\n";
			}
			
		}		
		$strRawMessage .= 'Subject: ' . $strSubject . "\r\n";
		$strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
		
		if(!empty($data['thread_id'])){
			$reference = "";
			if(isset($data['reference']) && !empty($data['reference'])){
				$reference = $data['reference']." ".$data['message_id'];
			} else {
				$reference =$data['message_id'];
			}
			$strRawMessage .= 'References: ' . $reference . "\r\n";
			$strRawMessage .= 'In-Reply-To: ' . $data['message_id'] . "\r\n";
			
		}
		
		if(!empty($data['fileName']) && !empty($data['fileSrc'])){			
			$strRawMessage .= 'Content-type: multipart/mixed; boundary="' . $boundary1 . '"' . "\r\n\r\n";
		} else {
			$strRawMessage .= 'Content-type: multipart/mixed; boundary="' . $boundary . '"' . "\r\n\r\n";
		}
		
		
		if(!empty($data['fileName']) && !empty($data['fileSrc'])){
			$strRawMessage .= "\r\n--{$boundary1}\r\n";
			$strRawMessage .= 'Content-type: multipart/alternative; boundary="' . $boundary . '"' . "\r\n\r\n";
		}
		/*
		if(isset($data['href']) && count($data['href'])>0){
			$i=0;
			foreach($data['href'] as $drive){				
				$strMailTextVersion .=" \n".$drive['title']."\n".$drive['href']."\n ";
				if($i==count($data['href'])-1){
					$strMailTextVersion .="\n";
				}
				$i++;
			}	
		}
		$strRawMessage .= "\r\n--{$boundary}\r\n";
		$strRawMessage .= 'Content-Type: text/plain; charset=' . $charset . "\r\n\r\n";
		$strRawMessage .= $strMailTextVersion . "\r\n\r\n";*/
		$originalMessage = "";
		if(isset($data['href']) && count($data['href'])>0){
			$i=0;
			foreach($data['href'] as $drive){
				$strMailContent .=" <br/><div class='gmail_chipgmail_drive_chip'style='width:396px;height:18px;max-height:18px;background-color:#f5f5f5;padding:5px;color:#222;font-family:arial;font-style:normal;font-weight:bold;font-size:13px;border:1px solid #ddd;line-height:1'><a href='".$drive['href']."' target='_blank' style='display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-decoration:none;padding:1px 0px;border:none;width:100%'><img style='vertical-align: bottom; border: none;' src='".$drive['img']."'>&nbsp;<span dir='ltr' style='color:#15c;text-decoration:none;vertical-align:bottom'>".$drive['title']."</div></span></div> ";
				if($i==count($data['href'])-1){
					$strMailContent .="<br/>";
				}
				$i++;
			}	
		}
		$strRawMessage .= "\r\n--{$boundary}\r\n";
		$strRawMessage .= 'Content-Type: text/html; charset=' . $charset . "\r\n\r\n";
		$strRawMessage .= '<div dir="ltr">'.$strMailContent . "</div>\r\n\r\n";
		$strRawMessage .= "--{$boundary}--";	
		
		if(!empty($data['fileName']) && !empty($data['fileSrc'])){
			$filePath = $data['fileSrc'];
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
			$mimeType = finfo_file($finfo, $filePath);
			$fileName = $data['fileName'];
			$fileData = base64_encode(file_get_contents($filePath));
			
			$strRawMessage .= "\r\n--{$boundary1}\r\n";
			$strRawMessage .= 'Content-Type: '. $mimeType .'; name="'. $fileName .'";' . "\r\n"; 
			
			
			$strRawMessage .= 'Content-Description: ' . $fileName . ';' . "\r\n";
			$strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
			$strRawMessage .= 'Content-Disposition: attachment; filename="' . $fileName . '"; size=' . filesize($filePath). ';' . "\r\n\r\n";
			$strRawMessage .= chunk_split(base64_encode(file_get_contents($filePath)), 76, "\n") . "\r\n";
			$strRawMessage .= '--' . $boundary1 . "--\r\n";
		} 
		
	   
		
		$gmailMessage = new Google_Service_Gmail_Message();
		$this->_service = new Google_Service_Gmail($this->_client);
		$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
		$gmailMessage->setRaw($mime);
		if(!empty($data['thread_id'])){
			$gmailMessage->setThreadId($data['thread_id']);			
		}
        $objSentMsg = $this->_service->users_messages->send("me", $gmailMessage);
		return $objSentMsg;
	}
	
	public function modifyThread($threadId,$userId,$labelsToAdd){
		$this->_service = new Google_Service_Gmail($this->_client);
		$mods = new Google_Service_Gmail_ModifyThreadRequest();
		$mods->setAddLabelIds(array($labelsToAdd));
		try {
			$thread = $this->_service->users_threads->modify($userId, $threadId, $mods);
			return $thread;
		} catch (Exception $e) {
			/*print 'An error occurred: ' . $e->getMessage();*/
			return '';
       }
	}
	
	function getAllContact(){
		$req = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/default/full");
		$val = $this->_client->getIo()->makeRequest($req);
		$response = json_encode(simplexml_load_string($val->getResponseBody()));
		return $response;
	}
	
	function domainDirectory(){
		$service = new Google_Service_Directory($this->_client);
		$groups = $service->groups->listGroups(array('domain' => 'synpat.com'));
		return $groups;
	}
	
	public function modifyMessage($messageId,$userId,$labelsToAdd){
		$this->_service = new Google_Service_Gmail($this->_client);
		$mods = new Google_Service_Gmail_ModifyMessageRequest();
		$mods->setAddLabelIds(array($labelsToAdd));
		try {
			$message = $this->_service->users_messages->modify($userId, $messageId, $mods);
			return $message;
		} catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();
		}
	}
	
	public function modifyThreadRemove($threadId,$userId,$labelsToRemove){
		$this->_service = new Google_Service_Gmail($this->_client);
		$mods = new Google_Service_Gmail_ModifyThreadRequest();
		$mods->setRemoveLabelIds(array(strtoupper($labelsToRemove)));		
		try {
			$thread = $this->_service->users_threads->modify($userId, $threadId, $mods);
			return $thread;
		} catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();			
       }
	}
	
	public function modifyMessageRemove($threadId,$userId,$labelsToRemove){
		$this->_service = new Google_Service_Gmail($this->_client);
		$mods = new Google_Service_Gmail_ModifyMessageRequest();
		$mods->setRemoveLabelIds(array(strtoupper($labelsToRemove)));		
		try {
			$thread = $this->_service->users_messages->modify($userId, $threadId, $mods);
			return $thread;
		} catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();			
       }
	}
	
	public function getAuthUserEmail(){
		$service = new Google_Service_Oauth2($this->_client);
		return  $service->userinfo->get();
	}

	public function getColor(){
		$service = new Google_Service_Calendar($this->_client);
		return  $service->colors->get();
	}
	
	public function searchEmails($q){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParams = array();
        if(!empty($q)){
			$optParams['q'] = $q; 
			$lists = $this->_service->users_messages->listUsersMessages('me',$optParams);
			$messages = array();
			$attachmentArray = array();
			if(count($lists)>0){
				foreach($lists as $list){
					$messageId = $list->getId(); 
					$threadId = $list->getThreadId(); 
					$optParamsGet = array();
					$optParamsGet['format'] = 'full'; 
					$message = $this->_service->users_messages->get('me',$messageId,$optParamsGet);
					
					$headers = $message->getPayload()->getHeaders();
					$parts = $message->getPayload()->getParts();
					$label = array();
					foreach($message as $threadMessage){
						foreach($threadMessage->labelIds as $lbl){ 
							$label[] = $lbl;
						}					
					}
					$messages[] = array("message_id"=>$messageId,"thread_id"=>$threadId,'labelIds'=>array_unique($label),"header"=>$headers,'parts'=>$parts,'content'=>$message);
				}
			}
			return $messages;
		} else {
			return array();
		}			
	}
	
	
	public function newMessageList($maxResults = 0,$label = 'INBOX',$nextPageToken=null){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParams = array();
        if($maxResults>0){
			$optParams['maxResults'] = 100; 
		}	   
		if($nextPageToken){
			$optParams['pageToken'] = $nextPageToken;
		}
		$optParams['labelIds'] = $label; 
		$lists = $this->_service->users_messages->listUsersMessages('me',$optParams);
		/*$lists = $threads->getThreads();*/
		$pageToken = $lists->getNextPageToken();
		$messages = array();
		$attachmentArray = array();
		if(count($lists)>0){
			foreach($lists as $list){
				$messageId = $list->getId(); 
				$threadId = $list->getThreadId(); 
				$optParamsGet = array();
				$optParamsGet['format'] = 'full'; 
				$message = $this->_service->users_messages->get('me',$messageId,$optParamsGet);
				/*$allMessagesInThread = $message->getMessages();	*/
				$headers = $message->getPayload()->getHeaders();
				$parts = $message->getPayload()->getParts();
				$label = array();
				foreach($message as $threadMessage){
					foreach($threadMessage->labelIds as $lbl){ 
						$label[] = $lbl;
					}					
				}
				$messages[] = array("message_id"=>$messageId,"thread_id"=>$threadId,'labelIds'=>array_unique($label),"header"=>$headers,'parts'=>$parts,'content'=>$message,'pageToken'=>$pageToken);
			}
		}
		return $messages;
	}
	
	public function messageList($maxResults = 0,$label = 'INBOX'){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParams = array();
        if($maxResults>0){
			$optParams['maxResults'] = 30; 
		}	   
        $optParams['labelIds'] = $label; // Only show messages in Inbox
		$messages = $this->_service->users_threads->listUsersThreads('me',$optParams);
		$lists = $messages->getThreads();
		$messages = array();
		$attachmentArray = array();
		if(count($lists)>0){
			foreach($lists as $list){
				$messageId = $list->getId(); // Grab first Message
				$optParamsGet = array();
				$optParamsGet['format'] = 'full'; // Display message in payload
				$message = $this->_service->users_threads->get('me',$messageId,$optParamsGet);
				$allMessagesInThread = $message->getMessages();	
				$contentTT = $this->findThreadData($messageId);
				$messagePayload = $allMessagesInThread[0]->getPayload();
				$headers = $allMessagesInThread[0]->getPayload()->getHeaders();
				$parts = $allMessagesInThread[0]->getPayload()->getParts();
				$label = array();
				foreach($allMessagesInThread as $threadMessage){
					//array_push($threadMessage->labelIds,$label);
					foreach($threadMessage->labelIds as $lbl){
						$label[] = $lbl;
					}					
				}
				/*$halfPart = $parts[0]->getParts();	*/
				/*$body = $halfPart[1]->getBody();*/
				if(isset($parts) && count($parts)>0){
					$body = $parts[0]->getBody();
					$rawData = $body->data;				
					$sanitizedData = strtr($rawData,'-_', '+/');
					$decodedMessage = base64_decode($sanitizedData);
					$attachments = array();
					if($parts[0]->mimeType=='multipart/alternative'){
						for($i=1;$i<count($parts);$i++){
							$fileName = $parts[$i]->filename;
							$realAttachID = "";
							$partHeaders= $parts[$i]->getHeaders();
							$attachmentID = $parts[$i]->getBody()->getAttachmentId();
							if(!in_array($attachmentID,$attachmentArray)){
								foreach($partHeaders as $h){
									if($h->name=='X-Attachment-Id'){
										$realAttachID= $h->value;
									}
								}
								$mimeType = $parts[$i]->mimeType;
								
								$fileSize = $parts[$i]->getBody()->getSize();
								$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
							}
						}
					}
					$messages[] = array("message_id"=>$messageId,'labelIds'=>array_unique($label),"payload"=>$messagePayload,"header"=>$headers,"body"=>$body,"rawMessage"=>$decodedMessage,"attachments"=>$attachments,'content'=>$contentTT);			
				}
			}
		}		
		return $messages;
	}
	/*
	public function findThreadData($threadID){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParamsGet = array();
		$optParamsGet['format'] = 'full'; 
		$message = $this->_service->users_threads->get('me',$threadID,$optParamsGet);
		$allMessagesInThread = $message->getMessages();	
		$messages = array();
		$attachmentArray = array();
		foreach($allMessagesInThread as $threadMessage){
			$message = 	$this->_service->users_messages->get('me',$threadMessage->id,$optParamsGet);
			$messagePayload = $threadMessage->getPayload();
			$headers = $threadMessage->getPayload()->getHeaders();
			$parts = $threadMessage->getPayload()->getParts();
			$body = "";
			$attachments = array();			
			if(count($parts)>0){				
				if($parts[0]->mimeType=='text/plain' || $parts[0]->mimeType=='text/html'){
					if(isset($parts[1])){						
						$rawBody = $parts[1]->getBody();
					} else {						
						$rawBody = $parts[0]->getBody();
					}					
					
					$rawData = $rawBody->data;	
					$sanitizedData = strtr($rawData,'-_', '+/');
					$body = base64_decode($sanitizedData);					
				} else if($parts[0]->mimeType=='multipart/alternative'){
					$internalParts = $parts[0]->getParts();
					$rawBody = $internalParts[1]->getBody();
					$rawData = $rawBody->data;	
					$sanitizedData = strtr($rawData,'-_', '+/');
					$body = base64_decode($sanitizedData);
					for($i=1;$i<count($parts);$i++){
						$fileName = $parts[$i]->filename;
						$realAttachID = "";
						$partHeaders= $parts[$i]->getHeaders();
						$attachmentID = $parts[$i]->getBody()->getAttachmentId();
						if(!in_array($attachmentID,$attachmentArray)){
							foreach($partHeaders as $h){
								if($h->name=='X-Attachment-Id'){
									$realAttachID= $h->value;
								}
							}
							$mimeType = $parts[$i]->mimeType;
							
							$fileSize = $parts[$i]->getBody()->getSize();
							$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
						}
					}
				}
			} else {
				$rawBody = $threadMessage->getPayload()->getBody();
				$rawData = $rawBody->data;	
				$sanitizedData = strtr($rawData,'-_', '+/');
				$body = base64_decode($sanitizedData);
			}
			$messages[] = array("message_id"=>$threadMessage->id,'labelIds'=>$threadMessage->labelIds,"header"=>$headers,"parts"=>$parts,"body"=>$body,"attachments"=>$attachments,"content"=>$allMessagesInThread);			
		}
		return $messages;
	} */
	function isHTML($string){		
		if(preg_match("#</*(div|DIV|TABLE|table|P|p)[^>]*>#i", $string)){
			return true;
		} else {
			return false;
		}
	}
	
	public function findThreadData($threadID){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParamsGet = array();
		$optParamsGet['format'] = 'full'; 
		$threadMessage = $this->_service->users_messages->get('me',$threadID,$optParamsGet);
		$messages = array();
		$threadId = $threadMessage->getThreadId(); 
		$attachmentArray = array();
		/*foreach($allMessagesInThread as $threadMessage){*/
			/*$message = 	$this->_service->users_messages->get('me',$threadMessage->id,$optParamsGet);*/
			$messagePayload = $threadMessage->getPayload();
			$headers = $threadMessage->getPayload()->getHeaders();
			$parts = $threadMessage->getPayload()->getParts();
			$body = "";
			$attachments = array();	
			
			if(count($parts)>0){
				
				if($parts[0]->mimeType=='text/plain' || $parts[0]->mimeType=='text/html'){
					if(isset($parts[1])){						
						$rawBody = $parts[1]->getBody();
					} else {						
						$rawBody = $parts[0]->getBody();
					}					
					
					$rawData = $rawBody->data;	
					$sanitizedData = strtr($rawData,'-_', '+/');
					$body = base64_decode($sanitizedData);	
					if($this->isHTML($body)===false){
						$body = nl2br($body);
					}
				} else if($parts[0]->mimeType=='multipart/alternative' || $parts[0]->mimeType=='multipart/related'){
					$internalParts = $parts[0]->getParts();
					$rawBody = $internalParts[1]->getBody();					
					if($rawBody->size>0 && !empty($rawBody->data)){
						$rawData = $rawBody->data;
					} else {
						$innerParts = $internalParts[0]->getParts();						
						$rawBody = $innerParts[1]->getBody();
						$rawData = $rawBody->data;
					}
					
					if(empty($rawData)){
						if(isset($parts[1])){						
							$rawData = $parts[1]->getBody();
						} else {						
							$rawData = $parts[0]->getBody();
						}
					}
					$sanitizedData = strtr($rawData,'-_', '+/');
					$body = base64_decode($sanitizedData);
					if($this->isHTML($body)===false){
						$body = nl2br($body);
					}
					for($i=1;$i<count($parts);$i++){ 
						$fileName = $parts[$i]->filename;
						$realAttachID = "";
						$partHeaders= $parts[$i]->getHeaders();
						$attachmentID = $parts[$i]->getBody()->getAttachmentId();
						if(!in_array($attachmentID,$attachmentArray)){
							$attachmentArray[] = $attachmentID ;
							foreach($partHeaders as $h){
								if($h->name=='X-Attachment-Id'){
									$realAttachID= $h->value;
								}
							}
							$mimeType = $parts[$i]->mimeType;
							
							$fileSize = $parts[$i]->getBody()->getSize();
							$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
						}
					}
				}
			} else {
				
				$rawBody = $threadMessage->getPayload()->getBody();
				$rawData = $rawBody->data;	
				$sanitizedData = strtr($rawData,'-_', '+/');
				$body = base64_decode($sanitizedData);
				$body = nl2br($body);
			}
			
			$messages[] = array("thread_id"=>$threadId,"message_id"=>$threadMessage->id,'labelIds'=>$threadMessage->labelIds,"header"=>$headers,"parts"=>$parts,"body"=>$body,"attachments"=>$attachments,"content"=>$threadMessage);			
		/*}*/
		return $messages;
	}
	
	
	
	
	
	
	
	function downloadAttachments($messageID,$attachmentID){
		$this->_service = new Google_Service_Gmail($this->_client);
		$attachments = $this->_service->users_messages_attachments->get('me',$messageID,$attachmentID);
		return $attachments;
	}
	
	public function listLabels(){
		$labels = array();
		try{
			$this->_service = new Google_Service_Gmail($this->_client);
			$labelsResponse = $this->_service->users_labels->listUsersLabels("me");
			if ($labelsResponse->getLabels()) {
				$list = $labelsResponse->getLabels();
				foreach($list as $lbl){
					$labels[$lbl->getId()] = $lbl->getName();
				}
			}
		} catch(Excetion $e){
			
		}
		return $labels;
	}
	
	public function createLabel($labelName){
		$label = new Google_Service_Gmail_Label();
		$label->setName($labelName);
		/*
		$label->setLabelListVisibility("labelShow");
		$label->setMessageListVisibility("show");*/
		try {
			$this->_service = new Google_Service_Gmail($this->_client);
			$label = $this->_service->users_labels->create("me", $label);
		} catch (Exception $e) {
			/*echo $e->getMessage();*/
		}
		return $label;
	}
	public function listMessages(){
	try
    {
        //$result = $this->_service->users_messages->listUsersMessages($userID);
		$optParams = array();
        $optParams['maxResults'] = 5; // Return Only 5 Messages
        $optParams['labelIds'] = 'INBOX'; // Only show messages in Inbox
        $messages = $this->_service->users_messages->listUsersMessages('me',$optParams);
        $result = $messages->getMessages();
        //$messageId = $list[0]->getId(); // Grab first Message

		/*
        $optParamsGet = [];
        $optParamsGet['format'] = 'full'; // Display message in payload
        $message = $service->users_messages->get('me',$messageId,$optParamsGet);
        $messagePayload = $message->getPayload();
        $headers = $message->getPayload()->getHeaders();
        $parts = $message->getPayload()->getParts();

        $body = $parts[0]['body'];
        $rawData = $body->data;
        $sanitizedData = strtr($rawData,'-_', '+/');
        $decodedMessage = base64_decode($sanitizedData);

        var_dump($decodedMessage);*/
    }
    catch (Exception $e)
    {
        throw new Exception("Gmail API error: ".$e->getMessage()."<br />");
    }
    return $result; 
	}
}

class DriveDomainDirectoryHelper{
	protected $scope = array('https://www.googleapis.com/auth/admin.directory.group');
	
	private $_service;
	
	 
	private $_delegatedAdmin = "webmaster@synpat.com";
	
	public function __construct() {
		$cred = new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) );
		$creds->sub = $this->_delegatedAdmin;
		
		$client = new Google_Client();
		$client->setClientId( CLIENT_ID );
		$client->setAssertionCredentials($creds);
		$client->getAuth()->refreshTokenWithAssertion();
		$this->_service = new Google_Service_Directory($client);
	}
	
	
	public function __get( $name ) {
		return $this->_service->$name;
	}
	
	public function addMemberInDomainDirectory($data = array()){
		$member = new Google_Service_Directory_Member($data);
	}
}

class TaskServiceHelper{
	protected $scope = array('https://www.googleapis.com/auth/tasks');
	
	private $_service;
	
	public function __construct() {
		$client = new Google_Client();
		$client->setClientId( CLIENT_ID );
		
		$client->setAssertionCredentials( new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) )
		);
		
		$this->_service = new Google_Service_Tasks($client);
	}
	
	public function __get( $name ) {
		return $this->_service->$name;
	}
	
	public function taskList(){
		$getList = $this->_service->tasklists->listTasklists();
		return $getList;
	}
	
	public function findTask($taskID){
		$getData = $this->_service->tasks->listTasks($taskID);
		return $getData;
	}
	
	function addList($data){
		$tasklist = new Google_Service_Tasks_TaskList();
		$tasklist->setTitle($data['title']);
		$result = $this->_service->tasklists->insert($tasklist);
		return $result;
	}
	
	public function addTask($id,$data){
		$task = new Google_Service_Tasks_Task();
		$task->setTitle($data['title']);
		$task->setDue(date(DATE_RFC3339, strtotime($data['due_date'])));
		$result = $this->_service->tasks->insert($id, $task);
		return $result;
	}
	
}
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/CellEntry.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/CellFeed.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/Exception.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/ListEntry.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/ListFeed.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/ServiceRequestFactory.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/ServiceRequestInterface.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/DefaultServiceRequest.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/Spreadsheet.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/SpreadsheetFeed.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/SpreadsheetService.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/UnauthorizedException.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/Util.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/Worksheet.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/WorksheetFeed.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/Batch/BatchRequest.php');  
require_once realpath(dirname(__FILE__) . '/src/Google/Spreadsheet/Batch/BatchResponse.php'); 
class SpreadsheetServiceHelper{
	protected $scope = array('https://spreadsheets.google.com/feeds','https://docs.google.com/feeds');
	
	private $_service;
	
	private $_spreadSheetService;
	
	private $_token = '';
	
	public function __construct() {
		$client = new Google_Client();
		$client->setClientId( CLIENT_ID );
		$gauth = new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) );
		$client->setAssertionCredentials($gauth);
		if($client->getAuth()->isAccessTokenExpired()) {
			$client->getAuth()->refreshTokenWithAssertion($gauth);
		}
		$token = $client->getAccessToken();
		if ($token) {
			$arr = json_decode($token);
			if(isset($arr->access_token)) {
				$this->_token = $arr->access_token;
			}
		}		
	}
	
	public function getAccessToken(){
		return $this->_token;
	}
	
	
	public function getSpreadSheet($sheetName,$workSheetName){		 
		$serviceRequest = new Google\Spreadsheet\DefaultServiceRequest($this->getAccessToken());
		Google\Spreadsheet\ServiceRequestFactory::setInstance($serviceRequest);
		$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
		$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
		$spreadsheet = $spreadsheetFeed->getByTitle($sheetName);
		$worksheetFeed = $spreadsheet->getWorksheets();
		$worksheet = $worksheetFeed->getByTitle($workSheetName);
		$listFeed = $worksheet->getListFeed();
		return $listFeed;
	}
	
	
	
	public function getSpreadsheetById($id){
		$serviceRequest = new Google\Spreadsheet\DefaultServiceRequest($this->getAccessToken());
		Google\Spreadsheet\ServiceRequestFactory::setInstance($serviceRequest);
		$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
		$this->_spreadSheetService = $spreadsheetService->getSpreadsheetById($id);
		return $this->_spreadSheetService;
	}
	
	public function getAllWorkSheets(){
		$worksheetFeedXML = $this->_spreadSheetService->getWorksheets();
		return $worksheetFeedXML->getAllSheets();
	}
	
	public function getWorkSheetByName($workSheetName){
		$worksheetFeedXML = $this->_spreadSheetService->getWorksheets();
		$worksheet = $worksheetFeedXML->getByTitle($workSheetName);
		$listFeed = $worksheet->getListFeed();
		return $listFeed;
	}
	
	public function getAllRows($allEntries){
		$rowArray = array();
		if(count($allEntries)>0){
			foreach($allEntries as $entry){
				$values = $entry->getValues();
				$rowArray[] = $values;
			}
		}
		return $rowArray;
	}
}

class SignatureServiceHelper {
	
	protected $scope = array('https://apps-apis.google.com/a/feeds/emailsettings/2.0/');
	
	private $_service;
	
	private $_token = '';
	
	public function __construct() {
		$client = new Google_Client();
		$client->setClientId( CLIENT_ID );
		$gauth = new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) );
		$gauth->sub = "admin@synpat.com";
		$client->setAssertionCredentials($gauth);
		if($client->getAuth()->isAccessTokenExpired()) {
			$client->getAuth()->refreshTokenWithAssertion($gauth);
		}
		$token = $client->getAccessToken();
		if ($token) {
			$arr = json_decode($token);
			if(isset($arr->access_token)) {
				$this->_token = $arr->access_token;
			}
		}		
	}
	
	public function getAccessToken(){
		return 'Authorization: Bearer ' . $this->_token;
	}
	
	public function putUserSignature($signature,$email){
		$authString = $this->getAccessToken();
		$stringUsername = explode('@',$email);
		if($stringUsername[1]=="synpat.com"){
			
			$service_url = 'https://apps-apis.google.com/a/feeds/emailsettings/2.0/synpat.com/'.$stringUsername[0].'/signature';
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
			curl_setopt($curl, CURLOPT_HTTPGET, 1);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $signature);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($authString));
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$curl_response = curl_exec($curl);
			curl_close($curl);
			echo "<pre>";
			print_r($curl_response);
			die;
			if($curl_response){
				return $curl_response;
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
	
	public function getUserSignature($email){
		$authString = $this->getAccessToken();
		$stringUsername = explode('@',$email);
		if($stringUsername[1]=="synpat.com"){
			$stringUsername[0]="admin";
			$service_url = 'https://apps-apis.google.com/a/feeds/emailsettings/2.0/synpat.com/'.$stringUsername[0].'/signature';
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
			curl_setopt($curl, CURLOPT_HTTPGET, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($authString));
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$curl_response = curl_exec($curl);
			curl_close($curl);
			if($curl_response){
				return $curl_response;
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
}


class ContactServiceHelper{
	private $_client;
	private $scope = array('https://www.googleapis.com/auth/userinfo.email','https://apps-apis.google.com/a/feeds/groups/','https://apps-apis.google.com/a/feeds/alias/','https://apps-apis.google.com/a/feeds/user/','https://www.google.com/m8/feeds/','https://www.google.com/m8/feeds/user/');
	private $_service;
	
	private $_token = '';
	
	
	
	public function __construct() {
		$client = new Google_Client();
		$client->setClientId( CLIENT_ID );
		$gauth = new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) );
		/*$gauth->sub = "webmaster@synpat.com";*/
		$client->setAssertionCredentials($gauth);
		if($client->getAuth()->isAccessTokenExpired()) {
			$client->getAuth()->refreshTokenWithAssertion($gauth);
		}
		
		$this->_client = $client;
		$token = $client->getAccessToken();
		if ($token) {
			$arr = json_decode($token);
			if(isset($arr->access_token)) {
				$this->_token = $arr->access_token;
			}
		}		
	}
	
	public function findContact($ID){
		$oauth = $this->_client->getAuth();
		$request = new Google_Http_Request($ID);
		$oauth->sign($request);
		$io = $this->_client->getIo();
		$result = $io->makeRequest($request)->getResponseBody();
		return $result;
	}
	
	public function allContacts(){
		$oauth = $this->_client->getAuth();		
		$pUrl = 'default/full?v=3&max-results=50000000';
		/*$request = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/".$pUrl."&alt=json");*/
		$request = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/".$pUrl);
		$oauth->sign($request);
		$io = $this->_client->getIo();
		$result_json = $io->makeRequest($request)->getResponseBody();
		/*$result = json_decode($result_json, true); */
		return $result_json;
		
		
		/*
		if(isset($result["feed"]["entry"])){
			return $result["feed"]["entry"];
		} else {
			return array();
		}*/
		/*
		$contacts = array();
		foreach($result["feed"]["entry"] as $entry){
			if(!isset($entry['gd$email'])){
				$entry['gd$email'] = array();
			}

			if(!isset($entry['gd$phoneNumber'])||empty($entry['gd$phoneNumber'])){
				$entry['gd$phoneNumber'] = array();
			}
			
			$phones = array();
			$emails = array();

			foreach($entry['gd$phoneNumber'] as $phone)	{
				$phone['$t'] = preg_replace('/\+33/', "0", $phone['$t']);
				$phone['$t'] = preg_replace('/\-/', '', $phone['$t']);
				$phones[] = $phone['$t'];
			}

			foreach($entry['gd$email'] as $email){
				$emails[] = $email['address'];
			}
			
			$contacts[] = array(
				"fullName"=>utf8_decode($entry['title']['$t']),
				"phones"=>$phones,
				"emails"=>$emails
			);
		}
		return $contacts;*/
	}
	
	public function addContact(){
		$contact = "
			<atom:entry xmlns:atom='http://www.w3.org/2005/Atom'
				xmlns:gd='http://schemas.google.com/g/2005'
				xmlns:gContact='http://schemas.google.com/contact/2008'>
			  <atom:category scheme='http://schemas.google.com/g/2005#kind'
				term='http://schemas.google.com/contact/2008#contact'/>
			  <gd:name>
				 <gd:givenName>HELLO</gd:givenName>
				 <gd:familyName>WORLD</gd:familyName>
				 <gd:fullName>Hello World</gd:fullName>
			  </gd:name>
			  <atom:content type='text'>Notes</atom:content>
			  <gd:email rel='http://schemas.google.com/g/2005#work'
				primary='true'
				address='liz@gmail.com' displayName='E. Bennet'/>
			  <gd:email rel='http://schemas.google.com/g/2005#home'
				address='liz@example.org'/>
			  <gd:phoneNumber rel='http://schemas.google.com/g/2005#work'
				primary='true'>
				(206)555-1212
			  </gd:phoneNumber>
			  <gd:phoneNumber rel='http://schemas.google.com/g/2005#home'>
				(206)555-1213
			  </gd:phoneNumber>
			  <gd:im address='liz@gmail.com'
				protocol='http://schemas.google.com/g/2005#GOOGLE_TALK'
				primary='true'
				rel='http://schemas.google.com/g/2005#home'/>
			  <gd:structuredPostalAddress
				  rel='http://schemas.google.com/g/2005#work'
				  primary='true'>
				<gd:city>Mountain View</gd:city>
				<gd:street>1600 Amphitheatre Pkwy</gd:street>
				<gd:region>CA</gd:region>
				<gd:postcode>94043</gd:postcode>
				<gd:country>United States</gd:country>
				<gd:formattedAddress>
				  1600 Amphitheatre Pkwy Mountain View
				</gd:formattedAddress>
			  </gd:structuredPostalAddress>
			 <gContact:groupMembershipInfo deleted='false'
					href='http://www.google.com/m8/feeds/groups/" . SERVICE_ACCOUNT_NAME . "/base/6'/>
			</atom:entry>
			";
		$len = strlen($contact);
		$add = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/default/full/");
		$add->setRequestMethod("POST");
		$add->setPostBody($contact);
		$add->setRequestHeaders(array(
			'content-length' => $len,
			'GData-Version' => '3.0',
			'content-type' => 'application/atom+xml; charset=UTF-8; type=feed'
		));
		$add = $this->_client->getAuth()->sign($add);
		$submit = $this->_client->getIo()->executeRequest($add);
		return $submit;
		/*echo "<pre>";
		print_r($submit);
		die;
		
		$sub_response = $submit->getResponseBody();
		$parsed    = simplexml_load_string($sub_response);
		$client_id = explode("base/", $parsed->id);
		return $client_id;*/
	}
	
	
	function addGContact($getData){
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
		$note = $doc->createElement("atom:content",$getData['invitee']['note']);
		$note->setAttribute("type","text");
		$entry->appendChild($note);
		/*Note*/
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
		$group->setAttribute('href' ,'http://www.google.com/m8/feeds/groups/'.SERVICE_ACCOUNT_NAME.'/base/6');				
		$group->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');				
		$group->setAttribute('deleted' ,false);				
		$entry->appendChild($group);		
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
		$len = strlen($doc->saveXML());
		$add = new Google_Http_Request("https://www.google.com/m8/feeds/contacts/default/full/");
		$add->setRequestMethod("POST");
		$add->setPostBody($doc->saveXML());
		$add->setRequestHeaders(array(
			'content-length' => $len,
			'GData-Version' => '3.0',
			'content-type' => 'application/atom+xml; charset=UTF-8; type=feed'
		));
		$add = $this->_client->getAuth()->sign($add);
		$submit = $this->_client->getIo()->executeRequest($add);
		return $submit;
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
		$note = $doc->createElement("atom:content",$getData['invitee']['note']);
		$note->setAttribute("type","text");
		$entry->appendChild($note);
		/*Note*/
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
		$group->setAttribute('href' ,'http://www.google.com/m8/feeds/groups/'.SERVICE_ACCOUNT_NAME.'/base/6');				
		$group->setAttribute('xmlns' ,'http://schemas.google.com/contact/2008');				
		$group->setAttribute('deleted' ,false);				
		$entry->appendChild($group);	
		
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
		$len = strlen($doc->saveXML());
		$add = new Google_Http_Request($getData['invitee']['id']);
		$add->setRequestMethod("PUT");
		$add->setPostBody($doc->saveXML());
		$add->setRequestHeaders(array(
			'content-length' => $len,
			'GData-Version' => '3.0',
			'If-Match'=>'*',
			'content-type' => 'application/atom+xml; charset=UTF-8; type=feed'
		));
		$add = $this->_client->getAuth()->sign($add);
		$submit = $this->_client->getIo()->executeRequest($add);
		return $submit;		
	}
	
	function deleteContact($ID){
		try{		
			$request = new Google_Http_Request($ID);
			$request->setRequestMethod("DELETE");
			$request->setRequestHeaders(array(
				'GData-Version' => '3.0',
				'If-Match'=>'*',
				'content-type' => 'application/atom+xml; charset=UTF-8; type=feed'
			));
			$delete = $this->_client->getAuth()->sign($request);
			$submit = $this->_client->getIo()->executeRequest($delete);			
			return $submit;		
		} catch(Exception $e){
			return '';		
		}	
	}
}

class CalendarServiceHelper{
	private $_service;
	
	protected $scope = array('https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.readonly');
	
	private $_client;
	
	public function __construct() {
		$this->_client = new Google_Client();
		$this->_client->setClientId( CLIENT_ID );
		
		$this->_client->setAssertionCredentials( new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) )
		);
		if($this->_client->getAuth()->isAccessTokenExpired()) {	 	
			$this->_client->getAuth()->refreshTokenWithAssertion(new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH )));	 	
		}
		$this->_service = new Google_Service_Calendar($this->_client);
	}
	
	public function getColor(){
		$_service = new Google_Service_Calendar($this->_client);
		return  $_service->colors->get();
	}
	
	function insert_event($event){
		$eventC = new Google_Service_Calendar_Event($event);
		$calendarId = 'primary';
		$service = new Google_Service_Calendar($this->_client);
		$eventR = $service->events->insert($calendarId, $eventC);
		return $eventR;
	}
	
	public function getCalendarList(){
		$calendarList = array();
		do {
			try {
			  $parameters = array();
			  if (isset($pageToken)) {				
				$parameters['pageToken'] = $pageToken;
			  }
			  $children = $this->_service->calendarList->listCalendarList($parameters);
			  foreach ($children->getItems() as $child) {
				$calendarList[] = $child;
			  }
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
			  $pageToken = NULL;
			}
		} while ($pageToken);		
		return $calendarList;
	}
	
	public function getEventsList($calendarID,$timeMin){
		$eventList = array();
		do {
			try {
			  $parameters = array('timeMin'=>$timeMin,'timeZone'=>date_default_timezone_get(),'orderBy'=>'updated','maxResults'=>2500);
			  if (isset($pageToken)) {				
				$parameters['pageToken'] = $pageToken;
			  }
			  $children = $this->_service->events->listEvents($calendarID,$parameters);
			  foreach ($children->getItems() as $child) {
				$eventList[] = $child;
			  }
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
			  $pageToken = NULL;
			}
		} while ($pageToken);
		return $eventList;
	}
}

class DriveServiceHelper {
	
	protected $scope = array('https://www.googleapis.com/auth/drive');
	
	private $_service;
	
	public function __construct() {
		$client = new Google_Client();
		$client->setClientId( CLIENT_ID );
		
		$client->setAssertionCredentials( new Google_Auth_AssertionCredentials(
				SERVICE_ACCOUNT_NAME,
				$this->scope,
				file_get_contents( KEY_PATH ) )
		);
		
		$this->_service = new Google_Service_Drive($client);
	}
	
	
	public function __get( $name ) {
		return $this->_service->$name;
	}
	
	
	
	public function insertFile($name,$mimeType,$filename,Google_Service_Drive_ParentReference $fileParent = null,$convert=false){
		$file = new Google_Service_Drive_DriveFile();
		$file->setTitle( $name );
		$file->setDescription('');
		$file->setMimeType( $mimeType );
		if( $fileParent ) {
			$file->setParents( array( $fileParent ) );			
		}
		$content = file_get_contents($filename);
		
		$createdFile = $this->_service->files->insert($file, array(
				'data' => $content,
				'mimeType' => $mimeType,
				'uploadType' =>'multipart',
				'convert'=>$convert
		));
		return $createdFile;
	}
	
	public function createFile( $name, $mime, $description, $content, Google_Service_Drive_ParentReference $fileParent = null,$convert=false ) {		
		$file = new Google_Service_Drive_DriveFile();
		$file->setTitle( $name );
		$file->setDescription( $description );
		$file->setMimeType( $mime );
		if( $fileParent ) {
			$file->setParents( array( $fileParent ) );
			
		}
		$createdFile = $this->_service->files->insert($file, array(
				'data' => $content,
				'mimeType' => $mime,
				'uploadType' =>'multipart',
				'convert'=>$convert
		));
		
		return $createdFile['id'];
	}
	
	public function createFileFromPath( $path, $description, Google_Service_Drive_ParentReference $fileParent = null ) {
		$fi = new finfo( FILEINFO_MIME );
		//$mimeType = explode( ';', $fi->buffer(file_get_contents($path)));		
		$mimeType = 'application/vnd.google-apps.document';
		//$fileName = preg_replace('/.*\//', '', $path );
		$fileA = pathinfo($path);
		return $this->createFile( $fileA['filename'], $mimeType, $description, file_get_contents($path), $fileParent );
	}
	
	
	public function createFolder( $name ) {
		return $this->createFile( $name, 'application/vnd.google-apps.folder', null, null);
	}
	
	public function createSubFolder($name, Google_Service_Drive_ParentReference $fileParent=null){
		return $this->createFile( $name, 'application/vnd.google-apps.folder', null, null,$fileParent);
	}
	
	public function createPresentaionFile($name,Google_Service_Drive_ParentReference $fileParent=null){
		return $this->createFile( $name, 'application/vnd.google-apps.presentation', null, null,$fileParent);
	}
	public function createSpreadSheetFile($name,Google_Service_Drive_ParentReference $fileParent=null){
		return $this->createFile( $name, 'application/vnd.google-apps.spreadsheet', null, null,$fileParent);
	}
	
	public function getPrmissionsList($fileId){
		try {
			$permissions = $this->_service->permissions->listPermissions($fileId);
			return $permissions->getItems();
		  } catch (Exception $e) {
			
		  }
		  return array();
	}
	
	public function deletePermission($fileId,$permissionId){
		 try {
			$this->_service->permissions->delete($fileId, $permissionId);
		  } catch (Exception $e) {
			
		  }
	}
	
	public function setPermissions( $fileId, $value, $role = 'writer', $type = 'user' ) {
		$perm = new Google_Service_Drive_Permission();
		$perm->setValue( $value );
		$perm->setType( $type );
		$perm->setRole( $role );
		
		$this->_service->permissions->insert($fileId, $perm);
	}
	public function setAdditionalPermissions( $fileId, $value, $role = 'reader', $type = 'user' ,$optParams=array()) {
		$perm = new Google_Service_Drive_Permission();
		$perm->setValue( $value );
		$perm->setType( $type );
		$perm->setRole( $role );
		$perm->setWithLink( true);
		$perm->setAdditionalRoles( array('commenter'));
		
		$this->_service->permissions->insert($fileId, $perm,$optParams);
	}
	
	function downloadFile($file) {
	  $downloadUrl = $file->getDownloadUrl();
	  if ($downloadUrl) {
		$request = new Google_Http_Request($downloadUrl, 'GET', null, null);
		$httpRequest = $this->_service->getClient()->getAuth()->authenticatedRequest($request);
		if ($httpRequest->getResponseHttpCode() == 200) {
		  return $httpRequest->getResponseBody();
		} else {
		  // An error occurred.
		  return null;
		}
	  } else {
		// The file doesn't have any content stored on Drive.
		return null;
	  }
	}
	
	public function getFileIdByName( $name ) {		
		$files = $this->_service->files->listFiles();
		foreach( $files['items'] as $item ) {
			if( $item['title'] == $name ) {
				return $item['id'];
			}
		}		
		return false;
	}
	
	public function searchFileIDFromChildern($folderId,$q=null){
		$listFiles = array();
		do {
			try {
			  $parameters = array();
			  if (isset($pageToken)) {				
				$parameters['pageToken'] = $pageToken;
			  }
			  if($q!=null){
				  $parameters['q'] = $q;
			  }
			  $children = $this->_service->children->listChildren($folderId, $parameters);
			  foreach ($children->getItems() as $child) {
				$listFiles[] = $this->getFileInfo($child->id);
			  }
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
			  $pageToken = NULL;
			}
		} while ($pageToken);		
		return $listFiles;
	}
	

	
	public function getFileIDFromChildern($folderId){
		$listFiles = array();
		do {
			try { 
			  $parameters = array();
			  if (isset($pageToken)) {				
				$parameters['pageToken'] = $pageToken;
			  }
			  $children = $this->_service->children->listChildren($folderId, $parameters);
			  
			  foreach ($children->getItems() as $child) {
				$childFileInfo =   $this->getFileInfo($child->id);
				$listFiles[] = $childFileInfo;
			  }
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
			  $pageToken = NULL;
			}
		} while ($pageToken);
		
		return $listFiles;
	}
	
	public function getFileNameFromChildern($folderId,$name){
		do {
			try {
			  $parameters = array();
			  if (isset($pageToken)) {				
				$parameters['pageToken'] = $pageToken;
			  }
			  $children = $this->_service->children->listChildren($folderId, $parameters);
				
			  foreach ($children->getItems() as $child) {
				$getInfo = $this->getFileInfo($child->id);
				if( $getInfo->title == $name ) {
					return $getInfo;
				}
				//print 'File Id: ' . $child->getId();
			  }
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
				return false;
			}
		} while ($pageToken);
		return false;
	}
	
	public function getFileInfo($fileID){
		return $this->_service->files->get($fileID);
	}
	
	public function copyFile($orgFileID,$name,Google_Service_Drive_ParentReference $fileParent=null){
		$file = new Google_Service_Drive_DriveFile();
		$file->setTitle( $name );
		if( $fileParent ) {
			$file->setParents( array( $fileParent ) );			
		}
		return $this->_service->files->copy($orgFileID,$file);
	}
	
	public function moveFile($fileId,$newParentId) {
		try {
			$file = new Google_Service_Drive_DriveFile();
			$fileParent = new Google_Service_Drive_ParentReference();
			$fileParent->setId($newParentId);
			$file->setParents(array($fileParent));
			$updatedFile = $this->_service->files->patch($fileId, $file);
			return $updatedFile;
		} catch (Exception $e) {
			return '';
		}
	}
	
	public function renameFile($fileId, $newTitle) {
		try {
			$file = new Google_Service_Drive_DriveFile();
			$file->setTitle($newTitle);

			$updatedFile = $this->_service->files->patch($fileId, $file, array(
			  'fields' => 'title'
			));
			return $updatedFile;
		} catch (Exception $e) {
			return '';
		}
	}
	
	public function getAllFiles(){
		$files = $this->_service->files->listFiles();
		$data = array();
		if(count($files['items'])>0){
			foreach($files['items'] as $item){
				if($item['mimeType']!='application/vnd.google-apps.folder'){
					$data[] = $item;
				}
			}			
		}
		return $data;
	}
	public function getAll(){
		$files = $this->_service->files->listFiles();
		$data = array();
		if(count($files['items'])>0){
			foreach($files['items'] as $item){
				if($item['mimeType']=='application/vnd.google-apps.folder'){
					$data[] = $item;
				}
			}			
		}   
		return $data;
	}
	function removeParentFileFromFolder( $folderId, $fileId) {
		try {
			$this->_service->parents->delete($fileId, $folderId);
		} catch (Exception $e) {
			 
		}
	}
	function removeFileFromFolder($folderId, $fileId) {
		try {
			$this->_service->children->delete($folderId, $fileId);
		} catch (Exception $e) {
			
		}
	}
	
	function removeFile($fileId){
		try {
			$this->_service->files->delete($fileId);
		} catch (Exception $e) {
			
		}
		
	}
	
	function deleteFilesFolder($folderID){
		$getAllDataInFolder = $this->getFileIDFromChildern($folderID);
		
		if(count($getAllDataInFolder)>0){
			foreach($getAllDataInFolder as $child){
				$this->removeFileFromFolder($folderID,$child->getId());
			}			
		}
		/*Delete Folder From Lead*/
		$this->removeFileFromFolder('0B61I0m5ybHrFLUN0UG1LLW8wSXM',$folderID);
		$this->removeParentFileFromFolder('0B61I0m5ybHrFLUN0UG1LLW8wSXM',$folderID);
		return true;		
	}
}

?>
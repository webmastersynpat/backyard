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
	public $gmailClientID = " 663686724086-kp4un2tsai1e5cr0bsbuuavqgnl9ra16.apps.googleusercontent.com";
	public $gmailClientSecret ="oU1ImLe1q6TBENwdK15Rpr5C";
	public $redirectURI = "https://backyard.synpat.com/leads/market/";
	public $gmailDeveloperKey = " AIzaSyDhqL9QYWlPwv2M-w2MKWyiOo98tDihVhY";
	protected $scope = array('https://mail.google.com','https://www.googleapis.com/auth/admin.directory.user.readonly');
	public function __construct() {
		$this->_client = new Google_Client();
		$this->_client->setAuthConfigFile(CLIENT_SECRET);
		$this->_client->addScope('email');
		//$client->addScope('profile');     
		$this->_client->addScope('https://mail.google.com');           
		$this->_client->addScope('https://www.googleapis.com/auth/gmail.compose');           
		$this->_client->addScope('https://www.googleapis.com/auth/gmail.modify');           
		$this->_client->addScope('https://www.googleapis.com/auth/gmail.readonly');           
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
				$strRawMessage .=  $email . " <" . $email . ">, " ;
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
					$strRawMessage .=  $email . " <" . $email . ">, " ;
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
					$strRawMessage .=  $email . " <" . $email . ">, " ;
				}
				$strRawMessage = substr($strRawMessage,0,-2);
				$strRawMessage .= "\r\n";
			}
			
		}		
		$strRawMessage .= 'Subject: ' . $strSubject . "\r\n";
		$strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
		if(!empty($data['thread_id'])){
			$strRawMessage .= 'References: ' . $data['message_id'] . "\r\n";
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
		/*$labelsResponse = $this->_service->users_labels->listUsersLabels($userId);
		 $labels = array();
		if ($labelsResponse->getLabels()) {
		  $labels = array_merge($labels, $labelsResponse->getLabels());
		}
		echo "<pre>";
		print_r( $labels);
		die;*/
		$mods = new Google_Service_Gmail_ModifyThreadRequest();
		$mods->setAddLabelIds(array($labelsToAdd));
		/*$mods->setRemoveLabelIds($labelsToRemove);*/
		
		try {
			$thread = $this->_service->users_threads->modify($userId, $threadId, $mods);
			return $thread;
		} catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();
			/*return '';*/
       }
	}
	
	public function modifyThreadRemove($threadId,$userId,$labelsToRemove){
		$this->_service = new Google_Service_Gmail($this->_client);
		/*$labelsResponse = $this->_service->users_labels->listUsersLabels($userId);
		 $labels = array();
		if ($labelsResponse->getLabels()) {
		  $labels = array_merge($labels, $labelsResponse->getLabels());
		}
		echo "<pre>";
		print_r( $labels);
		die;*/
		$mods = new Google_Service_Gmail_ModifyThreadRequest();
		/*$mods->setAddLabelIds(array(strtoupper($labelsToAdd)));*/
		$mods->setRemoveLabelIds(array(strtoupper($labelsToRemove)));
		
		try {
			$thread = $this->_service->users_threads->modify($userId, $threadId, $mods);
			return $thread;
		} catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();
			/*return '';*/
       }
	}
	
	public function getAuthUserEmail(){
		$service = new Google_Service_Oauth2($this->_client);
		return  $service->userinfo->get();
	}
	
	public function messageList($maxResults = 0,$label = 'INBOX'){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParams = [];
        if($maxResults>0){
			/*$optParams['maxResults'] = $maxResults; */
			$optParams['maxResults'] = 30; 
		}	   
		
        $optParams['labelIds'] = $label; // Only show messages in Inbox
       // $messages = $this->_service->users_messages->listUsersMessages('me',$optParams);
		
		$messages = $this->_service->users_threads->listUsersThreads('me',$optParams);
		/*
		$listThreads = $messages->getThreads();
		
		echo "<pre>";
		print_r($listThreads);
		$optParamsGet = [];
				$optParamsGet['format'] = 'full'; // Display message in payload
		$firstThread = $listThreads[0]->getId();
		$message = $this->_service->users_threads->get('me',$firstThread,$optParamsGet);
		print_r($message);
		die;
		*/
        //$lists = $messages->getMessages();
		$lists = $messages->getThreads();
		$messages = array();
		if(count($lists)>0){
			foreach($lists as $list){
				$messageId = $list->getId(); // Grab first Message
				$optParamsGet = [];
				$optParamsGet['format'] = 'full'; // Display message in payload
				$message = $this->_service->users_threads->get('me',$messageId,$optParamsGet);
				$allMessagesInThread = $message->getMessages();				
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
							foreach($partHeaders as $h){
								if($h->name=='X-Attachment-Id'){
									$realAttachID= $h->value;
								}
							}
							$mimeType = $parts[$i]->mimeType;
							$attachmentID = $parts[$i]->getBody()->getAttachmentId();
							$fileSize = $parts[$i]->getBody()->getSize();
							$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
						}
					}
					$messages[] = array("message_id"=>$messageId,'labelIds'=>array_unique($label),"payload"=>$messagePayload,"header"=>$headers,"body"=>$body,"rawMessage"=>$decodedMessage,"attachments"=>$attachments);			
				}
			}
		}		
		return $messages;
	}
	
	public function findThreadData($threadID){
		$this->_service = new Google_Service_Gmail($this->_client);
		$optParamsGet = [];
		$optParamsGet['format'] = 'full'; // Display message in payload
		$message = $this->_service->users_threads->get('me',$threadID,$optParamsGet);
		$allMessagesInThread = $message->getMessages();	
		$messages = array();
		foreach($allMessagesInThread as $threadMessage){
			$message = 	$this->_service->users_messages->get('me',$threadMessage->id,$optParamsGet);
			$messagePayload = $threadMessage->getPayload();
			$headers = $threadMessage->getPayload()->getHeaders();
			$parts = $threadMessage->getPayload()->getParts();
			$body = "";
			$attachments = array();
			if(count($parts)>0){		
				if($parts[0]->mimeType=='text/plain'){
					$rawBody = $parts[1]->getBody();
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
						foreach($partHeaders as $h){
							if($h->name=='X-Attachment-Id'){
								$realAttachID= $h->value;
							}
						}
						$mimeType = $parts[$i]->mimeType;
						$attachmentID = $parts[$i]->getBody()->getAttachmentId();
						$fileSize = $parts[$i]->getBody()->getSize();
						$attachments[] = array('filename'=>$fileName,'mimeType'=>base64_encode($mimeType),'attachmentId'=>$attachmentID,'size'=>$fileSize,"realAttachID"=>$realAttachID);
					}
				}
			}
			$messages[] = array("message_id"=>$threadMessage->id,'labelIds'=>$threadMessage->labelIds,"header"=>$headers,"body"=>$body,"attachments"=>$attachments);			
		}
		
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
		$optParams = [];
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
	
	public function insertFile($name,$mimeType,$filename,Google_Service_Drive_ParentReference $fileParent = null){
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
				'convert'=>true
		));
		return $createdFile;
	}
	
	public function createFile( $name, $mime, $description, $content, Google_Service_Drive_ParentReference $fileParent = null ) {		
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
		$perm->setAdditionalRoles( array('commenter'));
		
		$this->_service->permissions->insert($fileId, $perm,$optParams);
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
				$listFiles[] = $this->getFileInfo($child->id);
				//print 'File Id: ' . $child->getId();
			  }
			  $pageToken = $children->getNextPageToken();
			} catch (Exception $e) {
			  print "An error occurred: " . $e->getMessage();
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
}


/*
$service = new DriveServiceHelper( CLIENT_ID, SERVICE_ACCOUNT_NAME, KEY_PATH );
$folderId = $service->getFileIdByName(PASTING_FOLDER);

//Finding File from Master Folder
$folderId = $service->getFileIdByName(BACKUP_FOLDER);
if($folderId){
	$allFiles = $service->getFileIDFromChildern($folderId);
	if(count($allFiles)>0){
		foreach($allFiles as $file){
			$fileID = $file->getId();			
			$getFileInfo = $service->getFileInfo($fileID);
			if($getFileInfo->title=='Dummy Document'){
				$folderIDOpportunites = $service->getFileIdByName('20141108');
				if($folderIDOpportunites){
					$fileParent = new Google_Service_Drive_ParentReference();
					$fileParent->setId( $folderIDOpportunites );
					$getFileInfo = $service->copyFile($fileID,'20141108_Seller_NDA',$fileParent);
					echo "<pre>";
					print_r($getFileInfo);					
				}
			}			
			//echo "<pre>";
			//print_r($getFileInfo);
			die;
		}
	}
}
*/




/*
Creating Sub Folder
if($folderId){
	$fileParent = new Google_Service_Drive_ParentReference();
	$fileParent->setId( $folderId );
	$newFolderId = $service->createSubFolder('20141108',$fileParent);	
	if($newFolderId){
		echo $newFolderId;
		$service->setPermissions( $newFolderId, SHARE_WITH_GOOGLE_EMAIL );
	}
}
*/

//$getFileID = $service->getFileIDFromChildern($folderId);





/*
if($folderId){
	echo "Enter";
	$fileParent = new Google_Service_Drive_ParentReference();
    $fileParent->setId( $folderId );
	$path = realpath(dirname(__FILE__) . '/1SynPatProposalLettertoSellers.docx');
	$fileId = $service->createFileFromPath( $path, $path, $fileParent );
	printf( "File: %s created\n", $fileId );
	$service->setPermissions( $fileId, SHARE_WITH_GOOGLE_EMAIL );
	//$service->setPermissions( $fileId, SHARE_WITH_GOOGLE_EMAIL_ANOTHER );
	
}
echo "<pre>";
//print_r($folderId);
die;*/
?>
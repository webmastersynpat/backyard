<?php 

include_once "examples/templates/base.php";
session_start();

require_once realpath(dirname(__FILE__) . '/autoload.php');

define( 'BACKUP_FOLDER', 'Master Documents Through API' );
//define( 'BACKUP_FOLDER', 'Master Documents' );
define('PASTING_FOLDER','Opportunities Through API');
define( 'SHARE_WITH_GOOGLE_EMAIL', 'webmaster@synpat.com' );
define( 'SHARE_WITH_GOOGLE_EMAIL_ANOTHER', 'uzi@synpat.com' );
$folderID = '0B-7JHq4pougDSE5UR2M0OGY3c00';
define( 'CLIENT_ID',  '671429899926-tvqle2htej2bmq1q55k2tnr1dpf3k5g2.apps.googleusercontent.com' );
define( 'SERVICE_ACCOUNT_NAME', '671429899926-tvqle2htej2bmq1q55k2tnr1dpf3k5g2@developer.gserviceaccount.com' );
define( 'KEY_PATH', 'Backyard Project-08980be0dff1.p12');

class DriveServiceHelper {
	
	protected $scope = array('https://www.googleapis.com/auth/drive');
	
	private $_service;
	
	public function __construct( $clientId, $serviceAccountName, $key ) {
		$client = new Google_Client();
		$client->setClientId( $clientId );
		
		$client->setAssertionCredentials( new Google_Auth_AssertionCredentials(
				$serviceAccountName,
				$this->scope,
				file_get_contents( $key ) )
		);
		
		$this->_service = new Google_Service_Drive($client);
	}
	
	public function __get( $name ) {
		return $this->_service->$name;
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
	public function setPermissions( $fileId, $value, $role = 'writer', $type = 'user' ) {
		$perm = new Google_Service_Drive_Permission();
		$perm->setValue( $value );
		$perm->setType( $type );
		$perm->setRole( $role );
		
		$this->_service->permissions->insert($fileId, $perm);
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
				$listFiles[] = $child;
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
}



$service = new DriveServiceHelper( CLIENT_ID, SERVICE_ACCOUNT_NAME, KEY_PATH );
$folderId = $service->getFileIdByName(PASTING_FOLDER);
/*
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
	
}*/
echo "<pre>";
//print_r($folderId);
die;
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.3/PasswordHash.php');

define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', false);

/**
 * SimpleLoginSecure Class
 *
 * Makes authentication simple and secure.
 *
 */
class SimpleLoginSecure
{
	var $CI;
	var $user_table = 'users';
	var $lead_table = 'assign_leads';
	var $module_table = 'assign_modules';  
 
	/**
	 * Create a user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function create($name='',$email = '', $password = '', $phone_number='',$type=1 ,$auto_login = true) 
	{
		$this->CI =& get_instance();
		


		//Make sure account info was sent
		if($email == '' OR $password == '') {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('email', $email); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) //email already exists
			return false;

		//Hash password using phpass
		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$password_hashed = $hasher->HashPassword($password);

		//Insert account into the database
		$data = array(
					'name' =>$name,
					'email' => $email,
					'password' => $password_hashed,
					'phone_number' => $phone_number,
					'type'	=> $type,
					'user_date' => date('c'),
					'user_modified' => date('c'),
				);		
		$this->CI->db->set($data); 

		if(!$this->CI->db->insert($this->user_table)) //There was a problem! 
			return false;						
				
		if($auto_login)
			$this->login($email, $password);
		
		return true;
	}

	/**
	 * Update a user account
	 *
	
	 * @access	public
	 * @param integer
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function update($id = null, $password='',$profile_pic='',$phoneNumber, $auto_login = true) 
	{
		$this->CI =& get_instance();

		//Make sure account info was sent
		if($id == null) {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('id', $id);
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() == 0){ // user don't exists
			return false;
		}

		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		$password_hashed = $hasher->HashPassword($password);
		//Update account into the database
		$data = array(
					'password' => $password_hashed,
					'phone_number' => $phoneNumber,
					'profile_pic' => $profile_pic,
					'user_modified' => date('c'),
				);
		$this->CI->db->where('id', $id);

		if(!$this->CI->db->update($this->user_table, $data)) //There was a problem! 
			return false;						
				
		if($auto_login){
			$this->CI->db->where('id', $id);
			$query = $this->CI->db->get_where($this->user_table);
			if ($query->num_rows() > 0){ 
				$user_data['user'] = $query->first_row(); 
				
			}
			$user_data['email'] = $email;
			$user_data['user'] = $user_data['email']; // for compatibility with Simplelogin
			
			$user_data['phone_number'] = $phoneNumber; // for compatibility with Simplelogin
			
			$this->CI->session->set_userdata($user_data);			
		}
		return true;
	}

	
	
	/**
	 * Update a user account
	 *
	
	 * @access	public
	 * @param integer
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function updateProfilePic($id = null,$profile_pic='',$phoneNumber, $auto_login = true) 
	{
		$this->CI =& get_instance();

		//Make sure account info was sent
		if($id == null) {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('id', $id);
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() == 0){ // user don't exists
			return false;
		}
	
		//Update account into the database
		$data = array(
					'profile_pic' => $profile_pic,
					'phone_number' => $phoneNumber,
					'user_modified' => date('c'),
				);
 
		$this->CI->db->where('id', $id);

		if(!$this->CI->db->update($this->user_table, $data)) //There was a problem! 
			return false;						
				
		if($auto_login){
			$this->CI->db->where('id', $id);
			$query = $this->CI->db->get_where($this->user_table);
			if ($query->num_rows() > 0){ 
				$user_data['user'] = $query->first_row(); 
				
			}	
			
			$user_data['email'] = $email;
			$user_data['user'] = $user_data['email']; // for compatibility with Simplelogin
			$user_data['profile_pic'] = $profile_pic; // for compatibility with Simplelogin
			$user_data['phone_number'] = $phoneNumber; // for compatibility with Simplelogin
			
			$this->CI->session->set_userdata($user_data);
			}
		return true;
	}
	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function login($email = '', $password = '') 
	{
		$this->CI =& get_instance();

		if($email == '' OR $password == '')
			return false;


		//Check if already logged in
		
		if($this->CI->session->userdata('email') == $email)
			return true;
		
		
		//Check against user table
		$this->CI->db->where('email', $email); 
		$query = $this->CI->db->get_where($this->user_table);

		
		if ($query->num_rows() > 0) 
		{
			$user_data = $query->row_array(); 
			
			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

			if(!$hasher->CheckPassword($password, $user_data['password']))
				return false;

			//Destroy old session
			$this->CI->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->CI->session->sess_create();
			$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET user_last_login = "' . date('c') . '" WHERE id = ' . $user_data['id']);
			
			
			//Set session data
			unset($user_data['password']);
			$user_data['user'] = $user_data; // for compatibility with Simplelogin
			$user_data['modules_assign'] = $this->getAllModules($user_data['id']);
			$user_data['logged_in'] = true;
			/*$user_data['signature'] = $this->getMySignature($user_data['email']);*/
			$loginDate = date('Y-m-d H:i:s');			
			$user_data['login_date'] =$loginDate;
			$user_data['initialise_email'] =0;
			$this->CI->session->set_userdata($user_data); 
			$this->CI->db->insert('user_logtime',array('user_id'=>$user_data['id'],'sid'=>$this->CI->session->userdata['session_id'],'login_date'=>$loginDate));
			/*user_logtime*/
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['find_user'] = $user_data;
			unset($_SESSION['another_access_token']);
			unset($_SESSION['access_token']);
			unset($_SESSION['my_emails']);
			unset($_SESSION['INBOX']);
			unset($_SESSION['STARRED']);
			unset($_SESSION['DRAFT']);
			unset($_SESSION['SENT']);
			unset($_SESSION['TRASH']);
			unset($_SESSION['LEAD']);
			return true;
		} 
		else 
		{
			return false;
		}	

	}
	
	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout() {
		$this->CI =& get_instance();
		/*
		$this->CI->db->simple_query('INSERT INTO user_logtime(user_id,sid,login_date) VALUES('. $user_data['id'].',"'.$this->CI->session->userdata['session_id'].'","'.date('Y-m-d H:i:s').'")');
		*/
		
	
		$this->CI->session->sess_destroy();
		if(!isset($_SESSION)){
			session_start();
		}
		unset($_SESSION['find_user']);
		unset($_SESSION['another_access_token']);
		unset($_SESSION['access_token']);
		unset($_SESSION['my_emails']);
		unset($_SESSION['INBOX']);
		unset($_SESSION['STARRED']);
		unset($_SESSION['DRAFT']);
		unset($_SESSION['SENT']);
		unset($_SESSION['TRASH']);
		unset($_SESSION['LEAD']);
	}
	
	
	
	function getAllModules($userID){
		$this->CI->db->where('user_id', $userID); 
		$query = $this->CI->db->select('module_id')->from($this->module_table)->get();
		$data = array();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row->module_id;
            }            
        }
		return $data;
	}
	

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($id) 
	{
		$this->CI =& get_instance();
		
		if(!is_numeric($id))
			return false;			

		return $this->CI->db->delete($this->user_table, array('id' => $id));
	}
	
	
	/**
	* Edit a user password
	* @author    Stéphane Bourzeix, Pixelmio <stephane[at]bourzeix.com>
	* @author    Diego Castro <castroc.diego[at]gmail.com>
	*
	* @access  public
	* @param  string
	* @param  string
	* @param  string
	* @return  bool
	*/
	function edit_password($email = '', $old_pass = '', $new_pass = '')
	{
		$this->CI =& get_instance();
		// Check if the password is the same as the old one
		$this->CI->db->select('password');
		$query = $this->CI->db->get_where($this->user_table, array('email' => $email));
		$user_data = $query->row_array();

		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);	
		if (!$hasher->CheckPassword($old_pass, $user_data['password'])){ //old_pass is the same
			return FALSE;
		}
		
		// Hash new_pass using phpass
		$password_hashed = $hasher->HashPassword($new_pass);
		// Insert new password into the database
		$data = array(
			'password' => $password_hashed,
			'user_modified' => date('c')
		);
		
		$this->CI->db->set($data);
		$this->CI->db->where('email', $email);
		if(!$this->CI->db->update($this->user_table, $data)){ // There was a problem!
			return FALSE;
		} else {
			return TRUE;
		}
	}
    
    function confirm_password($c, $new_pass = '')
	{
		$this->CI =& get_instance();
		// Check if the password is the same as the old one
	/*	$this->CI->db->select('password');
		$query = $this->CI->db->get_where($this->user_table, array('forgot_code' => $c));
		$user_data = $query->row_array();

		$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);	
/*		if (!$hasher->CheckPassword($old_pass, $user_data['password'])){ //old_pass is the same
			return FALSE;
		}*/
		
		// Hash new_pass using phpass
        $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);	
		$password_hashed = $hasher->HashPassword($new_pass);
		// Insert new password into the database
		$data = array(
			'password' => $password_hashed,
			'user_modified' => date('c')
		);
		
		$this->CI->db->set($data);
		$this->CI->db->where('forgot_code', $c);
		if(!$this->CI->db->update($this->user_table, $data)){ // There was a problem!
			return FALSE;
		} else {
			return TRUE;
		}
	}
    
    function checkDataExist($data){
        $this->CI =& get_instance();
        $query = $this->CI->db->get_where($this->user_table, $data);
		$user_data = $query->row_array();
        return $user_data;
    }
    
    function updateEmailForgotCode($email ='',$code){ 
        $this->CI =& get_instance();
        $this->CI->db->where('email', $email);
		$this->CI->db->update($this->user_table, $code);
    }
	
}
?>

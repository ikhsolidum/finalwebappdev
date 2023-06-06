<?php
class User{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_wbapp';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}
	
	public function new_user($email, $password, $lastname, $firstname, $access) {
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
	
		$data = [$lastname, $firstname, $email, md5($password), $NOW, $NOW, '1', $access];

		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $this->conn->prepare("INSERT INTO tbl_users (user_lastname, user_firstname, user_email, user_password, user_date_added, user_time_added, user_status, user_access) VALUES (?,?,?,?,?,?,?,?)");

		try {
			$this->conn->beginTransaction();
			foreach ($data as $row) {
				$stmt->execute($row);
			}
			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			throw $e;
		}

		return true;
	}

	public function update_user($update_fields, $id) {
		// Setting Timezone for DB
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
	
		// Get the current user details from the database
		$current_user_details = $this->get_user_details($id);
	
		// Check which fields are edited by the user
		$firstname = isset($update_fields['user_firstname']) ? $update_fields['user_firstname'] : $current_user_details['user_firstname'];
		$lastname = isset($update_fields['user_lastname']) ? $update_fields['user_lastname'] : $current_user_details['user_lastname'];
		$email = isset($update_fields['user_email']) ? $update_fields['user_email'] : $current_user_details['user_email'];
		$user_status = isset($update_fields['user_status']) ? $update_fields['user_status'] : $current_user_details['user_status'];
	
		// Check if the password field is empty or not
		if (isset($update_fields['user_password']) && $update_fields['user_password'] !== '') {
			$new_password = $update_fields['user_password'];
			// Apply MD5 hashing to the password
			$hashed_password = md5($new_password);
		} else {
			$hashed_password = $current_user_details['user_password']; // Keep the current hashed password
		}
	
		$sql = "UPDATE tbl_users SET user_firstname=:user_firstname, user_lastname=:user_lastname, user_email=:user_email, user_password=:user_password, user_date_updated=:user_date_updated, user_time_updated=:user_time_updated, user_status=:user_status WHERE user_id=:user_id";
	
		$q = $this->conn->prepare($sql);
		$q->execute(array(
			':user_firstname' => $firstname,
			':user_lastname' => $lastname,
			':user_email' => $email,
			':user_password' => $hashed_password,
			':user_date_updated' => $NOW,
			':user_time_updated' => $NOW,
			':user_status' => $user_status,
			':user_id' => $id
		));
	
		return true;
	}
	
	
	

	public function list_users(){
		$sql = "SELECT * FROM tbl_users";
		$q = $this->conn->query($sql) or die("failed!");
		while ($r = $q->fetch(PDO::FETCH_ASSOC)){
			$data[] = $r;
		}
		if (empty($data)) {
			return false;
		} else {
			return $data;	
		}
	}

	public function get_user_details($id) {
		$sql = "SELECT * FROM tbl_users WHERE user_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_details = $q->fetch(PDO::FETCH_ASSOC);
		return $user_details;
	}

	public function delete_user($id) {
		$sql = "DELETE FROM tbl_users WHERE user_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	
		if ($stmt->execute()) {
			return true; // Deletion successful
		} else {
			return false; // Failed to delete user
		}
	}

	function get_user_id($email){
		$sql = "SELECT user_id FROM tbl_users WHERE user_email = :email";	
		$q = $this->conn->prepare($sql);
		$q->execute(['email' => $email]);
		$user_id = $q->fetchColumn();
		return $user_id;
	}

	function get_user_password($id) {
		$sql = "SELECT user_password FROM tbl_users WHERE user_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_password = $q->fetchColumn();
		return $user_password;
	}

	function get_user_email($id) {
		$sql = "SELECT user_email FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_email = $q->fetchColumn();
		return $user_email;
	}

	function get_user_firstname($id) {
		$sql = "SELECT user_firstname FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_firstname = $q->fetchColumn();
		return $user_firstname;
	}

	function get_user_lastname($id) {
		$sql = "SELECT user_lastname FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_lastname = $q->fetchColumn();
		return $user_lastname;
	}

	function get_user_access($id) {
		$sql = "SELECT user_access FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_access = $q->fetchColumn();
		return $user_access;
	}

	function get_user_status($id) {
		$sql = "SELECT user_status FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_status = $q->fetchColumn();
		return $user_status;
	}

	function get_session() {
		if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
			return true;
		} else {
			return false;
		}
	}

	public function check_login($email, $password) {
		$_SESSION['user_email'] = $_REQUEST['useremail'];
	
		$user_id = $this->get_user_id($email);
		$hashed_password = $this->get_user_password($user_id);
	
		if (password_verify($password, $hashed_password)) {
			$_SESSION['login'] = true;
			$_SESSION['user_email'] = $email;
			return true;
		} else {
			return false;
		}
	}
}	
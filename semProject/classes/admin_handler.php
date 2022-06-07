<?php
/*
* Designed and programmed by
* @Author: Francis A. Anlimah
*/

class AdminHandler
{
	private $gh;

	public function __construct()
	{
		require_once('general_handler.php');
		$this->gh = new GeneralHandler();
	}

	/*private function genPassword()
	{
		$digits = 10;
		return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
	}*/

	public function checkEmail($email)
	{
		$sql = "SELECT `id` FROM `tbl_users` WHERE `email`=:e";
		$params = array(':e' => $this->gh->validateEmail($email));
		return $this->gh->getID($sql, $params);
	}

	public function checkUser($email, $password)
	{
		$sql = "SELECT `id` FROM `users` WHERE `email_address`=:e AND `user_password`=:p";
		$params = array(':e' => $this->gh->validateEmail($email), ':p' => $password);
		return $this->gh->getData($sql, $params);
	}

	/*public function adminLogin($email, $password)
	{
		$sql = "SELECT `id` FROM `tbl_users` WHERE `email`=:e AND `password`=:p AND `type` = 1";
		$params = array(':e' => $this->gh->validateEmail($email), ':p' => sha1($password));
		return $this->gh->getID($sql, $params);
	}*/

	public function getAllUsers($i)
	{
		$sql = "";
		if ($i == 1) {
			//get not deleted students
			$sql .= "SELECT `id`,`fname`, `mname`, `lname`, `company`,`department`,`email` 
					FROM `tbl_users` WHERE `deleted` = 0";
		} else {
			//get deleted students
			$sql .= "SELECT `id`,`fname`, `mname`, `lname`, `company`,`department`,`email` 
					FROM `tbl_users` WHERE `deleted` = 1";
		}

		return $this->gh->getData($sql);
	}

	public function getUserData($user)
	{
		$sql = "SELECT `id`,`fname`, `mname`, `lname`, `company`,`department`,`email` 
				FROM `tbl_users` WHERE `id` = :u";
		$params = array(':u' => $user);
		return $this->gh->getData($sql, $params);
	}

	public function addUserData($f, $m, $l, $c, $d, $e)
	{
		if ($this->checkEmail($e)) {
			return '[{"error":"An account already exist with this email!"}]';
		} else {
			$sql = "INSERT INTO `tbl_users`(`fname`, `mname`, `lname`, `company`,`department`,`email`,`password`)  
					VALUES(:f, :m, :l, :c, :d, :e, :s)";
			$s = $this->gh->genCode();
			$params = array(':f' => $f, ':m' => $m, ':l' => $l, ':c' => $c, ':d' => $d, ':e' => $e, ':s' => sha1($s));
			if ($this->gh->inputData($sql, $params)) {
				if ($this->checkUser($e, $s)) {
					return '[{"success":"User data successfully added!"}]';;
				} else {
					return '[{"error":"Failed to add user data!"}]';
				}
			}
		}
	}

	public function updateUserData($f, $m, $l, $c, $d, $e, $i)
	{
		$sql = "UPDATE `tbl_users` SET 
				`fname` = :f, `mname` = :m, `lname` = :l, 
				`company` = :c, `department` = :d, `email` = :e 
				WHERE `id`=:i";
		$params = array(
			':f' => $f, ':m' => $m, ':l' => $l,
			':c' => $c, ':d' => $d, ':e' => $e, ':i' => $i
		);
		if ($this->gh->inputData($sql, $params)) {
			return '[{"success":"User data successfully updated!"}]';
		} else {
			return '[{"error":"User data update failed!"}]';
		}
	}

	public function deleteUserData($user_id)
	{
		$sql = "UPDATE `tbl_users` SET `deleted` = 1 WHERE `id`=:i";
		$params = array(':i' => $user_id);
		if ($this->gh->inputData($sql, $params)) {
			return '[{"success":"User data successfully deleted!"}]';;
		} else {
			return '[{"error":"User data deletion failed!"}]';
		}
	}

	public function searchForuser($key)
	{
		if (!empty($key)) {
			$sql = "SELECT `fname`, `mname`, `lname`, `position`,`department`,`email` 
					FROM `tbl_users` WHERE `email` LIKE :k";
			$users = $this->gh->getData($sql, array(":k" => "%" . $key . "%"));
			if (!empty($users)) {
				return json_encode($users);
			} else {
				return '[{"error":"No match found!"}]';
			}
		}
	}

	public function getDataStats($status)
	{
		$sql = "";
		if ($status == "users") {
			$sql .= "SELECT s.`id`, s.`index`, s.`fname`, s.`mname`, s.`lname` 
					FROM `tbl_users` AS s, `finance` AS f 
					WHERE s.`id` = f.`sid`";
		} else {
			$sql .= "SELECT s.`id`, s.`index`, s.`fname`, s.`mname`, s.`lname` 
					FROM `tbl_users` AS s, `finance` AS f 
					WHERE s.`id` = f.`sid` AND `status`=:s";
		}
		$param = array(":s" => $status);
		$data = $this->gh->getData($sql, $param);
		if (!empty($data)) {
			return $data;
		} else {
			return 0;
		}
	}

	private function updateUserPassw($user_id, $password)
	{
		$sql = "UPDATE `tbl_users` SET `password` = :p 
				WHERE `id`=:i";
		$params = array(':i' => $user_id, ':p' => sha1($password));
		return $this->gh->inputData($sql, $params);
	}

	public function sendEmail($email)
	{
		if ($this->checkEmail($email)) {
			return '[{"success":"Message has been sent to your email, ' . $email . '."}]';
		} else {
			return '[{"error":"This email wasn\'t found in our system"}]';
		}
	}

	public function resetUserPassw($user_id, $current_pw, $new_pw)
	{
		$sql = "SELECT `id` FROM `tbl_users` WHERE `password`=:c AND `id`=:u";
		$result = $this->gh->getData($sql, array(":c" => sha1($current_pw), ":u" => $user_id));
		if (!empty($result)) {
			$rslt = $this->updateUserPassw($result[0]["id"], $new_pw);
			if ($rslt) {
				return '[{"success":"Password changed successfully!"}]';;
			} else {
				return '[{"error":"Password reset failed!"}]';
			}
		} else {
			return '[{"error":"Password reset failed!"}]';
		}
	}

	public function getAllHazobs()
	{
		$sql = "SELECT h.`id`, CONCAT(u.`fname`, ' ', u.`mname`, ' ', u.`lname`) AS `author`, 
				h.`type`, h.`hid`, h.`status`, h.`created_date`, h.`created_time`, h.`deleted` 
				FROM `tbl_hazobs` AS h, `tbl_users` AS u 
				WHERE h.`uid` = u.`id` AND h.`deleted` = 0";
		return $this->gh->getData($sql);
	}

	// Restores a deleted student's data
	public function restoreStudentData($user)
	{
		$sql = "UPDATE `tbl_users` SET `deleted` = 0 WHERE `id`=:i";
		$params = array(':i' => $user);
		$result = $this->gh->inputData($sql, $params);
		if ($result != 0 || !empty($result)) {
			return '[{"success":"Student data successfully restored!"}]';
		} else {
			return '[{"error":"Unable to restore student data!"}]';
		}
	}
}

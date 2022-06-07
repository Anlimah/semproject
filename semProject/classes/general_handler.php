<?php
/*
* Designed and programmed by
* @Author: Francis A. Anlimah
*/

class GeneralHandler
{

	private $db;

	function __construct()
	{
		require_once('db.php');
		$this->db = new DbConnect();
	}

	//Get raw data from db
	public function getID($str, $params = array())
	{
		try {
			$result = $this->db->query($str, $params);
			if (!empty($result)) {
				return $result[0]["id"];
			} else {
				return 0;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	//Get raw data from db
	public function getData($str, $params = array())
	{
		try {
			$result = $this->db->query($str, $params);
			if (!empty($result)) {
				return $result;
			} else {
				return 0;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	//Get raw data from db
	public function getTotalData($str, $params = array())
	{
		try {
			return $this->db->getTotal($str, $params);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	//Insert, Upadate or Delete Data
	public function inputData($str, $params = array())
	{
		try {
			$result = $this->db->query($str, $params);
			if (!empty($result)) {
				return $result;
			} else {
				return 0;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	//Genarates 6 digit SMS code which will be sent to user for phone number verification
	public function genCode()
	{
		$digits = 10;
		return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
	}

	public function validateEmail($email)
	{
		$user_email = htmlentities(htmlspecialchars($email));
		$sanitized_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
		$validated_email = filter_var($sanitized_email, FILTER_VALIDATE_EMAIL);
		return $validated_email;
	}

	public function validatePassword($password)
	{
		$user_password = htmlentities(htmlspecialchars($password));
		$validated_password = (bool) preg_match('/^[A-Za-z0-9]/', $user_password);
		if ($validated_password) {
			return $user_password;
		}
	}

	public function validateIDInput($input)
	{
		$user_input = htmlentities(htmlspecialchars($input));
		$validated_input = (bool) preg_match('/^[A-Za-z0-9]/', $user_input);
		if ($validated_input) {
			return $user_input;
		}
	}
}

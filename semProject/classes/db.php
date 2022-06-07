<?php
/*
* Designed and programmed by
* @Author: Francis A. Anlimah
*
* This class is the database class configuration for db_waep DB
* It connect to db_waep and used by ExamHandler, UserHandler classes 
* for user exam purposes, suggestions
*/

class DbConnect
{

    private $host = "127.0.0.1";
    private $dbname = "db_sem_project";
    private $user = "root";
    private $pass = "";
    private $link;

    public function __construct()
    {
        try {
            $conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8', $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->link = $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function query($str, $params = array())
    {
        $stmt = $this->link->prepare($str);
        $stmt->execute($params);
        if (explode(' ', $str)[0] == 'SELECT') {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$data) {
                return $data;
            } else {
                return $data;
            }
        } elseif (explode(' ', $str)[0] == 'INSERT' || explode(' ', $str)[0] == 'UPDATE' || explode(' ', $str)[0] == 'DELETE') {
            return 1;
        }
    }

    //get total number of rows
    public function getTotal($str, $params = array())
    {
        $stmt = $this->link->prepare($str);
        $stmt->execute($params);
        $total = $stmt->rowCount();
        return $total;
    }
}

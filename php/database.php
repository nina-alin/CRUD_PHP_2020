<?php
	class Database {
		 private $dbName ;
		 private $dbHost ;
		 private $dbUsername ;
		 private $dbUserPassword ;
		 private $handler = null ;
		 public function __construct($dbName ,$dbHost, $dbUsername, $dbUserPassword ) {
			$this->dbName = $dbName;
			$this->dbHost = $dbHost;
			$this->dbUsername = $dbUsername;
			$this->dbUserPassword = $dbUserPassword;
		 }
		 public function connect() {
			 if ( null == $this->handler ) {
				 try {
					$this->handler = new PDO("mysql:host=".$this->dbHost.";"."dbname=".$this->dbName.";charset=utf8",$this->dbUsername,$this->dbUserPassword);
					$this->handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				 }
				 catch(PDOException $e) {
					die($e->getMessage());
				 }
			 }
			 return $this->handler;
		 }
		 public function disconnect() {
			$this->handler = null;
		 }
	}
?>
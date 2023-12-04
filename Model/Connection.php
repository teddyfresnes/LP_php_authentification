<?php
class Connection
{
	private string $host;
	private string $dbname;
	private string $username;
	private string $password;
	private PDO $db;
	
	function __construct()
	{
		$this->host = "localhost:3306";
		$this->username = "root";
		$this->password = "password";
		$this->dbname = "tp_nollet";
		
		try
		{
			$this->db = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
			
			echo "<script>console.log('Connexion à la base de données réussie!')</script>";
		}
		catch (PDOException $e)
		{
			echo "Erreur de connexion à la base de données: ".$e->getMessage();
		}
	}
	
	public function getDb(): PDO
	{
		return $this->db;
	}
}
	
	
<?php

class UserManager
{
	private PDO $db;
	private $table = "user";
	
	function __construct($db)
	{
		
		$this->db = $db;
	}
	
	
	function create(User $user)
	{
		// exemple de requete :
		// INSERT INTO user (password, email, firstName, lastName, address, postalCode, city, admin) VALUES('toto', 'Titi@gmail.com', 'Patrick', 'NOLLET', '4 pleceee', '75020', 'paris', 0)
		
		$req = $this->db->prepare("INSERT INTO $this->table (password, email, firstName, lastName,
		address, postalCode, city, country, admin) VALUES(:password, :email, :firstname, :lastname, :address,
		:postalCode, :city, :country, :admin)");
		
		try
		{
			// var_dump($user);
			$req->bindValue(':password', hash("sha1", $user->getPassword()));
			$req->bindValue(':email', $user->getEmail());
			$req->bindValue(':firstname', $user->getFirstName());
			$req->bindValue(':lastname', $user->getLastName());
			$req->bindValue(':address', $user->getAddress());
			$req->bindValue(':postalCode', $user->getPostalCode());
			$req->bindValue(':city', $user->getCity());
			$req->bindValue(':country', $user->getCountry());
			$req->bindValue(':admin', 0);
			// echo "<br />\n<br />\n";
			// $req->debugDumpParams(); // Affiche la requête finale
			$req->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	

	function update($user)
	{
		$req = $this->db->prepare("UPDATE $this->table SET 
			id = :id,
			password = :password, 
			email = :email, 
			firstName = :firstname, 
			lastName = :lastname, 
			address = :address, 
			postalCode = :postalCode, 
			city = :city, 
			country = :country, 
			admin = :admin,
			WHERE id = :id");

		try
		{
			$req->bindValue(':id', $user->getId()); // si l'id existe, il écrase les anciennes valeurs avec le nouvel user
			$req->bindValue(':password', hash("sha1", $user->getPassword()));
			$req->bindValue(':email', $user->getEmail());
			$req->bindValue(':firstname', $user->getFirstName());
			$req->bindValue(':lastname', $user->getLastName());
			$req->bindValue(':address', $user->getAddress());
			$req->bindValue(':postalCode', $user->getPostalCode());
			$req->bindValue(':city', $user->getCity());
			$req->bindValue(':country', $user->getCountry());
			$req->bindValue(':admin', 0);

			$req->execute();
			// echo "Mise à jour réussie pour l'utilisateur avec l'ID ".$user->getId();
		}
		catch (PDOException $e)
		{
			echo "Erreur lors de la mise à jour : " . $e->getMessage();
		}
	}
	
	function delete($user)
	{
		$req = $this->db->prepare("DELETE FROM $this->table WHERE id = :id");

		try
		{
			$req->bindValue(':id', $user->getId());
			$req->execute();
			// echo "Suppression réussie pour l'utilisateur avec l'ID ".$user->getId();
		}
		catch (PDOException $e)
		{
			echo "Erreur lors de la suppression : " . $e->getMessage();
		}
	}

	
	function findOne(int $id)
	{
		$users = [];
		$req = $this->db->query("SELECT * FROM USER WHERE (id = $id)");
		$result = $req->fetch();
		// var_dump($result);
		return $result;
	}
	
	function findAll()
	{
		$users = [];
		$req = $this->db->query("SELECT * FROM user ORDER BY id");
		while ($donnees = $req->fetch(PDO::FETCH_ASSOC))
		{
			$users[] = new User($donnees);
		}
		return $users;
	}
	
	
    public function login($email, $password)
	{
        $password = hash("sha1", $password);
        $req = $this->db->query("SELECT * FROM user WHERE email = '$email' AND password = '$password'");
		//var_dump($req);
		if ($req) {
			$user = $req->fetch();
			return $user;
		} else {return null;}
		
    }


	public function findByEmail($email)
	{
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result)
		{
            return new User($result);
        }
		else
		{
            return null;
        }
    }


    public function isAdminByEmail($email)
	{
        $query = "SELECT isAdmin FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result && $result['isAdmin'] == 1);
    }

	public function becomeAdmin($email)
	{
		$user = $this->findByEmail($email);
		if ($user) {
			$stmt = $this->db->prepare("UPDATE user SET admin = 1 WHERE email = :email");
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$user->setAdmin(1);
			return true;
		}
		return false;
	}	
}

?>
<?php
require_once('Controleur.php');

class ControleurProfil extends Controleur
{
	protected $panier;

	public function route_profil()
	{
		//Ici on va récupérer les commandes du client
		$id_utilisateur = $_SESSION['user']['id'];
		//ListIdcommande permet de recupérer tous les numéros de commandes
		$recupListId = $this->panier->listIdCommandes($id_utilisateur);
		
		$poposh = [];
		$prixTotal = [];
		$dateCom = [];

		for($i=0; $i<count($recupListId); $i++)
		{
			//On se sert du numéro de commande pour récupérer tous les articles quelle contient
			$recupComm[$i] = $this->panier->historiqueCommandes($recupListId[$i]['id_commande']);

			//On met tout dans le tableau poposh[], qui nous permettra d'afficher les données
			$poposh[] = $recupComm[$i];
			$prixTotal[] = $recupListId[$i]['prix_total'];
			$dateCom[] = $recupListId[$i]['date'];
		}

		$error = [
			'empty' => '', 
			'email' => '',                                                     
  			'login' => '',
  			'password' => ''
			];
		
		//Gestion du formulaire
		if(isset($_POST['submit']))
		{
			//si vide
			if(empty($_POST['login']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password2']))
			{
				$error['empty'] = "<span>Champs vide</span>";
			}
			//pregmatch
			$pattern = "/^\S*[a-z,A-Z,0-9]{4,}\S*/";
			if(!preg_match($pattern, $_POST['login'])){
				$error['login'] = "<span>commence bien au début, 4 caractéres minimum, majuscule,minuscules,chiffres autorisées</span>";
			}
			//si user existe en bdd
			if($this->user->exists($_POST['login'])===-1)
			{
				 $error['login'] = "<span>Login deja pris</span>";
			}
			//si mauvais format d'email
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
  				 $error['email'] = "<span>Saisissez un email valide</span>";
			}
			$password = htmlspecialchars($_POST['password2']);
			//pregmatch pour password
			if(!preg_match($pattern, $password)){
				$error['password'] = "<span>commence bien au début, 4 caractéres minimum, majuscule,minuscules,chiffres autorisées</span>";
			}
			$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$login = htmlspecialchars($_POST['login']);
			$email = htmlspecialchars($_POST['email']);
			//si password pas identique
			if(parent::Verifypass($password, $password_hash)===false)
			{
				 $error['password'] = "<span>Mot de passe non identique</span>";
			}
			//si il y a des error
			if(array_filter($error))
			{
			}
			//sinon
			else
			{
				$this->user->updateUser($login,$email,$password_hash, $_SESSION['user']['id']);
				$_SESSION['user']['login'] = $login;
				$_SESSION['user']['email'] = $email;
				$_SESSION['user']['password'] = $password;

			}
		}
	
		require 'Vue/vueProfil.php';
	}
}
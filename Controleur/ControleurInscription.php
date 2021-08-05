<?php
require_once'Controleur.php';

class ControleurInscription extends Controleur
{
	protected $user;

	public function route_inscription()
	{
		$error = [
			'empty' => '', 
			'email' => '',                                                     
			'login' => '',
			'password' => ''
		];
		if(isset($_POST['submit']))
		{
			//Verification des champs   
			if(empty($_POST['login']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password2']))
			{
				$error['empty'] = "<span>Champs vide</span>";
			}
			//pregmatch pour login
			$pattern = "/^\S*[a-z,A-Z,0-9]{4,}\S*/";
			if(!preg_match($pattern, $_POST['login'])){
				$error['login'] = "<span>4 caractères minimum</span>";
			}
			//Verification en bdd
			if($this->user->exists($_POST['login'])===-1)
			{
				$error['login'] = "<span>Login non disponible</span>";
			}
			
			//si email non valide
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				$error['email'] = "<span>Saisissez un email valide</span>";
			}

			
			$password = htmlspecialchars($_POST['password2']);
			//pregmatch pour password
			if(!preg_match($pattern, $password)){
				$error['password'] = "<span>4 caractéres minimum</span>";
			}
			//on définit les variables en entrée
			$login = htmlspecialchars($_POST['login']);
			$email = htmlspecialchars($_POST['email']);
			$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
			//Si les mdp ne corresponde pas
			if(parent::Verifypass($password, $password_hash)===false)
			{
				$error['password'] = "<span>Mots de passe non identiques</span>";
			}
			if(array_filter($error))
			{
			}
			else
			{
				if($_POST['droit']== 'utilisateur')
				{
					$droits = 1;
				}
				if($_POST['droit']== 'administrateur') 
				{
					$droits = 909;
				}
				
				$this->user->insertUser($login, $email, $password_hash, $droits);
				parent::Redirect("connexion");
			}
		}
		require 'Vue/vueInscription.php';
	}


}
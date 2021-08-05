<?php
require_once'Model/ModelUser.php';
require_once'Model/ModelAdmin.php';
require_once'Model/ModelAdminUser.php';
require_once'Model/ModelAdminProduits.php';
require_once'Model/ModelAdminCategories.php';
require_once'Model/ModelProduct.php';
require_once'Model/ModelPanier.php';

/**
 * Fait office d'interface entre les controleurs et les modeles
 * Les classes modèles sont instanciées ici
 * Tous les controleurs vont hériter de cette classe
 * Il devront utiliser les attributs de cette classe pour accéder aux classes modeles
 * Certaines fonctions utilisées globalement pourront être implémentées ici
 */
class Controleur
{

	protected $user;
	protected $admin;
	protected $adminuser;
	protected $adminproduits;
	protected $admincategories;
	protected $adminregions;
	protected $product;
	protected $panier;

	public function __construct()
	{
		$this->admincategories = new admincategories();
		$this->adminproduits = new adminProduits();
		$this->adminuser =new adminUser();
		$this->user = new user(); 
		$this->admin = new admin();
		$this->product = new product();
		$this->panier = new panier();
	}

	//Permet de rediriger vers une autre page
	public static function Redirect(string $url)
	{
		header("Location: index.php?page=".$url."");
		exit();
	}

	//Permet de verifier la validité d'un passorw avec un password haché
	public function Verifypass($password, $password_hash)
	{
		if(password_verify($password, $password_hash) === false)
		{
			return false;
		}
		else
			return true;
	}

	//Permet de verifier que l'utilisateur est bien un admin
	public function notAdmin($droits)
	{
		if($droits!=909)
		{
			return false;
		}
		else
		return true;
	}

}
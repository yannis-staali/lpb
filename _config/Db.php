<?php

//Classe permettant la connexion à la BDD sous forme de singleton
class db
{
	//La classe utilisatrice n'aura qu'a appeler cette propriété statique
	private static $instance = null;

	public static function getPdo(): PDO
	{
		if(self::$instance === null){
			self::$instance = new PDO('mysql:host=localhost;dbname=bonneplateforme;charset=utf8', 'root', '',[
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

			]);
		}
		return self::$instance;
	}
}
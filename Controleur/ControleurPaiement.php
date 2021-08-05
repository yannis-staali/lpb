<?php

require_once('vendor/autoload.php');

require_once('Controleur.php');

class Controleurpaiement extends Controleur
{
	protected $user;
	protected $panier;

	public function route_paiement()
	{

		if(isset($_POST['panier_confirmer']))
		{
			$total = $this->panier->getTotal($_SESSION['user']['id']);
			$total_calcul = $this->panier->calculTotal($total);

			\Stripe\Stripe::setApiKey('sk_test_51JAYzQJ9WCFPfyUzetAiC5JP2UmbGFEoT5NwElz4j2WZFLgZhQ02DTnZv6COVS5rtJ6yhccPi7JKrv3pvAjOZOhD00yp3uJLFC');

			$intent = \Stripe\PaymentIntent::create([
			'amount' => $total_calcul*100,
			'currency' => 'eur',
			'payment_method_types' => ['card'],
			]);

		}

		$error = [
			'empty' => '',                                                      
			'nom' => '',
			'prenom' => '',
			'telephone' => '',
			'email' => '',
			'adresse' => '',
			'ville' => '',
			'departement' => '',
			'postcode' => '',
			'pays' => '',
		];

		require 'Vue/vuePaiement.php';
	}

}
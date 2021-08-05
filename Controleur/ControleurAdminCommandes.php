<?php
require_once('Controleur.php');
 
class ControleurAdminCommandes extends Controleur
{
    protected $panier;


	public function route_adminCommandes()
    {

        $success = [
            'update' => '',
            'delete' => '',
            'insert' => ''
        ];
        $error = [
            'empty' => ''
        ];

		//ListIdcommande permet de recupérer tous les numéros de commandes
		$recupListId = $this->panier->TotallistIdCommandes();

		$poposh = [];

		for($i=0; $i<count($recupListId); $i++)
		{
			//On se sert du numéro de commande pour récupérer tous les articles quelle contient
			$recupComm[$i] = $this->panier->historiqueCommandesAdmin($recupListId[$i]['id_commande']);

			//On met tout dans le tableau poposh[], qui nous permettra d'afficher les données
			$poposh[] = $recupComm[$i];

		}
        
        require('Vue/vueAdminCommandes.php');
	}
}
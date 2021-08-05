<?php
require_once('Controleur.php');
 
class ControleurAdminCategories extends Controleur
{
    protected $admincategories;

	public function route_adminCategories(){

        $success = [
            'update' => '',
            'delete' => '',
            'insert' => ''
        ];
        $error = [
            'empty' => ''
        ];

        //Verfication admin
        if(parent::notAdmin($_SESSION['user']['droits'])===false)
        {
            parent::Redirect("accueil");
        }

        //Selectionne la categorie à update
        if(isset($_POST['updateCategorie']))
        {
            $id_categorie= intval($_POST['updateCategorie']);
            $categori = $this->admincategories->selectCategorie($id_categorie);
        }

        //Action changement
        if(isset($_POST['categorieUpdate']))
        {
            if(empty($_POST['nom'])){
                $error['empty'] = "<span>Entrez un nom</span>";
            }
            else{
               
                $id=intval($_POST['id']);
                $nom = htmlspecialchars($_POST['nom']);
                $this->admincategories->updateCategorie($id,$nom);
                $success['update'] = "<span>Catégorie modifiée</span>";
            }
        }

        //Suppression catégorie
        if(isset($_POST['deleteCategorie']))
        {
                $id = intval($_POST['deleteCategorie']);
                $this->admincategories->deleteCategorie($id);
                $success['delete'] = "<span>Catégorie supprimée</span>";
        }

        //Affichage des catégories
        if(!isset($_POST['updateCategorie']))
        {
            $categories = $this->admincategories->selectAllCategories();
        }

        //Insertion catégorie
        if(isset($_POST['insertCategorie']))
        {
            
            if(empty($_POST['categorie'])){
                $error['empty'] = "<span>La catégorie doit avoir un nom</span>";
            }
            else{
                $nom_categorie = htmlspecialchars($_POST['categorie']);
                $this->admincategories->insertCategorie($nom_categorie);
                $success['insert'] = "<span>Bravo Categorie INSERT</span>";
                header("refresh: 2;");
            }
        }
        
        require'Vue/vueAdminCategories.php';
	}
}
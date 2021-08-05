<?php
require_once 'Controleur.php';

class ControleurProduct extends Controleur
{
    protected $product;
    protected $panier;

    public function route_produits()
    {
       

        // AFFICHAGE PANIER
        // Si l'utilisateur est connecté 
        if(isset($_SESSION['user']['id'])) 
        {
            // On regarde s'il a deja un panier
            $checkPanier = $this->panier->panierExist($_SESSION['user']['id']);

            // si un panier existe 
            if($checkPanier !== false) 
            {
                //La variable $Panier (qui recupere les données) est utilisé dans la vueProduit pour afficher le contenu du panier
                // $Panier = $this->panier->getPanier($_SESSION['user']['id']);

                //on recupère le prix total
                $total = $this->panier->getTotal($_SESSION['user']['id']);

                $total_calcul = $this->panier->calculTotal($total);

            }
        }
        // ================================================================

        //\\ Si un utilisateur CLIQUE sur ajouter au panier
        if(isset($_POST['product'])) 
        {
                //on récupère l'id du produit (qui est contenu dans la Value du boutton ajout au panier)
                $id_produit = intval($_POST['product']);
                // $prix_produit = $this->panier-getPrice($id_produit);
                $prix_produit = $this->panier->getPrice($id_produit);

                //Si l'utilisateur est connecté
                if(isset($_SESSION['user']['id'])) 
                {
                        //on recupere son id
                        $id_utilisateur = $_SESSION['user']['id'];

                        //on verifie si un panier n'est pas deja présent, si c'est le cas on récupère toutes les lignes dans un tableau (ici $selectProduitPanier)
                        $selectProduitPanier = $this->panier->panierExist($id_utilisateur);

                        //si le panier n'existe pas
                        if($selectProduitPanier === false) 
                        {
                            // On en crée un en insérant une ligne dans la table liste_commande
                            $quantite = 1;
                            $statut = 0;
                            $this->panier->insertPanier($id_produit, $id_utilisateur, $quantite, $prix_produit);
                            $success = 'Ajout produit';
                            header("refresh: 0.3;");
                        }
                        //si le panier existe deja
                        else 
                        {
                        //on verifie si le produit ajouté n'est pas deja présent
                        $id_produitExist = $this->panier->produitExistInPanier($selectProduitPanier, $id_produit);
                        
                        //Si le produit ajouté n'existe pas encore on crée une ligne dans le panier
                        if($id_produitExist === false) 
                        {
                            $quantite = 1;
                            $statut = 0;
                            $this->panier->insertPanier($id_produit, $id_utilisateur, $quantite, $prix_produit);
                            $success = 'Ajout produit';
                            header("refresh: 0.3;");
                        }
                        //Sinon on incrémente la quantité et le prix
                        else 
                        {
                            //recuperation du prix total actuel
                            $actual_price = $this->panier->getActualPrice($selectProduitPanier, $id_produit);

                            //ajout du prix d'un nouvel article
                            $update_price = $actual_price + $prix_produit;
                        
                            //incrémentation de la quantité
                            $id_produitExist++;

                            $this->panier->updateQuantite($id_produitExist, $id_produit, $id_utilisateur, $update_price);
                            $success = 'Adding product';
                            header("refresh: 0.3;");
                        }
                    }
                }
        }

        //MSG SUCEESS DELETE
        if (isset($_GET['success'])) {
            $success['deleteProduit'] = '<span>Suppression</span>';
            header("Refresh:0.3; url=index.php?page=produits");
        }
        if (isset($_GET['successUpdate'])) {
            $success['updateQuantite'] = '<span>Ajout produit</span>';
            header("Refresh:0.3; url=index.php?page=produits");
        }

        //ACTION CLICK BOUTTON PLUS (+)
        if (isset($_GET['addOne'])) 
        {
            //on recupere l'id et la quantité
            $infoProduits = explode("__", $_GET['addOne']);
            //on incremente la quantité
            $infoProduits[1]++;

            // on recupere le prix total actuel
            $price = $this->panier->getActualPrice($checkPanier, $infoProduits[0]);
            //on recupere le prix d'un article a l'unité
            $price_unit = $this->panier->getPrice($infoProduits[0]);
            //on determine le nouveau prix après addition
            $new_price = $price + $price_unit;

            $this->panier->updateQuantite($infoProduits[1], $infoProduits[0], $_SESSION['user']['id'], $new_price );
            $success = "Ajout produit";
            header("Refresh:0.3; url=index.php?page=produits");
        }
        //ACTION CLICK BOUTTON MOINS (-)
        if (isset($_GET['deleteOne'])) 
        {
            //on recupere l'id et la quantité
            $infoProduits = explode("__", $_GET['deleteOne']);

            //on verifie la quantité actuelle et si elle est à 1 on supprime la ligne
            if($infoProduits[1] == 1)
            {
                $this->panier->deletePanier($_SESSION['user']['id'], $infoProduits[0]);
                $success = "Suppression";
                header("Refresh:0.3; url=index.php?page=produits");
            }
            //on decremente la quantité
            $infoProduits[1]--;

            // on recupere le prix total actuel
            $price = $this->panier->getActualPrice($checkPanier, $infoProduits[0]);
            //on recupere le prix d'un article a l'unité
            $price_unit = $this->panier->getPrice($infoProduits[0]);
            //on determine le nouveau prix après soustraction
            $new_price = $price - $price_unit;

            $this->panier->updateQuantite($infoProduits[1], $infoProduits[0], $_SESSION['user']['id'], $new_price);
            $success = "Suppression";
            header("Refresh:0.3; url=index.php?page=produits");
        }
        //ACTION CLICK SUR LA CORBEILLE
        if (isset($_GET['deletePanier'])) {
            $id_produit = intval($_GET['deletePanier']);
            $this->panier->deletePanier($_SESSION['user']['id'], $id_produit);
            $success = "Suppression";
            header("Refresh:0.3; url=index.php?page=produits");
        }

   
        //AFFICHAGE DES PRODUITS ============================================

        //SELECT PAR L'ID
        if(isset($_GET['produit_id_details'])) 
        {
            $id = $_GET['produit_id_details'];
            $result = $this->product->selectProductDetailsId($id);

        }

        //SELECT ALL PRODUIT BDD SANS FILTRES
        if (!isset($_GET['categories'])) {
            $allProducts = $this->product->selectAllProducts();
        }
        //SELECT PRODUCT BY CATEGORIE
        if (isset($_GET['categories'])) {
            $categorie = intval($_GET['categories']);
            $allProducts = $this->product->selectProductWhereCategories($categorie);
        }

        //barre de recherche
        if(isset($_POST['search']))
        {
            $search = $_POST['search'];

            $allProducts = $this->product->searchProduct($search);

        }


        //SELECT ALL CATEGORIE
        $allCategories = $this->admincategories->selectAllCategories();

        if(isset($_GET['produit_id_details']))
        {
            require 'Vue/vueProduitsDetails.php';
        }
        else require 'Vue/vueProduits.php';
        
    }
}

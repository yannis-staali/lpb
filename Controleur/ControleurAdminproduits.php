<?php
require_once'Controleur.php';

class ControleurAdminProduct extends Controleur
{
    protected $adminproduits;
    protected $admincategories;
    protected $adminregions;
    protected $product;

    public function route_adminProduct()
    {
            $error = [
                'empty' => '',
                'img' => ''
            ];
            $success = [
                'update' => '',
                'delete' => '',
                'product' => ''
            ];
        
            //Verification admin
            if(parent::notAdmin($_SESSION['user']['droits'])===false)
            {
                parent::Redirect("accueil");
            }
            $product = 1;
            if(!isset($_POST['updateProduct']))
            {
            $allProducts = $this->adminproduits->selectAllProductVendeur($_SESSION['user']['id'] );
            $categories = $this->admincategories->selectAllCategories();
            }
    
            if(isset($_POST['updateProduct']))
            {
                $id_product = intval($_POST['updateProduct']);
                $productUpdates = $this->product->selectProduct($id_product);
            }


            if(isset($_GET['img']))
            {
                $_SESSION['product']['url_image'] = $_GET['img'];
            }
            if(isset($_POST['productUpdate']))
            {
                if(empty($_POST['nom']) || empty($_POST['description']) || empty($_POST['prix']))
                {
                    $error['empty'] = "<p>Remplir tout les champs</p>";
                }
                else{

                    $nom = htmlspecialchars($_POST['nom']);
                    $description = htmlspecialchars($_POST['description']);
                    $prix = intval($_POST['prix']);
                    $stock = intval($_POST['stock']);
                    $id = intval($_POST['id']);
                    $categorie = htmlspecialchars($_POST['categorie']);

                    $this->adminproduits->updateProduct($id, $nom, $description, $prix, $stock, $categorie);
                    $success['product'] = "<p>Le produits a été modifié</p>";
                    header("refresh: 0.5;");
                }

            }

            //Suppression
            if(isset($_POST['deleteProduct']))
            {
                $accepte = "<div class='container_delete_ask'><h3>Etes-vous sûr de vouloir effacer le produit ?</h3><br>
                <form action='index.php?page=adminproduits' method='POST'>
                <button type='submit' name='accepte' value='".$_POST['deleteProduct']."'>OUI</button>
                <button type='submit' name='non' value='0'>NON</button>
                </form></div>";
            }

            //Verification avant suppression
            if(isset($_POST['accepte'])){
                $this->adminproduits->deleteProduct($_POST['accepte']);
                $context = "style/images/image_product/";
            unlink($context.$_POST['accepte']);
                $success['delete'] = "<p>Le produit a été supprimé</p>";
                header("refresh: 2;");
            }
     

            //Insertion
            if(isset($_POST['submitProduct']))
            {
                if(empty($_POST['nom']) || empty($_POST['description']) || empty($_POST['prix']) || empty($_POST['stock']))
                {
                    $error['empty'] = "<p>Remplir tous les champs</p>";
                    $fileError = $_FILES['image']['error'];
                    
                }
                else{
                    $file = $_FILES['image'];
                    $fileName = $_FILES['image']['name'];
                    $fileTmpName = $_FILES['image']['tmp_name'];
                    $fileSize = $_FILES['image']['size'];
                    $fileError = $_FILES['image']['error'];
                    $fileType = $_FILES['image']['type'];
                }


                    if($fileError===0)
                    {
                        $nom = htmlspecialchars($_POST['nom']);
                        $description = htmlspecialchars($_POST['description']);
                        $prix = intval($_POST['prix']);
                        $stock = intval($_POST['stock']);
                        // $id_utilisateur = $_SESSION['user']['id'];
                        $id_utilisateur = $_SESSION['user']['id'];
                        // j'ai changé la taille max du fichier
                        if($fileSize > 900000) 
                        {
                            $error['img'] = "<p>Erreur le fichier est trop large, limite : 1MB</p>";
                        } else {
                            $img_exe = pathinfo($fileName, PATHINFO_EXTENSION);
                            $img_exe_str = strtolower($img_exe);

                            $extension = array("jpg", "jpeg", "png", "svg");
                            if(in_array($img_exe_str, $extension))
                            {
                                $img_name = uniqid("IMG-", true).'.'.$img_exe_str;
                                $img_in_path = 'style/images/image_product/'.$img_name;
                                move_uploaded_file($fileTmpName, $img_in_path);
                                $id_categorie = intval($_POST['categorie']);
                                $this->adminproduits->insertProduct($nom, $description, $prix,$img_name,$stock,$id_categorie, $id_utilisateur);
                                $success['product'] = "<p>Le produit a été inséré</p>";
                                header("refresh: 2;");
                            } else {
                                $error['img'] = "<p>le type de fichier n'est pas supporté, uniquement : jpg jpeg png ou svg</p>";
                            }
                        }

                    } else {
                        $error['img'] = "<p>Aucune image chargée</p>";
                    }
            }
        require'Vue/vueAdminProduits.php';
    }
}

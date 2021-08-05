<?php
require_once'Controleur.php';

class ControleurAdminUser extends Controleur
{
    protected $adminuser;

	public function route_adminUser()
    {
        $error = [
            'empty' => ''
        ];
        $success = [
            'update' => '',
            'delete' => ''
        ];

        //Selectionne tous les users
        if(!isset($_POST['update']))
        {
            $user = $this->adminuser->selectalluser();
        }   
        //Selectionne un user en particulier
        if(isset($_POST['update']))
        {
            $id_utilisateur = intval($_POST['update']);
            $userUpdate = $this->adminuser->selectViaId($id_utilisateur);
        }

        //Modification User
        if(isset($_POST['updateUser']))
        {
            if(empty($_POST['id']) || empty($_POST['login']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['id_droits']))
            {
                $error['empty'] = "<span>Il y a des champs vide</span>";
            }
            else
            {
                $login = htmlspecialchars($_POST['login']);
                $email = htmlspecialchars($_POST['email']);
                $password = $_POST['password'];
                $id_droits = intval($_POST['id_droits']);
                $id = intval($_POST['id']);
                $this->adminuser->updateUser($login,$email,$password,$id_droits, $id);
                $success['update'] = "<span>Utilisateur modifié</span>";
                header("refresh: 0.5;");
            }
        }
        //Suppression user
        if(isset($_POST['delete'])){
            $accept = "<span>ETES VOUS SUR DE VOULOIR EFFACER ID n° ".$_POST['delete']."</span><br>
            <form action='index.php?page=adminuser' method='POST'>
            <button type='submit' name='accept' value='".$_POST['delete']."'>OUI</button>
            <button type='submit' name='non' value='0'>NON</button>
            </form>";
        }
        //Verification avant suppression
        if(isset($_POST['accept'])){
            $this->adminuser->deleteUser($_POST['accept']);
            $success['delete'] = "<span>Utilisateur supprimé</span>";
            header("refresh: 0.5;");
        }
        if(isset($_POST['non'])){
            echo"Ok";
        }
        //
        if(array_filter($success))
        {
        }
        if(array_filter($error))
        {
        }
        require'Vue/vueAdminUser.php';
    }
}
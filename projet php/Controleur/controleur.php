<?php
session_start();
require_once '../Model/connexion_db.php';
require_once '../Model/tbs_class.php';
require_once '../Model/modele.php';
require_once '../Model/functions.php';


$tbs = new clsTinyButStrong;
$cible = $_SERVER["PHP_SELF"];

// Initialise le nombre de produits dans le panier s'il n'existe pas déjà dans la session
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
    if (!isset($_SESSION['panier']['ids_lunnette'] )) {
        $_SESSION['panier']['ids_lunnette'] = array();
    }
}
// Gestion des requêtes pour les lunettes Hommee
$gabHomme = "../Vue/homme.html";
$reqHomme = "select * from lunettes where genre = 'Homme'";
$qHomme = new RQ_Lunette_Homme($connexion, $tbs, $reqHomme, $gabHomme);
$qHomme->executer();


// Gestion des requêtes pour les lunettes Femme
$gabFemme = "../Vue/femme.html";
$reqFemme = "select * from lunettes where genre = 'Femme'";
$qFemme = new RQ_Lunette_Femme($connexion, $tbs, $reqFemme, $gabFemme);
$qFemme->executer();


// Redirection et gestion des pages selon le paramètre 'page' dans l'URL

if(!isset($_GET["page"])){
    $acceuil ="../Vue/accueil.html";
    $tbs->LoadTemplate($acceuil);
}else{
    switch ($_GET["page"]){
        case "homme":   
            $ajout = "";
            if(isset($_GET["ajout"])){
                if($_GET["ajout"] == "ajouter"){
                    $ajout = "Votre produit à été ajouté au panier";
                }
            }  

            $qHomme->afficher();
            break;
        case "femme":
            $ajout = "";
            if(isset($_GET["ajout"])){
                if($_GET["ajout"] == "ajouter"){
                    $ajout = "Votre produit à été ajouté au panier";
                }
            }

            $qFemme->afficher();
            break;
        case "ajouter":
            $genre = "hommme";
            // Vérifie si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Vérifie si l'identifiant de la lunette est présent dans la requête POST
                if (isset($_POST['id_lunettes'])) {
                    $id_lunette = $_POST['id_lunettes'];
                    $genre = strtolower(getGenreById($connexion,$id_lunette)["genre"]);
                    // Récupère l'identifiant de la lunette
                    $_SESSION['panier']['ids_lunnette'][] = $id_lunette;
                }
                if($genre == "homme"){
                    header('Location: controleur.php?page=homme&ajout=ajouter');
                }elseif ($genre == "femme") {
                    header('Location: controleur.php?page=femme&ajout=ajouter');
                }else{
                    header('Location: controleur.php?');
                }
                }
            break;
            // Préparation des informations pour la page du panier
        case "panier":
                $gabaritPanier="../Vue/panier.html";

                $ids_sessions=$_SESSION['panier']['ids_lunnette'];

                $quantite = array_count_values($ids_sessions);
                $panier = array();
                foreach($ids_sessions as $id_session){
                    $lunnette = getLunetteByid($connexion, intval($id_session));
                    
                    $panier[] = array(
                        'img_lunette' => $lunnette[0]["image"],
                        'nom_lunette' => $lunnette[0]["nom_lunettes"],
                        'couleur_lunette' => $lunnette[0]["couleur_lunettes"],
                        'prix_lunette' => $lunnette[0]["prix_lunettes"],
                        'id_lunette' => $lunnette[0]["id_lunettes"],
                         'quantite' => $quantite[$lunnette[0]["id_lunettes"]]);
                        }


                $panier_consolidated = array();
                foreach ($panier as $item) {
                    $id_lunette = $item['id_lunette'];
                        if (!isset($panier_consolidated[$id_lunette])) {
                            $panier_consolidated[$id_lunette] = $item;
                        } 
                      }

                $i = 0;
                $lunImg = array();
                $lunNom = array();
                $lunCouleur = array();
                $lunPrix = array();
                $lunId = array();
                $lunQuan = array();
                $totalPrix = 0;
                foreach ($panier_consolidated as $ligne) {
                    $lunImg[$i++] = $ligne["img_lunette"];
                    $lunNom[$i++] = $ligne["nom_lunette"];
                     $lunCouleur[$i++] = $ligne["couleur_lunette"];
                     $lunPrix[$i++] = $ligne["prix_lunette"];
                    $lunPrix[$i++] = $ligne["prix_lunette"] * $ligne["quantite"];
                    $lunId[$i++] = $ligne["id_lunette"];
                     $lunQuan[$i++] = $ligne["quantite"];
                    $prix_article = $ligne["prix_lunette"] * $ligne["quantite"];
                    $totalPrix += $prix_article;
                    }
                
                $tbs->LoadTemplate($gabaritPanier);

                $tbs->MergeBlock("image_lunette_ajouter", $lunImg);
                $tbs->MergeBlock("nom_lunette_ajouter", $lunNom);
                $tbs->MergeBlock("couleur_lunette_ajouter", $lunCouleur);
                 $tbs->MergeBlock("prix_lunette_ajouter", $lunPrix);
                $tbs->MergeBlock("id_lunette_ajouter", $lunId);
                 $tbs->MergeBlock("quantite_lunette_ajouter", $lunQuan);

                $tbs->Show();

                break;
            case "supprimer":
                // Supprime un article du panier
                if(isset($_GET['id'])) {
                    $id_lunette_supprimer = $_GET['id'];
                    
                    $index = array_search($id_lunette_supprimer, $_SESSION['panier']['ids_lunnette']);
            
                    if ($index !== false) {
                        unset($_SESSION['panier']['ids_lunnette'][$index]);
                    }
                }
                
                    header('Location: controleur.php?page=panier');
                    break;
                case "confirmation_commande":
                    // Chargement de la page de confirmation de commande
                    
                    $tbs->LoadTemplate("../Vue/confirmation_commande.html");
                break;

                case "traitement":

                    // Traitement des données de commande avant finalisation

                    $ids_sessions=$_SESSION['panier']['ids_lunnette'];

                    $quantite = array_count_values($ids_sessions);
                    $panier = array();
                foreach($ids_sessions as $id_session){
                    $lunnette = getLunetteByid($connexion, intval($id_session));
                    
                    $panier[] = array(
                        'img_lunette' => $lunnette[0]["image"],
                        'nom_lunette' => $lunnette[0]["nom_lunettes"],
                        'couleur_lunette' => $lunnette[0]["couleur_lunettes"],
                        'prix_lunette' => $lunnette[0]["prix_lunettes"],
                        'id_lunette' => $lunnette[0]["id_lunettes"],
                         'quantite' => $quantite[$lunnette[0]["id_lunettes"]]
                    );
                }

                $panier_consolidated = array();

                foreach ($panier as $item) {
                    $id_lunette = $item['id_lunette'];
                        if (!isset($panier_consolidated[$id_lunette])) {
                            $panier_consolidated[$id_lunette] = $item;
                    } 
                }
                    if(isset($_POST["prenom"])){
                    $gabariTraitement = "../Vue/traitement.html";
                    $tbs->loadTemplate($gabariTraitement);

                    $prenom = $_POST['prenom'];
                    $nom = $_POST['nom'];
                    $mail = $_POST['email'];
                    $adresse = $_POST['adresse'];
                    $tel = $_POST['tel'];
                
                    $query = "INSERT INTO client (prenom, nom, mail, adresse, tel) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $connexion->prepare($query);
                    $stmt->bindParam(1, $prenom);
                    $stmt->bindParam(2, $nom);
                    $stmt->bindParam(3, $mail);
                    $stmt->bindParam(4, $adresse);
                    $stmt->bindParam(5, $tel);
                    $stmt->execute();
                    $id_client = $connexion->lastInsertId();
                     // Récupère bl'ID du client nouvellement inséré

                                        $query = "INSERT INTO commande (date_commande, Prix_total, id_client) VALUES (NOW(), ?, ?)";
                    $stmt = $connexion->prepare($query);
                    $stmt->bindParam(1, $prix_total);
                    $stmt->bindParam(2, $id_client);
                    $stmt->execute();
                    $id_commande = $connexion->lastInsertId(); // Récupère l'ID de la commande nouvellement insérée

                    

                    $query = "INSERT INTO ligne_de_commande (id_commande, Prix, quantite, id_lunettes) VALUES (?, ?, ?, ?)";
                    $stmt = $connexion->prepare($query);

                    foreach ($panier_consolidated as $item) {
                        $prix = $item['prix_lunette'] * $item['quantite'];
                        $quantite = $item['quantite'];
                        $id_lunettes = $item['id_lunette'];
                        $stmt->bindParam(1, $id_commande);
                        $stmt->bindParam(2, $prix);
                        $stmt->bindParam(3, $quantite);
                        $stmt->bindParam(4, $id_lunettes);
                        $stmt->execute();
                        }

                    var_dump($_POST);
                    var_dump($panier_consolidated);
                    }
                    
                    $_SESSION['panier']['ids_lunnette'] = array();


                    header('Location: ../Vue/fin.html');  
                   

                 exit(); 
                    
                break ; 
                case "fin":
                    // Redirection vers la page de fin après le traitement
                    $gabariFin = "../Vue/fin.html";
                    $tbs->loadTemplate($gabariFin);
                    break;


            
}
}

$tbs->show();

?>









<?php

class Requete
{
    protected $pdo;
    protected $tbs;
    protected $req;
    protected $gab;
    protected $data;
    public function __construct($pdo, $tbs, $req, $gab)
    {
        $this->pdo = $pdo;
        $this->tbs = $tbs;
        $this->req = $req;
        $this->gab = $gab;
    }

    public function executer() {
        $res = $this->pdo->prepare($this->req);
        $res->execute();
        $this->data = $res->fetchAll();
    }
}


class RQ_Lunette_Homme extends Requete
{
    public function afficher()
    {
        $i = 0;
        $lunImg = array();
        $lunNom = array();
        $lunCouleur = array();
        $lunPrix = array();
        $lunId = array();
        $ajout = "ffrs";

        foreach ($this->data as $ligne) {
            $lunImg[$i++] = $ligne["image"];
            $lunNom[$i++] = $ligne["nom_lunettes"];
            $lunCouleur[$i++] = $ligne["couleur_lunettes"];
            $lunPrix[$i++] = $ligne["prix_lunettes"];
            $lunId[$i++] = $ligne["id_lunettes"];

        }
        $this->tbs->LoadTemplate("{$this->gab}");
        $this->tbs->MergeBlock("image_lunette_homme", $lunImg);
        $this->tbs->MergeBlock("nom_lunette_homme", $lunNom);
        $this->tbs->MergeBlock("couleur_lunette_homme", $lunCouleur);
        $this->tbs->MergeBlock("prix_lunette_homme", $lunPrix);
        $this->tbs->MergeBlock("id_lunette_homme", $lunId);

        $this->tbs->Show();
    }
}

class RQ_Lunette_Femme extends Requete
{
    public function afficher()
    {
        $i = 0;
        $lunImg = array();
        $lunNom = array();
        $lunCouleur = array();
        $lunPrix = array();
        $lunId = array();

        foreach ($this->data as $ligne) {
            $lunImg[$i++] = $ligne["image"];
            $lunNom[$i++] = $ligne["nom_lunettes"];
            $lunCouleur[$i++] = $ligne["couleur_lunettes"];
            $lunPrix[$i++] = $ligne["prix_lunettes"];
            $lunId[$i++] = $ligne["id_lunettes"];
            

        }
        $this->tbs->LoadTemplate("{$this->gab}");
        $this->tbs->MergeBlock("image_lunette_femme", $lunImg);
        $this->tbs->MergeBlock("nom_lunette_femme", $lunNom);
        $this->tbs->MergeBlock("couleur_lunette_femme", $lunCouleur);
        $this->tbs->MergeBlock("prix_lunette_femme", $lunPrix);
        $this->tbs->MergeBlock("id_lunette_femme", $lunId);
        $this->tbs->Show();
    }
}

class RQ_Lunette_ajouter extends Requete
{
    public function afficher()
    {
        $i = 0;
        $lunImg = array();
        $lunNom = array();
        $lunCouleur = array();
        $lunPrix = array();
        $lunId = array();
        foreach ($data as $ligne) {
            $lunImg[$i++] = $ligne["image"];
            $lunNom[$i++] = $ligne["nom_lunettes"];
            $lunCouleur[$i++] = $ligne["couleur_lunettes"];
            $lunPrix[$i++] = $ligne["prix_lunettes"];
            $lunId[$i++] = $ligne["id_lunettes"];
        }

        $this->tbs->LoadTemplate("{$this->gab}");
        $this->tbs->MergeBlock("image_lunette_ajouter", $lunImg);
        $this->tbs->MergeBlock("nom_lunette_ajouter", $lunNom);
        $this->tbs->MergeBlock("couleur_lunette_ajouter", $lunCouleur);
        $this->tbs->MergeBlock("prix_lunette_ajouter", $lunPrix);
        // $this->tbs->MergeBlock("id_lunette_ajouter", $lunId);

        $this->tbs->Show();
    }
}






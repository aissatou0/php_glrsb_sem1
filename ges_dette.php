<?php 
 /*
   Realiser une application qui contient les use case suivants : 
      1.Ajouter client avec  option ajouter un user
      2.Lister les clients 
      3.Ajouter une dette a un client
      4.Lister les dettes un client
      5.Lister toutes  les dettes
  */
//Matrice 


/*
   $clients[0] ==> [
         "telephone"=>"771001010",
          "nom"=>"Wane",
         "prenom"=>"Baila",
         "adresse"=>"OF"
        ]
          $clients[0]["nom"] ==> Wane
          $clients[0]["adresse"] ==> Wane

          $clients[1]["telephone"] ==> 771001011
          foreach ($clients as  $client) {
                echo "Telephone : ". $client["telephone"]."\t";
                echo "Nom : ". $client["nom"]."\t";
                echo "Prenom : ". $client["prenom"]."\t";
                echo "Adresse : ". $client["adresse"]."\t";
                echo"---------------------------------------"."\n";
          }
 */
//Use Case 
function ajouterClient(array &$clients,array $client){
    $clients[]=$client;
}
//Use Case Interne 

function estVide(string $value):bool{
    //$value=="" ou empty($value)
    if (empty($value)) {
       return true;
    }
    return false;
}
function rechercherClientParTel(array $clients,string $telephone):array|null{
    foreach ($clients as  $client) {
        if ($client["telephone"] == $telephone) {
            return $client;
        }
     }
     return null;
}



//Fonction Affichage et de saisie
function saisieChampObligatoire(string $sms):string{
    do {
        $value= readline($sms);
    } while (estVide($value));
   return $value;
}
function telephoneIsUnique(array $clients,string $sms):string{
    do {
        $value= readline($sms);
    } while (estVide($value) || rechercherClientParTel($clients,$value)!=null);
    return $value;
   
}


function saisieClient(array $clients):array{
    return [
        "telephone"=>telephoneIsUnique($clients,"Entrer le Telephone: "),
         "nom"=>saisieChampObligatoire(" Entrer le Nom: "),
         "prenom"=>saisieChampObligatoire(" Entrer le Prenom: "),
         "adresse"=>saisieChampObligatoire(" Entrer l'Adresse: "),
    ] ; 
}

function afficheClient(array $clients):void{
    if (count($clients)==0) {
        echo "Pas de client a affiche";
    }else {
        foreach ($clients as  $client) {
            echo"\n-----------------------------------------\n";
            echo "Telephone : ". $client["telephone"]."\t";
            echo "Nom : ". $client["nom"]."\t";
            echo "Prenom : ". $client["prenom"]."\t";
            echo "Adresse : ". $client["adresse"]."\t";
      }
    }
    
}

function ajouterDette(array &$clients, string $telephone, array $dette): void {
    foreach ($clients as &$client) {
        if ($client["telephone"] === $telephone) {
            if (!isset($client["dettes"])) {
                $client["dettes"] = [];
            }
            $client["dettes"][] = $dette;
            echo "Dette ajoutée avec succès.\n";
            return;
        }
    }
    echo "Client introuvable.\n";
}
function saisieDette(): array {
    $montant = (float)saisieChampObligatoire("Entrer le montant de la dette: ");
    $description = saisieChampObligatoire("Entrer la description de la dette: ");
    return [
        "montant" => $montant,
        "description" => $description,
        "date" => date("Y-m-d"),
    ];
}

function listerDettesClient(array $clients, string $telephone): void {
    $client = rechercherClientParTel($clients, $telephone);
    if ($client === null) {
        echo "Client introuvable.\n";
        return;
    }
    if (!isset($client["dettes"]) || count($client["dettes"]) === 0) {
        echo "Aucune dette trouvée pour ce client.\n";
        return;
    }
    echo "\nDettes du client {$client['nom']} {$client['prenom']} :\n";
    foreach ($client["dettes"] as $index => $dette) {
        echo "\nDette " . ($index + 1) . ":\n";
        echo "Montant: {$dette['montant']} | Description: {$dette['description']} | Date: {$dette['date']}\n";
    }
}

function listerToutesDettes(array $clients): void {
    $hasDettes = false;
    foreach ($clients as $client) {
        if (isset($client["dettes"])) {
            echo "\nDettes de {$client['nom']} {$client['prenom']} :\n";
            foreach ($client["dettes"] as $index => $dette) {
                echo "\nDette " . ($index + 1) . ":\n";
                echo "Montant: {$dette['montant']} | Description: {$dette['description']} | Date: {$dette['date']}\n";
            }
            $hasDettes = true;
        }
    }
    if (!$hasDettes) {
        echo "Aucune dette enregistrée.\n";
    }
}

 function menu(){
     echo "
      1.Ajouter client avec  option ajouter un user\n
      2.Lister les clients\n 
      3.Ajouter une dette a un client\n
      4.Lister les dettes un client\n
      5.Lister toutes  les dettes\n
      6.Quitter\n";
     return (int)readline(" Faites votre choix: ");
 }




 function principal(){
    $clients=[];
    do {
       $choix= menu();
       switch ($choix) {
        case 1:
           $client= saisieClient($clients);
           ajouterClient($clients,$client);
        break;
        case 2:
            afficheClient( $clients);
        break;
        case 3:
            $telephone = saisieChampObligatoire("Entrer le téléphone du client: ");
            $dette = saisieDette();
            ajouterDette($clients, $telephone, $dette);
        break;
        case 4:
            $telephone = saisieChampObligatoire("Entrer le téléphone du client: ");
            listerDettesClient($clients, $telephone);
        break;
        case 5:
            listerToutesDettes($clients);
        break;
        
        default:
           echo "Veullez faire un bon choix: ";
        break;
       }

    } while ($choix!=6);
 }
 principal();
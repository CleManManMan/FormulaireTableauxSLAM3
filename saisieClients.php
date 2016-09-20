<?php
// Démarrage de la session AVANT d'écrire du code HTML afin de
// conserver l'information indiquant si c'est le premier accès
// et pour transmettre le tableau des personnes
session_start();
?>
<!DOCTYPE html>
<html>
 <head> <!-- Entête HTML -->
    <meta charset="utf-8" />
    <title>Saisie de plusieurs personnes</title>
    <!-- Feuille de style -->
    <link href="css/saisie_liste_personnes.css" rel="stylesheet" type="text/css" /> 
 </head>
 <body>
   <?php
   define("WEB_EOL","<br/>");
   // Si l'appel provient d'un terminer, on affiche le tableau
   if (!empty($_POST['terminer']))
   {?>
    <table summary="Tableau des personnes">
     <caption>Tableau des personnes</caption>
     <thead>
     <tr> <!-- entête du tableau -->
        <th>ID</th>
        <th>Nom</th>
        <th>Pr&eacute;nom</th>
        <th>Age</th>
     </tr>
     </thead>
     <?php
     //Vérification que le tableau existe:au moins une saisie
     if (isset($_SESSION['tab_personnes']))
     {
         $tab_personnes=$_SESSION['tab_personnes'];
         foreach ($tab_personnes as $ID => $une_personne)
         {
            echo "<tr>";
            echo "<td>$ID</td>";
            foreach ($une_personne as $EtiqChamp => $ValChamp)
            {
                echo "<td>$ValChamp</td>";
            }
            echo "</tr>";
         }
     }
     else // Affichage d'un message dans le tableau 
     { 
        echo "<td colspan=\"4\"><b>Aucune donn&eacute;e &agrave; afficher</b></td>";
        echo "</tr>";
     }
    ?>
    </table>
    <?php
   }
   // Affichage du formulaire et des informations en dessous 
   else
   { ?> <!-- --- Affichage du formulaire --- -->
   <form action="saisieClients.php" method="post">
      <fieldset>
      <legend>Saisissez les donn&eacute;es d'une nouvelle personne :</legend><br/>
      Entrez un nom (ex : Dupont) : <input type="text" name="Nom" size="20" maxlength="20" autofocus/><br/><br/>
      Entrez un pr&eacute;nom (ex : Jean) : <input type="text" name="Prenom" size="40" maxlength="40" /><br/><br/>
      Entrez un &acirc;ge (ex : 28) : <input type="text" name="Age" size="3" maxlength="3" pattern="[1-9][0-9]{1,2}" /><br/><br/>
      <input type="submit" name="valider" value="Valider cette personne" />
      <!-- Ajout du bouton pour terminer la saisie  -->
      <input type="reset" value="Effacer le formulaire" />
      <input type="submit" name="terminer" value="Terminer la Saisie" />
      </fieldset>
     </form>
     <?php
     // Affichage du résultat du traitement précédent
     // - soit le rangement dans le tableau tab_personnes
     // - soit un message d'erreur (sauf premier affichage)
     // --- Récupération  des valeurs saisies ---
     if (isset($_POST['Nom'])) $Nom = $_POST['Nom'] ;
     else $Nom     = ''  ;
     if (isset($_POST['Prenom'])) $Prenom = $_POST['Prenom'] ;
     else $Prenom  = ''  ;
     if (isset($_POST['Age'])) $Age = $_POST['Age'] ;
     else $Age     = ''  ;
     // --- Traitement des données saisies ---
     // Suppression des espaces : début, fin et multiples
     // Remplace espace par un moins noms et prénoms composés
     // -- Nom --
     $Nom    = trim($Nom)                          ;
     $Nom    = str_replace('-', ' ', $Nom)         ;
     $Nom    = preg_replace('/\s{2,}/', ' ', $Nom) ;
     $Nom    = strtolower($Nom)                    ;
     $Nom    = ucwords($Nom)                       ;
     $Nom    = str_replace(' ', '-', $Nom)         ;
     // -- Prenom --
     $Prenom = trim($Prenom)                         ;
     $Prenom = str_replace('-', ' ', $Prenom)        ;
     $Prenom = preg_replace('/\s{2,}/', ' ', $Prenom);
     $Prenom = strtolower($Prenom)                   ;
     $Prenom = ucwords($Prenom)                      ;
     $Prenom = str_replace(' ', '-', $Prenom)        ;
     // -- Age --
     $Age = trim($Age)                          ;
     $Age = preg_replace('/\s{2,}/', ' ', $Age) ;
     // Si ce n'est pas la première saisie
     if (isset($_SESSION['Afficher_Messages_Champs']))
     {// Vérification que la saisie n'est pas vide
      if (!empty($Nom) && !empty($Prenom) && !empty($Age) )
      {$Age = intval($Age)                        ;
       if (!(($Age>0)&&($Age<120)))
        echo "Le champ Age est invalide".WEB_EOL;
       else
       {?>
        <table summary="Rangement dans le tableau">
         <caption>Derni&eacute;re personne saisie</caption>
         <thead>
          <tr> <!-- entête du tableau -->
           <th>Nom</th>
           <th>Pr&eacute;nom</th>
           <th>Age</th>
          </tr>
         </thead>
         <tr>
          <td><?php echo $Nom    ; ?></td>
          <td><?php echo $Prenom ; ?></td>
          <td><?php echo $Age    ; ?></td>
         </tr>
        </table>
        <?php
        // Rangement dans $tab_personnes de la session
        $_SESSION['tab_personnes'][]=array($Nom,$Prenom,$Age);
        }
      }
      // Affichage des messages d'erreur si champs vides
      else
      {echo "<fieldset>";
       echo "<legend>Valeurs &agrave; renseigner :</legend><br/>";
       if (empty($Nom)) echo "Le champ Nom est vide".WEB_EOL;
       if (empty($Prenom)) echo "Le champ Pr&eacute;nom est vide".WEB_EOL;
       if (empty($Age)) echo "Le champ Age est vide".WEB_EOL;
       if ($Age==0) echo "Le champ Age est invalide".WEB_EOL;
       echo "</fieldset>";
      }
     }
     // Ce n'est pas la première saisie:la variable indiquant
     // d'afficher les messages d'erreurs est positionnée
     else
     {$_SESSION['Afficher_Messages_Champs']="oui";}
   } ?>
 </body>
</html>

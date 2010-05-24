Installation
============

1.  Renseignez les identifiants de votre base de données MySQL 
('host', 'user', 'pass' et 'base') et les URLs / chemins propre à votre configuration dans le 
**fichier local.php** situé dans le dossier inc/configs/.

2.  Importez **tables.sql** dans votre base de données MySQL.

3.  Créez les **dossiers des ressources** assets/, assets/cache/, assets/cache/tpl/, 
assets/upload/ et assets/users/. Utilisez uniquement des chemins
relatifs pour définir l'emplacement de ces dossiers.

4.  Si vous voulez **mettre en ligne le site**, il faut simplement créer un 
fichier prod.php dans le dossier inc/configs/, en reprenant la même
structure que local.php. S'il y a deux configurations, prod.php
sera toujours inclut en priorité.


Contribuer
==========

Les contributeurs sont évidemment les bienvenues. N'hésitez pas à "forker" le
projet si vous avez plusieurs modifications ou fonctionnalités à
soumettre. Vous pouvez toujours faire un patch, 
que je me ferai le plaisir de corriger s'il est effectivement valable.

Merci d'avance aux contributeurs. :-)


Contact
=======

*  **Twitter :**  [@nephthys](http://twitter.com/nephthys)  
*  **Email :**  camille@nouweo.com

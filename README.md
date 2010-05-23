Installation
============

1.  Modifier le fichier de configuration de base dans ./inc/configs/local.php.
Les identifiants de votre base de données MySQL ('host', 'user', 'pass' et
'base), les URLs et chemins du site ('siteurl' et 'assets').

2.  Importer tables.sql dans votre base de données MySQL.

3.  Créer les dossiers des ressources ../assets/, ../assets/cache/, 
../assets/upload/ et ../assets/users/. Utilisez uniquement des chemins
relatifs pour définir l'emplacement de ces dossiers.

4.  Si vous voulez mettre en ligne le site, il faut simplement créer un 
fichier prod.php dans le dossier ./inc/configs/, en reprenant la même
structure que local.php. S'il y a les deux configurations, prod.php
sera toujours inclut en priorité.


Contribuer
=========

Les contributeurs sont évidemment les bienvenues. N'hésitez pas à "forker" le
projet si vous avez plusieurs modifications ou fonctionnalités à
soumettre.

Autrement, vous pouvez toujours faire un patch, que je me ferai le plaisir
de corriger s'il est effectivement valable.

Merci d'avance à ceux qui contribueront. :-)


Contact
=======

*  **Twitter :** : [@nephthys](http://twitter.com/nephthys)  
*  **Email :**  camille@nouweo.com

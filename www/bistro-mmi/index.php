<?php
/*
---------------------------------------------------------------
le routeur FRONTAL

REMARQUE 1:
le projet est livré sans le dossier /vendor (pour limiter la taille disque)
Donc, il faut commmencer (à partir du dossier /app-bistro) par faire un:
composer update
pour que le dossier /vendor soit construit.

REMARQUE 2:
pour travailler avec ce projet, on peut :
- soit utiliser le package XAMPP
- soit démarrer
o un serveur APACHE local depuis le dossier /s3.bistro-mmi
php -S localhost:8000 -t www

REMARQUE 3:
COMPOSER charge 4 paquets:
digitalnature/php-ref     pour le debug ( instructions r() et ~r() )
twig/twig                 pour les vues
filp/whoops               le gestionnaire des erreurs
nikic/fast-route          le moteur de routage

 */
include '../../app-bistro/bootstrap.php';

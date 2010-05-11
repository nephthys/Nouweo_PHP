<?php
/*
 *  Copyright (C) 2009 Nouweo
 *  
 *  Nouweo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

session_start();
/**
 *  Générer une suite alphanumérique aléatoire
 *  @author Cam
 *  @param integer $car     Le nombre de caractères
 *  @return string
 */
function generer_code( $car ) 
{
    $string = '';
    $chaine = '123456789';
    
    for( $i = 0; $i < $car; $i++ ) {
        $string .= $chaine[rand()%strlen($chaine)];
    }
    
    return $string;
}

function make_img( $content ) {
    $timage = array((strlen($content)*20)+10,28); // array(largeur, hauteur) de l'image; ici la largeur est fonction du nombre de lettre du contenu, on peut bien sur mettre une largeur fixe.
    $content = preg_replace( '/(\w)/', '\\1 ', $content); // laisse plus d'espace entre les lettres
    $image = imagecreatetruecolor($timage[0], $timage[1]); // création de l'image

    // definition des couleurs
    $fond = imageColorAllocate($image, 240,255,240);
    $grey = imageColorAllocate($image, 210, 210, 210);
    $text_color = imageColorAllocate($image, rand(0, 100), rand(0, 50), rand(0, 60));

    imageFill($image, 0, 0, $fond); // on remplit l'image de blanc

    //On remplit l'image avec des polygones
    for($i=0, $imax=mt_rand(3,5);$i<$imax;$i++)
    {
        $x=mt_rand(3,10);
        $poly=array();
        for($j=0;$j<$x;$j++)
        {
            $poly[]=mt_rand(0,$timage[0]);
            $poly[]=mt_rand(0,$timage[1]);
        }
        
        imageFilledPolygon($image, $poly, $x, imageColorAllocate($image, mt_rand(150,255), mt_rand(150,255), mt_rand(150,255)));
    }
    
    // Création des pixels gris
    for ($i = 0; $i < $timage[0] * $timage[1] / rand(15, 18); $i++)
    {
        imageSetPixel($image, rand(0, $timage[0]), rand(0, $timage[1]), $grey);
    }

    // affichage du texte demandé; on le centre en hauteur et largeur (à peu près ^^")
    //imageString($image, 5, ceil($timage[0]-strlen($content)*8)/2, ceil($timage[1]/2)-9, $content, $text_color);
    $longueur_chaine=strlen($content);
    for($ch=0;$ch<$longueur_chaine;$ch++)
        imagettftext($image, 18, mt_rand(-30,30) , (10*($ch+1)) , mt_rand(18,20), $text_color, 'res/georgia.ttf', $content[$ch]);

    $type = function_exists('imageJpeg') ? 'jpeg' : 'png';
    @header('Content-Type: image/' . $type);
    @header('Cache-control: no-cache, no-store');
    ($type =='png') ? imagePng($image) : imageJpeg($image);
    ImageDestroy($image);

    exit();
}

$code_genere=generer_code(5);
$_SESSION['cap_nw'] = $code_genere;
make_img( $code_genere );
?>

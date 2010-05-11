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

/*  ***
*   Uploade une image attachée à une news et créé des miniatures de celle-ci
*   @param integer $id_image    ID de l'image
*   @param integer $ordre       Première image de la news ou... ?
*   @return integer
*** */
function add_img_news($id_news, $ordre = 1)
{
    $file2up = $_FILES['file']['tmp_name'];
    $name_file = $_FILES['file']['name'];
    $taille_file = filesize($file2up);

    $array_extensions = array('gif', 'png', 'jpg', 'jpeg');
    $ext_upload = strtolower(substr(strrchr($name_file, '.'), 1));
    $nom_fichier = substr(time(), 0, -2) . mt_rand(1, 999);

    // Extensions non autorisées
    if (!in_array($ext_upload, $array_extensions)) {
        return FALSE;
    }

    // La taille de l'image est trop élevée
    if (round($taille_file / 1000) > Nw::$droits['quota_max_size_img'] && Nw::$droits['quota_max_size_img'] != 0) {
        return FALSE;
    }

    $link_picture_hd    = PATH_ROOT.Nw::$assets['dir_upload'].'hd/'.$nom_fichier.'_'.$id_news.'.png';
    $link_th2           = PATH_ROOT.Nw::$assets['dir_upload'].'th2/'.$nom_fichier.'_'.$id_news.'.png';

    $link_th_hd         = PATH_ROOT.Nw::$assets['dir_upload'].'thhd/'.$nom_fichier.'_'.$id_news.'.png';
    $link_th_hd_tmp     = PATH_ROOT.Nw::$assets['dir_upload'].'thhd/'.$nom_fichier.'_'.$id_news.'.tmp.png';

    // Upload de l'image en taille réelle
    move_uploaded_file($file2up, $link_picture_hd);
    chmod($link_picture_hd, 0777);

    create_mini($link_picture_hd, PATH_ROOT.Nw::$assets['dir_upload'].'th3/'.$nom_fichier.'_'.$id_news.'.tmp.png', $ext_upload, 300);
    recadrer_image(PATH_ROOT.Nw::$assets['dir_upload'].'th3/'.$nom_fichier.'_'.$id_news.'.tmp.png', PATH_ROOT.Nw::$assets['dir_upload'].'th3/'.$nom_fichier.'_'.$id_news.'.png', 85, 230);
    @unlink(PATH_ROOT.Nw::$assets['dir_upload'].'th3/'.$nom_fichier.'_'.$id_news.'.tmp.png');
    chmod(PATH_ROOT.Nw::$assets['dir_upload'].'th3/'.$nom_fichier.'_'.$id_news.'.png', 0777);

    create_mini($link_picture_hd, PATH_ROOT.Nw::$assets['dir_upload'].'th1/'.$nom_fichier.'_'.$id_news.'.png', $ext_upload, 80);
    create_mini($link_picture_hd, $link_th2, $ext_upload, 180);
    chmod(PATH_ROOT.Nw::$assets['dir_upload'].'th1/'.$nom_fichier.'_'.$id_news.'.png', 0777);
    chmod($link_th2, 0777);

    add_symb_photo($link_th2, PATH_ROOT.Nw::$assets['dir_upload'].'cfg/symb_1.png');

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_images (i_id_news, i_nom, i_date, i_ordre)
    VALUES('.intval($id_news).', \''.$nom_fichier.'\', NOW(), '.intval($ordre).')') OR Nw::$DB->trigger(__LINE__, __FILE__);

    $id_last_image = Nw::$DB->insert_id;

    return $id_last_image;
}

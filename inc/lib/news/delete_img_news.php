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

function delete_img_news($id_image, $id_news)
{
    $rqt_image = Nw::$DB->query('SELECT i_nom FROM '.Nw::$prefix_table.'news_images WHERE i_id = '.intval($id_image)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $donnees_image = $rqt_image->fetch_assoc();

    @unlink(PATH_ROOT.Nw::$assets['dir_upload'].'hd/'.$donnees_image['i_nom'].'_'.$id_news.'.jpg');
    @unlink(PATH_ROOT.Nw::$assets['dir_upload'].'th1/'.$donnees_image['i_nom'].'_'.$id_news.'.jpg');
    @unlink(PATH_ROOT.Nw::$assets['dir_upload'].'th2/'.$donnees_image['i_nom'].'_'.$id_news.'.jpg');
    @unlink(PATH_ROOT.Nw::$assets['dir_upload'].'th3/'.$donnees_image['i_nom'].'_'.$id_news.'.jpg');

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news SET n_id_image = 0 WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

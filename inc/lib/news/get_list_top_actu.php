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

function get_list_top_actu($limit = 5)
{
    $add_champs_sql = '';
    $add_jointure_sql = '';
    $list_news = array();

    // Si l'utilisateur est connecté
    if (is_logged_in())
    {
        $add_champs_sql = ', v_id_membre';
        $add_jointure_sql = ' LEFT JOIN '.Nw::$prefix_table.'news_vote ON (n_id = v_id_news AND v_id_membre = '.intval(Nw::$dn_mbr['u_id']).')';
    }

    $rqt_list_news = Nw::$DB->query('SELECT i_id, i_nom, '.decalageh('n_date', 'date_news').',
        n_id, n_id_auteur, n_id_cat, n_titre, n_etat, n_vues, n_private, c_id, c_rewrite, c_nom,
        n_nbr_coms, n_nb_votes'.$add_champs_sql.'
        FROM '.Nw::$prefix_table.'news
            LEFT JOIN '.Nw::$prefix_table.'categories ON c_id = n_id_cat
            LEFT JOIN '.Nw::$prefix_table.'news_images ON i_id = n_id_image'.$add_jointure_sql.'
        WHERE n_etat = 3 AND n_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
        GROUP BY n_id ORDER BY n_nb_votes DESC, n_vues DESC
        LIMIT '.$limit) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees_news = $rqt_list_news->fetch_assoc())
        $list_news[] = $donnees_news;

    return $list_news;
}

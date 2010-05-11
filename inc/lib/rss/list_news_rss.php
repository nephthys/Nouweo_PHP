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

function list_news_rss($limit=20, $params=array())
{
    $list_news = array();
    $param_sql = (count($params) > 0) ? 'WHERE '.implode(' AND ', $params).' ' : '';

    // Rqt SQL
    $rqt_list_news = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, n_resume, n_nb_votes, n_nb_versions, n_id, n_id_auteur, n_id_cat, n_titre, v_texte, n_etat, n_vues, n_private, n_nbr_coms, i_id, i_nom,
        DATE_FORMAT(n_date, "%a, %d %b %Y %H:%i:%s") AS date, DATE_FORMAT(n_last_mod, "%Y-%m-%dT%H:%i:%s%P") AS date_sitemap, u_id, u_pseudo, u_alias, u_avatar
        FROM '.Nw::$prefix_table.'news
            LEFT JOIN '.Nw::$prefix_table.'news_versions ON (v_id_news = n_id AND v_id = n_last_version)
            LEFT JOIN '.Nw::$prefix_table.'members ON n_id_auteur = u_id
            LEFT JOIN '.Nw::$prefix_table.'news_images ON i_id = n_id_image
            LEFT JOIN '.Nw::$prefix_table.'categories ON c_id = n_id_cat
        '.$param_sql.'GROUP BY n_id ORDER BY n_date DESC LIMIT '.$limit
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees_news = $rqt_list_news->fetch_assoc()) {
        $list_news[] = $donnees_news;
    }

    return $list_news;
}

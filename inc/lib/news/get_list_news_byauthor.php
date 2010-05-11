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

function get_list_news_byauthor($id_membre, $params=array(), $limit = 5)
{
    $list_news = array();
    $where_clause = ( count( $params ) > 0 ) ? ' AND '.implode(' AND ', $params).' ' : '';

    $rqt_list_news = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, n_id, n_id_auteur, n_id_cat, n_titre, n_etat, n_vues, n_private, n_nbr_coms
        FROM '.Nw::$prefix_table.'news
        LEFT JOIN '.Nw::$prefix_table.'categories ON c_id = n_id_cat
        WHERE n_id_auteur = '.intval($id_membre).$where_clause.'
        ORDER BY n_date DESC
        LIMIT '.$limit) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees_news = $rqt_list_news->fetch_assoc())
        $list_news[] = $donnees_news;

    return $list_news;
}

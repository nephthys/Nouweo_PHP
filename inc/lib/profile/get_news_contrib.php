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

function get_news_contrib($where='', $ordre = 'n_date, v_date DESC', $page = '', $element_par_page=0)
{
    $where_clause = ( strlen( $where ) > 0 ) ? 'WHERE ' . $where . ' ' : '';
    $list = array();

    if (!empty($page) && is_numeric($page))
    {
        $premierMessageAafficher = ($page - 1) * $element_par_page;
        $end_rqt_sql = ' LIMIT '.$premierMessageAafficher . ', '.$element_par_page.' ';
    }

    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, i_id, i_nom, v_ip, v_nb_mots, v_diff_mots, v_id, v_id_membre, v_id_news, v_raison, n_etat, n_id, n_titre, '.decalageh('v_date', 'date').'
        FROM '.Nw::$prefix_table.'news_versions
            LEFT JOIN '.Nw::$prefix_table.'news ON v_id_news = n_id
            LEFT JOIN '.Nw::$prefix_table.'categories ON n_id_cat = c_id
            LEFT JOIN '.Nw::$prefix_table.'news_images ON i_id = n_id_image
        '.$where_clause.'ORDER BY '.$ordre.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc())
        $list[] = $donnees;

    return $list;
}

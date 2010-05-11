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
*   Liste les news
*   @param string $where            Clause WHERE
*   @param string $ordre            Clause ORDER BY
*   @param string $page         Page en cours (si vide, aucune page)
*   @param integer $element_par_page    Nbr de news par page (0 = toutes les news)
*   @return array
*** */
function get_list_votes_news($where='', $ordre='v_date DESC', $page = '', $element_par_page=0)
{
    $where_clause = ( strlen( $where ) > 0 ) ? 'WHERE ' . $where . ' ' : '';
    $list = array();

    if (!empty($page) && is_numeric($page))
    {
        $premierMessageAafficher = ($page - 1) * $element_par_page;
        $end_rqt_sql = ' LIMIT '.$premierMessageAafficher . ', '.$element_par_page.' ';
    }
    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT v_etat, v_id, v_type, '.decalageh('v_date', 'date').', u_id, u_pseudo, u_alias, u_avatar
        FROM '.Nw::$prefix_table.'news_vote
            LEFT JOIN '.Nw::$prefix_table.'members ON v_id_membre = u_id
        '.$where_clause.'GROUP BY v_id ORDER BY '.$ordre.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc()) {
        $list[] = $donnees;
    }

    return $list;
}

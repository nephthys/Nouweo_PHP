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
*   Liste les membres
*   @param string $where            Clause WHERE
*   @param string $ordre            Clause ORDER BY
*   @param string $page         Page en cours (si vide, aucune page)
*   @param integer $element_par_page    Nbr de membres par page (0 = tous les membres)
*   @return array
*** */
function get_list_mbr($where='', $ordre='u_pseudo ASC', $page = '', $element_par_page=0)
{
    $where_clause   = ( strlen( $where ) > 0 ) ? ' WHERE ' . $where . ' ' : '';
    $list_membres   = array();
    $end_rqt_sql    = '';

    if (!empty($page) && is_numeric($page))
    {
        $premierMessageAafficher = ($page - 1) * $element_par_page;
        $end_rqt_sql = ' LIMIT '.$premierMessageAafficher . ', '.$element_par_page.' ';
    }

    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT s_nb_news, s_nb_contrib, s_nb_coms, u_id, u_pseudo, u_alias, u_avatar, u_localisation, u_bio, g_id, g_titre, g_icone,
        '.decalageh('u_date_register', 'date_register').', '.decalageh('u_last_visit', 'last_visit').', DATE_FORMAT(u_last_visit, "%Y-%m-%dT%H:%i:%s+01:00") AS date_sitemap, DATE_FORMAT(u_date_register, "%Y-%m-%dT%H:%i:%s+01:00") AS date_sitemap_register
        FROM '.Nw::$prefix_table.'members
            LEFT JOIN '.Nw::$prefix_table.'groups ON u_group = g_id
            LEFT JOIN '.Nw::$prefix_table.'members_stats ON s_id_membre = u_id
        '.$where_clause.'GROUP BY u_id ORDER BY '.$ordre.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc())
        $list_membres[] = $donnees;

    return $list_membres;
}

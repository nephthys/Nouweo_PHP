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
*   Affiche les modifications d'une news
*   @param string $where            Clause WHERE
*   @param string $ordre            Clause ORDER BY
*   @param string $page         Page en cours (si vide, aucune page)
*   @param integer $element_par_page    Nbr de modfifs par page (0 = toutes)
*   @return array
*** */
function get_news_logs($where = '', $ordre='l_date DESC', $page = '', $element_par_page=0)
{
    $where_clause = (!empty($where)) ? 'WHERE '.$where.' ' : '';
    $add_champs_sql = '';
    $add_jointure_sql = '';
    $end_rqt_sql = '';
    $list = array();

    if (!empty($page) && is_numeric($page))
    {
        $premierMessageAafficher = ($page - 1) * $element_par_page;
        $end_rqt_sql = ' LIMIT '.$premierMessageAafficher . ', '.$element_par_page.' ';
    }

    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT n_id, n_titre, l_id_news, l_id_membre, l_titre, l_action, l_texte, l_ip, u_id, u_pseudo, u_avatar, u_alias, '.decalageh('l_date', 'date').'
        FROM '.Nw::$prefix_table.'news_logs
            LEFT JOIN '.Nw::$prefix_table.'members ON l_id_membre = u_id
            LEFT JOIN '.Nw::$prefix_table.'groups ON u_group = g_id
            LEFT JOIN '.Nw::$prefix_table.'news ON l_id_news = n_id
        '.$where_clause.'ORDER BY '.$ordre.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc()) {
        $list[] = $donnees;
    }

    return $list;
}

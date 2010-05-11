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
*   Affiche les derniers commentaires (toutes news confondues)
*   @param integer $etat_news           État des commentaires de news à afficher (facultatif)
*   @param string $ordre                Clause ORDER BY
*   @param string $page                 Page en cours (si vide, aucune page)
*   @param integer $element_par_page    Nbr de news par page (0 = toutes les news)
*   @return array
*** */
function get_list_last_cmt($etat_news=0, $ordre='com.c_date DESC', $page = '', $element_par_page=0)
{
    $where_clause = ($etat_news != 0) ? 'WHERE n_etat = '.intval($etat_news).' ' : '';
    $add_champs_sql = '';
    $add_jointure_sql = '';
    $list_cmts = array();

    if (!empty($page) && is_numeric($page))
    {
        $premierMessageAafficher = ($page - 1) * $element_par_page;
        $end_rqt_sql = ' LIMIT '.$premierMessageAafficher . ', '.$element_par_page.' ';
    }

    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT cat.c_nom, cat.c_rewrite, n_id, n_titre, u_alias, u_avatar, u_id, u_pseudo,
        com.c_id, com.c_id_news, com.c_id_membre, com.c_texte, com.c_ip, com.c_plussoie, '.decalageh('com.c_date', 'date').'
        FROM '.Nw::$prefix_table.'news_commentaires com
            LEFT JOIN '.Nw::$prefix_table.'members ON com.c_id_membre = u_id
            LEFT JOIN '.Nw::$prefix_table.'news ON com.c_id_news = n_id
            LEFT JOIN '.Nw::$prefix_table.'categories cat ON n_id_cat = cat.c_id
        '.$where_clause.'ORDER BY '.$ordre.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc()) {
        $list_cmts[] = $donnees;
    }

    return $list_cmts;
}

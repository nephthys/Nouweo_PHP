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
function search_news_bytag($tag, $etat, $page = '', $element_par_page=0)
{
    $add_champs_sql = '';
    $add_jointure_sql = '';
    $list_news = array();
    $end_rqt_sql = '';
    $clause_etat = ($etat != 0) ? 'n_etat = '.intval($etat).' AND ' : '';

    if (!empty($page) && is_numeric($page))
    {
        $premierMessageAafficher = ($page - 1) * $element_par_page;
        $end_rqt_sql = ' LIMIT '.$premierMessageAafficher . ', '.$element_par_page.' ';
    }

    // Si l'utilisateur est connecté
    if (is_logged_in())
    {
        $add_champs_sql = ', f_id_membre, f_type, v_id_membre';
        $add_jointure_sql  = ' LEFT JOIN '.Nw::$prefix_table.'news_flags ON (n_id = f_id_news AND f_id_membre = '.intval(Nw::$dn_mbr['u_id']).')';
        $add_jointure_sql .= ' LEFT JOIN '.Nw::$prefix_table.'news_vote ON (n_id = v_id_news AND v_id_membre = '.intval(Nw::$dn_mbr['u_id']).')';
    }

    // Rqt SQL
    $rqt_list_news = Nw::$DB->query('SELECT t_tag AS first_tag, c_id, c_nom, c_rewrite, n_resume, n_nb_votes, n_nb_versions, n_id, n_id_auteur, n_id_cat, n_titre, n_etat, n_vues, n_private, n_nbr_coms, i_id, i_nom,
        '.decalageh('n_date', 'date_news').', u_id, u_pseudo, u_alias, u_avatar'.$add_champs_sql.'
        FROM '.Nw::$prefix_table.'news
            LEFT JOIN '.Nw::$prefix_table.'members ON n_id_auteur = u_id'.$add_jointure_sql.'
            LEFT JOIN '.Nw::$prefix_table.'categories ON c_id = n_id_cat
            LEFT JOIN '.Nw::$prefix_table.'news_images ON i_id = n_id_image
            LEFT JOIN '.Nw::$prefix_table.'tags ON t_id_news = n_id
        WHERE '.$clause_etat.'t_tag = \''.insertBD(trim(urldecode($tag))).'\' GROUP BY n_id ORDER BY n_date DESC'.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees_news = $rqt_list_news->fetch_assoc()) {
        $list_news[] = $donnees_news;
    }

    return $list_news;
}

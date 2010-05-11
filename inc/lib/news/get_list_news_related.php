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
*   Trouve les tags des IDs de news x et sort des news relatives
*   @param integer|array $ids_news  IDs des news
*   @return array
*** */
function get_list_news_related($ids_news=array(), $limit=3, $etat=3)
{
    $news_related = array();
    $count_nb_related_bynews = array();
    $ids_related_passed = array();
    $add_champs_sql = '';
    $add_jointure_sql = '';

    if(is_array($ids_news))
        $rqt_sql = ' IN ('.implode(', ', $ids_news).')';
    else
        $rqt_sql = ' = '.intval($ids_news);

    // Si l'utilisateur est connecté
    if (is_logged_in())
    {
        $add_champs_sql = ', v_id_membre';
        $add_jointure_sql = ' LEFT JOIN '.Nw::$prefix_table.'news_vote
            ON (n_id = v_id_news AND v_id_membre = '.intval(Nw::$dn_mbr['u_id']).')';
    }

    $rqt_list_news = Nw::$DB->query('SELECT t1.t_id_news AS old_news, t1.t_tag,
        n_id, n_titre, n_nb_votes, n_nbr_coms, i_id, i_nom, c_id, c_nom, c_rewrite,
        '.decalageh('n_date', 'date_news').$add_champs_sql.'
        FROM '.Nw::$prefix_table.'tags t1
            LEFT JOIN '.Nw::$prefix_table.'tags t2 ON (t2.t_tag = t1.t_tag)
            LEFT JOIN '.Nw::$prefix_table.'news ON t2.t_id_news = n_id
            LEFT JOIN '.Nw::$prefix_table.'categories ON n_id_cat = c_id
            LEFT JOIN '.Nw::$prefix_table.'news_images ON i_id = n_id_image'.$add_jointure_sql.'
        WHERE n_etat = '.$etat.' AND t1.t_id_news'.$rqt_sql.' AND t1.t_position = 1
        ORDER BY t1.t_id_news, n_date DESC'
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees_news = $rqt_list_news->fetch_assoc())
    {
        if($donnees_news['old_news'] != $donnees_news['n_id'])
        {
            $count_nb_related_bynews[$donnees_news['old_news']] = (isset($count_nb_related_bynews[$donnees_news['old_news']])) ? $count_nb_related_bynews[$donnees_news['old_news']]+1 : 1;

            if($count_nb_related_bynews[$donnees_news['old_news']] <= $limit && !in_array($donnees_news['n_id'], $ids_related_passed))
            {
                $vars_imp = array(
                    'id'            => $donnees_news['n_id'],
                    'titre'         => $donnees_news['n_titre'],
                    'cat_rewrite'   => $donnees_news['c_rewrite'],
                    'rewrite'       => rewrite($donnees_news['n_titre']),
                    'date'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),

                    'nbr_votes'     => $donnees_news['n_nb_votes'],
                    'nbr_coms'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                    'has_voted'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,

                    'image_id'      => $donnees_news['i_id'],
                    'image_nom'     => $donnees_news['i_nom'],
                );

                if(is_array($ids_news))
                    $news_related[$donnees_news['old_news']][] = $vars_imp;
                else
                    $news_related[] = $vars_imp;

                $ids_related_passed[] = $donnees_news['n_id'];
            }
        }
    }

    return $news_related;
}

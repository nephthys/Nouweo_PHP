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

function get_list_actifs_bycategorie($id_cat=0)
{
    $add_where = '';
    $stats = array();

    if ($id_cat != 0)
        $add_where = ' AND n_id_cat = '.intval($id_cat);

    // Rqt SQL
    $rqt_dn_news = Nw::$DB->query('SELECT n_id_cat, COUNT(n_id_auteur) AS nb_posts,
        u_id, u_pseudo, u_alias, u_avatar FROM '.Nw::$prefix_table.'news
        LEFT JOIN '.Nw::$prefix_table.'members ON n_id_auteur = u_id
    WHERE n_etat = 3'.$add_where.'
    GROUP BY n_id_cat, n_id_auteur ORDER BY n_id_cat ASC, nb_posts DESC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt_dn_news->fetch_assoc())
    {
        if (!isset($stats[$donnees['n_id_cat']]))
            $stats[$donnees['n_id_cat']] = array();

        if (count($stats[$donnees['n_id_cat']]) < 3)
            $stats[$donnees['n_id_cat']][] = $donnees;
    }

    return $stats;
}

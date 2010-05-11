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

/***
*   Affiche un nuage de tags avec les tags des news publi�es
*   @param $limit       Nombre maximum de tags (si null pas de limite)
*   @author Cam
*   @return array
***/
function nuage_tags($limit = 30, $id_cat = 0)
{
    $list_tags = array();
    $count_tags = array();
    $limit_sql = ($limit != 0) ? ' LIMIT '.intval($limit) : '';
    $where_cat = ($id_cat != 0) ? ' AND n_id_cat = '.intval($id_cat) : '';


    $rqt_tags = Nw::$DB->query('SELECT c_id, c_couleur, c_nom, t_id_news, t_tag, COUNT(t_tag) AS nbr_tags, n_id_cat FROM '.Nw::$prefix_table.'tags
        LEFT JOIN '.Nw::$prefix_table.'news ON t_id_news = n_id
        LEFT JOIN '.Nw::$prefix_table.'categories ON c_id = n_id_cat
    WHERE n_etat = 3'.$where_cat.'
    GROUP BY t_tag ORDER BY t_tag ASC '.$limit_sql) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt_tags->fetch_assoc()) {
        $list_tags[$donnees['t_tag']] = $donnees;
        $count_tags[$donnees['t_tag']] = $donnees['nbr_tags'];
    }

    $max_size = 200;
    $min_size = 100;
    $max_qty = 0;
    $min_qty = 0;

    if (count($count_tags) > 0)
    {
        $max_qty = max(array_values($count_tags));
        $min_qty = min(array_values($count_tags));
    }

    $spread = $max_qty - $min_qty;

    if (0 == $spread)
        $spread = 1;

    $step = ($max_size - $min_size)/($spread);

    foreach($list_tags AS $tags)
        $list_tags[$tags['t_tag']]['size'] = floor($min_size + (($tags['nbr_tags'] - $min_qty) * $step));

    return $list_tags;
}

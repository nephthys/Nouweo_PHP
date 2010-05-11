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
*   Fonction qui récupère tous les flags des news (ou d'une news en particulier)
*   @param integer $etat_news   Trouve tous les tags des news en ligne/en rédaction ou en attente
*   @param integer $id_news Trouve tous les tags d'une news
*   @return array
*** */
function get_list_tags_news($etat_news=3, $id_news=0)
{
    global $list_tags_metas;

    $list_tags = array();
    $list_params = array();

    if($etat_news != 0)
        $list_params[] = 'n_etat = '.intval($etat_news);

    if($id_news != 0)
        $list_params[] = 't_id_news = '.intval($id_news);

    $sql_params = (count($list_params) > 0) ? ' WHERE '.implode(' AND ', $list_params) : '';

    $query = Nw::$DB->query('SELECT t_id_news, t_tag FROM '.Nw::$prefix_table.'tags
        LEFT JOIN '.Nw::$prefix_table.'news ON t_id_news = n_id
        '.$sql_params.'
        ORDER BY t_id_news DESC, t_position ASC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while($donnees_tags = $query->fetch_assoc())
    {
        $donnees_tags['rewrite'] = urlencode($donnees_tags['t_tag']);
        $list_tags[] = $donnees_tags;
        $list_tags_metas[] = $donnees_tags['t_tag'];
    }

    return $list_tags;
}

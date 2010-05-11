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

function get_tags_search($tag, $masque=0, $etat=3, $hide_var=0)
{
    $list_tags = array();
    $clause_etat = ($etat != 0) ? ' AND n_etat = '.intval($etat) : '';
    $hide_var_sql = ($hide_var != 0) ? ' AND t_tag <> \''.insertBD(trim(urldecode($tag))).'\'' : '';

    $type_masque = '\'%'.insertBD(trim($tag)).'%\''.$clause_etat;

    if ($masque != 0)
        $type_masque = '\''.insertBD(trim($tag)).'%\''.$clause_etat;

    $query = Nw::$DB->query('SELECT t_tag, COUNT(t_tag) AS nb_news FROM '.Nw::$prefix_table.'tags
    LEFT JOIN '.Nw::$prefix_table.'news ON t_id_news = n_id
    WHERE t_tag LIKE '.$type_masque.$hide_var_sql.'
    GROUP BY t_tag ORDER BY nb_news DESC, t_tag ASC
    LIMIT 10') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $query->fetch_assoc()) {
        $donnees['rewrite'] = urlencode($donnees['t_tag']);
        $list_tags[] = $donnees;

    }

    return $list_tags;
}

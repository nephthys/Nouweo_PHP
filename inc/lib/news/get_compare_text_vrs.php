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

function get_compare_text_vrs($id, $id_compare = 0, $id_compare2 = 0)
{
    if($id_compare && $id_compare2)
    {
        $where_clause = ' WHERE v_id_news = '.intval($id).' AND v_id IN ('.intval($id_compare).', '.intval($id_compare2).')
            ORDER BY v_id DESC';
    }
    else
    {
        $where_clause = ' WHERE v_id_news = '.intval($id).' 
            ORDER BY v_id DESC
            LIMIT 2';
    }

    $textes_a_comparer = array();

    $query = Nw::$DB->query( 'SELECT v_id, v_texte
        FROM ' . Nw::$prefix_table . 'news_versions'.$where_clause ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while($donnees = $query->fetch_assoc())
        $textes_a_comparer[] = array($donnees['v_id'], $donnees['v_texte']);

    return $textes_a_comparer;
}

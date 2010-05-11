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

function get_list_alerts_news($id_news = null, $solved = null)
{
    $where = array();
    if(!is_null($id_news))
        $where[] = 'a_id_news = '.intval($id_news);
    if(!is_null($solved))
        $where[] = 'a_solved = '.(int)$solved;

    $query = Nw::$DB->query("SELECT c_id, c_nom, c_rewrite, a_id, a_solved, a_texte, a_ip, a_motif, 
        ".decalageh('a_date', 'date').", a_auteur, a_admin, u1.u_pseudo AS pseudo_auteur,
        u2.u_pseudo AS pseudo_admin, a_id_news, n_titre, u1.u_alias
        FROM ".Nw::$prefix_table."news_alerts 
        LEFT JOIN ".Nw::$prefix_table."members u1 ON a_auteur = u1.u_id
        LEFT JOIN ".Nw::$prefix_table."members u2 ON a_admin = u2.u_id 
        LEFT JOIN ".Nw::$prefix_table."news ON a_id_news = n_id 
        LEFT JOIN ".Nw::$prefix_table."categories ON n_id_cat = c_id".
        (!empty($where) ? ' WHERE '.implode(' AND ', $where) : '')."
        ORDER BY a_solved")
    OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    $dn = array();
    while($row = $query->fetch_assoc())
        $dn[] = $row;
    return $dn;
}

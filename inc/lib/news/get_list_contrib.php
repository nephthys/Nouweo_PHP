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

function get_list_contrib($id, $id_auteur, $where='')
{
    $add_where = (!empty($where)) ? ' AND ' . $where : '';
    
    $query = Nw::$DB->query('SELECT u_avatar, u_alias, u_id, u_group, u_pseudo,
        (u_last_visit >= DATE_SUB(NOW(), INTERVAL '.Nw::$nbr_minutes_connected.' MINUTE)) AS connected, COUNT(*) AS nb_version
        FROM '.Nw::$prefix_table.'news_versions
            LEFT JOIN '.Nw::$prefix_table.'members ON u_id=v_id_membre
        WHERE v_id_membre <> '.$id_auteur.' AND v_id_news='.intval($id).$add_where.'
        GROUP BY v_id_membre ORDER BY nb_version DESC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    $dn_contrib = array();
    while($donnees_contrib = $query->fetch_assoc())
        $dn_contrib[] = $donnees_contrib;

    $query->free();

    return $dn_contrib;
}

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

function get_list_live_parts($id_live)
{
    $list_parts = array();

    $rqt = Nw::$DB->query('SELECT u_id, u_pseudo, u_alias, u_avatar 
    FROM '.Nw::$prefix_table.'w_live_parts
    LEFT JOIN '.Nw::$prefix_table.'members ON u_id = part_id_membre
    WHERE part_id_live = '.intval($id_live).'
    ORDER BY u_pseudo ASC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc())
        $list_parts[] = $donnees;

    return $list_parts;
}

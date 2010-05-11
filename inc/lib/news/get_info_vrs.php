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

function get_info_vrs($id_vrs)
{
    $query = Nw::$DB->query('SELECT v_id, v_ip, v_texte, v_raison, v_number, 
        '.decalageh('v_date', 'date').', v_ip, u_id, u_pseudo, u_alias
        FROM '.Nw::$prefix_table.'news_versions
            LEFT JOIN '.Nw::$prefix_table.'members ON u_id=v_id_membre
        WHERE v_id='.intval($id_vrs)) OR Nw::$DB->trigger(__LINE__, __FILE__);

    $donnees = $query->fetch_assoc();

    return $donnees;
}

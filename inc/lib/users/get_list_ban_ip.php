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

function get_list_ban_ip($finis = null, $ip = null)
{
    $where = array();
    if(!is_null($finis))
        $where[] = 'ban_is_end = '.(int)$finis;
    if(!is_null($ip))
        $where[] = 'ban_ip = '.ip2long($ip);
    
    $query = Nw::$DB->query("SELECT ban_id, ban_ip, ban_motif, ban_motif_admin,
        ".decalageh('ban_date', 'date').", ".decalageh('ban_date_end', 'date_end').",
        ban_id_modo, u_pseudo, u_alias, ban_is_end
        FROM ".Nw::$prefix_table."ban_ip
        LEFT JOIN ".Nw::$prefix_table."members ON ban_id_modo = u_id
        ".(!empty($where) ? 'WHERE '.implode(' AND ', $where) : '')."
        ORDER BY ban_is_end, ban_date DESC");
    return $query->fetch_all();
}

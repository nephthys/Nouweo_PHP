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

function get_info_cat($id, $type='id')
{
    if ($type == 'id')
        $where_type = 'c_id='.intval($id);
    else
        $where_type = 'c_rewrite=\''.insertBD(trim($id)).'\'';
        
    $query = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, c_nbr_news, c_desc
        FROM '.Nw::$prefix_table.'categories
        WHERE '.$where_type) OR Nw::$DB->trigger(__LINE__, __FILE__);
    return $query->fetch_assoc();
}

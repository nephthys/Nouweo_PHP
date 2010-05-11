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

function mbr_exists($res, $by = null)
{
    if(is_null($by))
    {
        if(is_numeric($res))
            $where_clause = 'u_id = '.intval($res);
        else
            $where_clause = 'u_alias = \''.insertBD(trim($res)).'\'';
    }
    elseif ($by == 'alias')
        $where_clause = 'u_alias = \''.insertBD(trim($res)).'\'';
    else
        $where_clause = 'u_id = '.intval($res);

    $query = Nw::$DB->query( 'SELECT COUNT(*) as count FROM '.Nw::$prefix_table.'members WHERE '.$where_clause) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();
    $query->free();

    return $dn['count'] > 0;
}

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

function get_info_mbr($res, $by = null)
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
    elseif ($by == 'id')
        $where_clause = 'u_id = '.intval($res);
    elseif ($by == 'mail')
        $where_clause = 'u_email = \''.insertBD($res).'\'';
    elseif ($by == 'identifier')
        $where_clause = 'u_identifier = \''.insertBD($res).'\'';
    elseif ($by == 'pseudo')
        $where_clause = 'u_pseudo = \''.insertBD($res).'\'';

    $query = Nw::$DB->query( 'SELECT u_id, u_alias, u_avatar, u_pseudo, u_group,
    u_localisation, u_ident_unique, u_bio, '.decalageh('u_date_register', 'date_register').',
    '.decalageh('u_last_visit', 'last_visit').', u_password, u_code_act, u_active,
    u_email, u_decalage, DATE_FORMAT(u_date_naissance, "%d/%m/%Y") AS date_naissance, 
    g_titre, g_icone
    FROM '.Nw::$prefix_table.'members
        LEFT JOIN '.Nw::$prefix_table.'groups ON g_id = u_group
    WHERE '.$where_clause) OR Nw::$DB->trigger(__LINE__, __FILE__);

    return $query->fetch_assoc();
}

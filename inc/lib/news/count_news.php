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

function count_news($where)
{
    $add_jointure_sql = '';

    // Si l'utilisateur est connecté
    if (is_logged_in())
    {
        $add_jointure_sql = ' LEFT JOIN '.Nw::$prefix_table.'news_flags
            ON (n_id = f_id_news AND f_id_membre = '.intval(Nw::$dn_mbr['u_id']).')';
    }

    $query = Nw::$DB->query('SELECT COUNT(*) as count
    FROM '.Nw::$prefix_table.'news'.$add_jointure_sql.'
    WHERE '.$where) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();

    return $dn['count'];
}

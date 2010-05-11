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

function count_flags_news($id_membre)
{
    $count_flags = array(1 => 0, 2 => 0, 3 => 0);
    $query = Nw::$DB->query('SELECT COUNT(f_type) AS count_type, f_type 
        FROM '.Nw::$prefix_table.'news_flags
        WHERE f_id_membre='.intval($id_membre).'
        GROUP BY f_type
        ORDER BY f_type ASC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while($donnees = $query->fetch_assoc())
        $count_flags[$donnees['f_type']] = $donnees['count_type'];

    return $count_flags;
}

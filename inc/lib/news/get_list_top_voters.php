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

function get_list_top_voters($limit=5)
{
    $tops = array();
    $limit_sql = ($limit != 0) ? ' LIMIT '.intval($limit) : '';

    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT u_id, u_pseudo, u_alias, u_avatar, s_id_membre, s_nb_votes
        FROM '.Nw::$prefix_table.'members_stats
            LEFT JOIN '.Nw::$prefix_table.'members ON s_id_membre = u_id
        ORDER BY s_nb_votes DESC'.$limit_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc()) {
        $tops[] = $donnees;
    }

    return $tops;
}

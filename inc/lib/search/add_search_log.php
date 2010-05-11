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

function add_search_log($keyword, $nbr_results)
{
    $id_membre_sql = (is_logged_in()) ? intval(Nw::$dn_mbr['u_id']) : 0;
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'logs_recherche (l_id_membre, l_date, l_mot_cle, l_ip, l_nbr_results) VALUES('.$id_membre_sql.', NOW(), \''.insertBD(trim($keyword)).'\', \''.get_ip().'\', '.intval($nbr_results).')') OR Nw::$DB->trigger(__LINE__, __FILE__);
}

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

function get_list_cat()
{
    $list = array();
    
    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, n_titre, DATE_FORMAT(n_last_mod, "%Y-%m-%dT%H:%i:%s+01:00") AS date_sitemap FROM '.Nw::$prefix_table.'categories
        LEFT JOIN '.Nw::$prefix_table.'news ON (n_id_cat = c_id AND n_etat = 3)
        GROUP BY c_id ORDER BY n_last_mod DESC, c_id ASC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc()) 
        $list[] = $donnees;

    return $list;
}

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

function give_cat_images()
{
    $list = array();
    
    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT n_id, i_id, i_nom FROM '.Nw::$prefix_table.'news LEFT JOIN '.Nw::$prefix_table.'news_images ON n_id_image = i_id WHERE n_etat = 3 AND n_id_image <> 0 GROUP BY n_id_cat ORDER BY n_date DESC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc()) 
        $list[$donnees['n_id']] = $donnees;

    return $list;
}

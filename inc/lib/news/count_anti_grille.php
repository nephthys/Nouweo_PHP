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

/**
*   Anti-grillé : permet de savoir si quelqu'un a édité la news entre temps
*   et de fusionner les 2 versions
**/
function count_anti_grille($id_news, $id_version)
{
    $donnees = array();
    $grille = false;
    
    $rqt_count_grille = Nw::$DB->query('SELECT n_last_version 
        FROM '.Nw::$prefix_table.'news
        WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $donnees_anti_grille = $rqt_count_grille->fetch_assoc();

    // Si quelqu'un a déjà modifié la news entre temps
    if ($donnees_anti_grille['n_last_version'] > $id_version) {
        $grille = true;
        $donnees = $donnees_anti_grille;
    }
    
    return array('count' => $grille, 'dn' => $donnees);
}

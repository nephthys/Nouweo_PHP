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
function get_info_abonnement($where)
{
    $donnees = array();
    
    $query = Nw::$DB->query('SELECT a_id, a_id_membre, a_email, a_ip, a_token, '.decalageh('a_date', 'date').' FROM '.Nw::$prefix_table.'abonnes WHERE '.$where) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();

    return $dn;
}

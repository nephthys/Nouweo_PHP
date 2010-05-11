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

function get_list_abonnements()
{
    $donnees = array();
    
    $query = Nw::$DB->query('SELECT a_id, a_id_membre, a_email, a_ip, a_token, '.decalageh('a_date', 'date').' FROM '.Nw::$prefix_table.'abonnes ORDER BY a_id_membre ASC') OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    while ($dn = $query->fetch_assoc())
        $donnees[] = $dn;
        
    return $donnees;
}

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

function get_list_simple_ban_ip()
{
    $list = array();

    $rqt = Nw::$DB->query('SELECT b_ip, b_motif
        FROM '.Nw::$prefix_table.'ban_ip
        ORDER BY b_date DESC');
    
    while ($donnees = $rqt->fetch_assoc())
        $list[] = $donnees;

    return $list;
}

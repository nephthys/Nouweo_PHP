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

function get_last_registered($limit=0)
{
    $list_membres   = array();
    $end_rqt_sql    = ($limit != 0) ? ' LIMIT '.$limit : '';

    // Rqt SQL
    $rqt = Nw::$DB->query('SELECT u_id, u_pseudo, u_alias, u_avatar, u_localisation, u_bio,
        '.decalageh('u_date_register', 'date_register').', '.decalageh('u_last_visit', 'last_visit').'
        FROM '.Nw::$prefix_table.'members
        WHERE u_active = 1 GROUP BY u_id ORDER BY u_date_register DESC'.$end_rqt_sql
    ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $rqt->fetch_assoc())
        $list_membres[] = $donnees;

    return $list_membres;
}

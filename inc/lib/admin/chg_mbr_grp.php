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

/***
*   Change un membre de groupe.
*   @param $id_mbr          ID de l'utilisateur
*   @param $id_grp          ID du groupe
*   @author vincent1870
*   @return void
***/
function chg_mbr_grp($id_mbr, $id_grp)
{
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members
    SET u_group = '.intval($id_grp).'
    WHERE u_id = '.intval($id_mbr)) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

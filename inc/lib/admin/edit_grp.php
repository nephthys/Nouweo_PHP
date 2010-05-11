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
*   Edition du groupe
*   @param $id      ID du groupe à éditer
*   @author Cam
*   @return void
***/
function edit_grp($id)
{
    $couleur = (isset($_POST['couleur'])) ? 1 : 0;

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'groups
    SET g_nom = \''.insertBD(trim($_POST['nom'])).'\', g_titre = \''.insertBD(trim($_POST['titre'])).'\',
    g_icone = \''.insertBD(trim($_POST['icone'])).'\', g_couleur = '.$couleur.'
    WHERE g_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

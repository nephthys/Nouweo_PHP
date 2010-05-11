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

function delete_avatar($id_membre)
{
    $query = Nw::$DB->query('SELECT u_avatar FROM '.Nw::$prefix_table.'members WHERE u_id = '.intval($id_membre)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();
    
    @unlink(PATH_ROOT.Nw::$assets['dir_users'].'pictos/100_'.$dn['u_avatar'].'.png');
    @unlink(PATH_ROOT.Nw::$assets['dir_users'].'pictos/45_'.$dn['u_avatar'].'.png');
    @unlink(PATH_ROOT.Nw::$assets['dir_users'].'pictos/16_'.$dn['u_avatar'].'.png');
            
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members SET u_avatar = NULL WHERE u_id = '.intval($id_membre)) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

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

function edit_profile_mbr()
{
    inc_lib('bbcode/parse');
    $contenu_bio = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($_POST['biographie']))));

    $explode_date_naissance = explode('/', $_POST['date_naissance']);
    $new_dn = $explode_date_naissance[2].'-'.$explode_date_naissance[1].'-'.$explode_date_naissance[0];
    
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members SET 
        u_decalage = \''.insertBD($_POST['decalage_horaire']).'\', 
        u_bio = \''.$contenu_bio.'\', 
        u_date_naissance = \''.insertBD($new_dn).'\',
        u_localisation = \''.insertBD($_POST['localisation']).'\' 
    WHERE u_id = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

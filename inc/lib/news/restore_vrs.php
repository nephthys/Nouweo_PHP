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

function restore_vrs($id_news, $id_version)
{
    inc_lib('news/get_info_vrs');
    inc_lib('bbcode/clearer');
    $donnees_vrs = get_info_vrs($id_version);
    $contenu_extrait = Nw::$DB->real_escape_string(CoupeChar(clearer($donnees_vrs['v_texte']), '...', Nw::$pref['long_intro_news']));

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news 
        SET n_resume = \''.$contenu_extrait.'\', n_last_version = '.intval($id_version).'
        WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

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

function add_msg_list_live($id_live, $message)
{
    inc_lib('bbcode/parse');
    $contenu = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($message))));

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'w_live_posts (post_id_membre,
        post_id_live, post_date, post_contenu, post_ip) VALUES('.intval(Nw::$dn_mbr['u_id']).', 
        '.intval($id_live).', NOW(), \''.$contenu.'\', \''.get_ip().'\')') OR Nw::$DB->trigger(__LINE__, __FILE__);

    $id_new_post = Nw::$DB->insert_id;

    return $id_new_post;
}

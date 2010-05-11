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

function add_article($id_admin, $resource_name, $link, $num, $lang, $contenu, $date_pub)
{
    inc_lib('bbcode/parse');

    $contenu = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($contenu))));
    $resource_name = Nw::$DB->real_escape_string(htmlspecialchars(trim($resource_name)));
    $link = Nw::$DB->real_escape_string(htmlspecialchars(trim($link)));
    $lang = Nw::$DB->real_escape_string(htmlspecialchars(trim($lang)));
    $num = !empty($num) ? intval($num) : 'NULL';

    Nw::$DB->query("INSERT INTO ".Nw::$prefix_table."press(p_id_admin, p_ressource_name,
    p_link, p_num, p_lang, p_description, p_date)
    VALUES(".intval($id_admin).", '".$resource_name."',  '".$link."', ".$num.",
    '".$lang."', '".$contenu."', ".(!empty($date_pub) ? "STR_TO_DATE('".$date_pub."', '%d/%m/%Y')" : 'DATE(NOW())').")")
    OR Nw::$DB->trigger(__LINE__, __FILE__);
}

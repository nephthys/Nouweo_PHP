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

function add_alert_news($id_user, $id_news, $texte, $motif)
{
    inc_lib('bbcode/parse');
    $texte = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($texte))));
    
    Nw::$DB->query("INSERT INTO ".Nw::$prefix_table."news_alerts(a_id_news, a_auteur,
        a_ip, a_date, a_texte, a_motif)
        VALUES(".intval($id_news).", ".intval($id_user).", ".get_ip().", NOW(), 
        '".$texte."', ".intval($motif).")")
    OR Nw::$DB->trigger(__LINE__, __FILE__);
    return Nw::$DB->insert_id;
}

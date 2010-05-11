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

function manage_fav($id_news)
{
    $query = Nw::$DB->query( 'SELECT COUNT(*) AS count
        FROM ' . Nw::$prefix_table . 'news_flags
        WHERE f_id_news = '.intval($id_news).' AND f_id_membre = '.intval(Nw::$dn_mbr['u_id']).'
        AND f_type = 1') OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();
    $query->free();

    // On met en favoris
    if($dn['count'] == 0)
    {
        Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_flags (f_id_news, 
            f_id_membre, f_type) VALUES('.intval($id_news).',
            '.intval(Nw::$dn_mbr['u_id']).', 1)') OR Nw::$DB->trigger(__LINE__, __FILE__);
        return 1;
    }
    else
    {
        Nw::$DB->query('DELETE FROM '.Nw::$prefix_table.'news_flags 
            WHERE f_id_news = '.intval($id_news).' AND f_id_membre = '.intval(Nw::$dn_mbr['u_id']).'
            AND f_type = 1') OR Nw::$DB->trigger(__LINE__, __FILE__);
        return 2;
    }
}

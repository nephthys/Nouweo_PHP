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

function add_tag_news($id_news, $tag, $position=0)
{
    if($position == 0)
    {
        $rqt_dn_tags = Nw::$DB->query('SELECT t_position
            FROM '.Nw::$prefix_table.'tags
            WHERE t_id_news = '.intval($id_news).'
            ORDER BY t_position DESC LIMIT 1') OR Nw::$DB->trigger(__LINE__, __FILE__);
        $donnees_last_tag = $rqt_dn_tags->fetch_assoc();

        if(isset($donnees_last_tag['t_position']))
            $position = $donnees_last_tag['t_position']+1;
    }

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'tags (t_id_news, t_tag, t_position)
        VALUES('.intval($id_news).', \''.insertBD(trim($tag)).'\', '.intval($position).')') OR Nw::$DB->trigger(__LINE__, __FILE__);
}

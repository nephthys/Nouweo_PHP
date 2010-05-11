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

function has_voted_news($id_news, $etat)
{
    $query = Nw::$DB->query( 'SELECT COUNT(*) AS count
        FROM ' . Nw::$prefix_table . 'news_vote
        WHERE v_id_news = '.intval($id_news).' AND v_id_membre = '.intval(Nw::$dn_mbr['u_id']).'
        AND v_etat = '.intval($etat)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();
    $query->free();

    return $dn['count'] > 0;
}

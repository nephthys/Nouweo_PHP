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

function count_alerts_news($id_news = null, $solved = null)
{
    $where = array();
    if(!is_null($id_news))
        $where[] = 'a_id_news = '.intval($id_news);
    if(!is_null($solved))
        $where[] = 'a_solved = '.(int)$solved;

    $query = Nw::$DB->query("SELECT COUNT(*) AS nb
        FROM ".Nw::$prefix_table."news_alerts ".
        (!empty($where) ? ' WHERE '.implode(' AND ', $where) : ''));
    $dn = $query->fetch_assoc();
    return $dn['nb'];
}

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

function get_list_vrs($id_news=0, $limit=0)
{
    $add_sql = '';
    $add_sql2 = '';

    if($id_news != 0)
        $add_sql = ' WHERE v_id_news='.intval($id_news);

    if($limit != 0)
        $add_sql2 = ' LIMIT '.intval($limit);

    $query = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, n_id, n_titre, v_id, v_id_news, v_ip, v_texte, v_raison, v_number, v_mineure,
        '.decalageh('v_date', 'date').', v_ip, u_id, u_alias, u_avatar, u_pseudo
        FROM '.Nw::$prefix_table.'news_versions
            LEFT JOIN '.Nw::$prefix_table.'news ON v_id_news = n_id
            LEFT JOIN '.Nw::$prefix_table.'categories ON n_id_cat = c_id
            LEFT JOIN '.Nw::$prefix_table.'members ON u_id=v_id_membre'.$add_sql.'
        ORDER BY v_date DESC, v_id DESC'.$add_sql2) OR Nw::$DB->trigger(__LINE__, __FILE__);

    $dn_versions = array();
    while($donnees = $query->fetch_assoc())
        $dn_versions[] = $donnees;

    $query->free();

    return $dn_versions;
}

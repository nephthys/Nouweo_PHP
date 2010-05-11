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

/*  ***
*   Voir get_list_tags_news()
*** */
function get_list_flags_news($etat_news=1, $id_news=0)
{
    $list_flags = array();
    $other_param = '';

    if($id_news != 0)
        $other_param = ' AND f_id_news = '.intval($id_news).' GROUP BY f_type';

    $query = Nw::$DB->query('SELECT f_id_news, f_type FROM '.Nw::$prefix_table.'news_flags
        LEFT JOIN '.Nw::$prefix_table.'news ON f_id_news = n_id
        WHERE n_etat = '.intval($etat_news).' AND f_id_membre = '.intval(Nw::$dn_mbr['u_id']).$other_param.'
        ORDER BY f_id_news DESC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while($donnees_flags = $query->fetch_assoc())
    {
        $donnees_flags['txt_lang'] = Nw::$lang['news']['news_flag_type'.$donnees_flags['f_type']];
        $list_flags[] = $donnees_flags;
    }

    return $list_flags;
}

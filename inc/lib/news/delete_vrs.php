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

function delete_vrs($id_news, $id_version, $last_version)
{
    inc_lib('bbcode/clearer');
    $add_rqt_sql = '';

    $rqt = Nw::$DB->query('SELECT v_id_membre, v_number
        FROM '.Nw::$prefix_table.'news_versions
        WHERE v_id_news = '.intval($id_news).' AND v_id = '.intval($id_version)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $result = $rqt->fetch_assoc();

    // Si on veut supprimer la dernière version de la news
    if($id_version == $last_version)
    {
        $query = Nw::$DB->query('SELECT v_id, v_texte
            FROM '.Nw::$prefix_table.'news_versions
            WHERE v_id_news = '.intval($id_news).' AND v_id <> '.intval($id_version).'
            ORDER BY v_date DESC
            LIMIT 1') OR Nw::$DB->trigger(__LINE__, __FILE__);
        $donnees_ex_vrs = $query->fetch_assoc();

        $contenu_extrait = Nw::$DB->real_escape_string(CoupeChar(clearer($donnees_ex_vrs['v_texte']), '...', Nw::$pref['long_intro_news']));
        $add_rqt_sql = ', n_resume = \''.$contenu_extrait.'\', n_last_version = '.intval($donnees_ex_vrs['v_id']);
    }

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats 
        SET s_nb_contrib = s_nb_contrib - 1
        WHERE s_id_membre = '.intval($result['v_id_membre'])) OR Nw::$DB->trigger(__LINE__, __FILE__);
        
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news_versions SET v_number = v_number - 1
        WHERE v_id_news = '.intval($id_news).' AND v_number > '.intval($result['v_number'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

    Nw::$DB->query('DELETE FROM '.Nw::$prefix_table.'news_versions
        WHERE v_id_news = '.intval($id_news).' AND v_id = '.intval($id_version)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news 
        SET n_nb_versions = n_nb_versions - 1'.$add_rqt_sql.'
        WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

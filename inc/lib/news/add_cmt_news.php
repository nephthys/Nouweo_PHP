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

function add_cmt_news($id_news)
{
    $contenu_cmt = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($_POST['contenu']))));

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_commentaires (c_id_news,
        c_id_membre, c_texte, c_date, c_ip, c_plussoie) VALUES('.intval($id_news).',
    '.intval(Nw::$dn_mbr['u_id']).', \''.$contenu_cmt.'\', NOW(), \''.get_ip().'\', 0)') OR Nw::$DB->trigger(__LINE__, __FILE__);

    $id_new_comment = Nw::$DB->insert_id;

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news 
        SET n_nbr_coms = n_nbr_coms + 1, n_last_com = '.intval($id_new_comment).'
        WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats 
        SET s_nb_coms = s_nb_coms + 1
        WHERE s_id_membre = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

    return $id_new_comment;
}

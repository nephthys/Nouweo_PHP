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

function archive_news($id_news)
{
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news SET n_etat = 0 WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    // Log
    $texte_log = sprintf(Nw::$lang['news']['log_votes_archived'], Nw::$pref['nb_votes_valid_news']);
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_action, l_texte, l_date, l_ip) VALUES('.intval($id_news).', '.intval(Nw::$dn_mbr['u_id']).', 10, \''.$texte_log.'\', NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

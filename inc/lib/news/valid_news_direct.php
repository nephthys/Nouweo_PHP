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

function valid_news_direct($id)
{
    inc_lib('admin/post_twitt_news');
    $return_alias = post_twitt_news($id);
    
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news 
    SET n_date = NOW(), n_last_mod = NOW(), n_private = 0, n_etat = 3, n_vues = 0, n_miniurl = \''.insertBD($return_alias).'\'
    WHERE n_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    inc_lib('news/delete_all_cmt');
    delete_all_cmt($id);
    
    generate_news_sitemap();
    generate_categories_sitemap();
    
    $rqt_dn_news = Nw::$DB->query('SELECT n_id, n_titre FROM '.Nw::$prefix_table.'news WHERE n_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn_news = $rqt_dn_news->fetch_assoc();
    
    // Log
    $texte_log = sprintf(Nw::$lang['news']['log_publication_votes'], Nw::$pref['nb_votes_valid_news']);
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_titre, l_action, l_texte, l_date, l_ip) VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', \''.insertBD($dn_news['n_titre']).'\', 13, \''.$texte_log.'\', NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

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

function add_vote_news($id_news, $type=true)
{
    $query = Nw::$DB->query( 'SELECT n_etat, n_nb_votes, n_nb_votes_neg, n_id_auteur FROM '.Nw::$prefix_table.'news WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn_news = $query->fetch_assoc();
    
    if ($type)
    {
        $update_champs_sql = 'n_nb_votes';
        $increment_field = $dn_news['n_nb_votes'];
    }
    else
    {
        $update_champs_sql = 'n_nb_votes_neg';
        $increment_field = $dn_news['n_nb_votes_neg'];
    }
        
    // On met en favoris
    inc_lib('news/has_voted_news');
    if(has_voted_news($id_news, $dn_news['n_etat']) == 0 && $dn_news['n_id_auteur'] != Nw::$dn_mbr['u_id'])
    {
        $type_sql = ($type) ? 1 : 0;
        
        Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_vote (v_id_membre, v_ip, v_date, v_id_news, v_etat, v_type) VALUES('.intval(Nw::$dn_mbr['u_id']).', \''.get_ip().'\', NOW(), '.intval($id_news).', '.intval($dn_news['n_etat']).', '.$type_sql.')') OR Nw::$DB->trigger(__LINE__, __FILE__);
        
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news SET '.$update_champs_sql.' = '.$update_champs_sql.' + 1 WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats SET s_nb_votes = s_nb_votes + 1 WHERE s_id_membre = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

        return array(true, $increment_field+1);
    }
    else
    {
        return array(false, $increment_field);
    }
}

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

function delete_cmt_news($id_news, $id_comment)
{
    // Le commentaire est juste remplacé par un message
    if ((Nw::$droits['can_del_all_comments'] && !empty($_POST['raison'])) || !Nw::$droits['can_del_all_comments'])
    {
        $message_masque = $_POST['raison'];

        if(!Nw::$droits['can_del_all_comments'])
            $message_masque = Nw::$lang['news']['cmt_deletedby_himself'];

        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news_commentaires SET c_masque = 1, c_masque_raison = \''.insertBD(trim($message_masque)).'\', c_masque_modo = '.intval(Nw::$dn_mbr['u_id']).' WHERE c_id_news = '.intval($id_news).' AND c_id = '.intval($id_comment)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    }

    // Suppression définitive du commentaire
    if (empty($_POST['raison']) && isset($_POST['rlly_delete']))
    {
        $add_sql = '';

        $query = Nw::$DB->query( 'SELECT c_id FROM '.Nw::$prefix_table.'news_commentaires WHERE c_id_news = '.intval($id_news).' AND c_id <> '.intval($id_comment).' ORDER BY c_date DESC LIMIT 1') OR Nw::$DB->trigger(__LINE__, __FILE__);
        $dn = $query->fetch_assoc();

        $query_stats = Nw::$DB->query( 'SELECT c_id_membre FROM '.Nw::$prefix_table.'news_commentaires WHERE c_id_news = '.intval($id_news).' AND c_id = '.intval($id_comment)) OR Nw::$DB->trigger(__LINE__, __FILE__);
        $dn_stats = $query->fetch_assoc();

        if (!empty($dn['c_id']))
            $add_sql = ', n_last_com = '.intval($dn['c_id']);

        Nw::$DB->query('DELETE FROM '.Nw::$prefix_table.'news_commentaires WHERE c_id_news = '.intval($id_news).' AND c_id = '.intval($id_comment)) OR Nw::$DB->trigger(__LINE__, __FILE__);
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news SET n_nbr_coms = n_nbr_coms - 1'.$add_sql.' WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats SET s_nb_coms = s_nb_coms - 1 WHERE s_id_membre = '.intval($dn_stats['c_id_membre'])) OR Nw::$DB->trigger(__LINE__, __FILE__);
    }
}

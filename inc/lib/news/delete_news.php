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

function delete_news($id)
{
    //On récupère l'id de la catégorie où se situe la news
    $query = Nw::$DB->query('SELECT u_id, u_pseudo, u_alias, u_email, n_titre, n_id_cat as id_cat, n_id_auteur
        FROM '.Nw::$prefix_table.'news
            LEFT JOIN '.Nw::$prefix_table.'members ON n_id_auteur = u_id
        WHERE n_id='.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn_news = $query->fetch_assoc();
    
    $query->free();
    $id_cat = $dn_news['id_cat'];
    $titre_news = $dn_news['n_titre'];
    
    // Suppression des contributions
    $not_delete_first_vrs = false;
    $query = Nw::$DB->query('SELECT v_id, v_id_membre
        FROM '.Nw::$prefix_table.'news_versions
        WHERE v_id_news='.intval($id).'
        ORDER BY v_id ASC') OR Nw::$DB->trigger(__LINE__, __FILE__);

    while ($donnees = $query->fetch_assoc()) 
    {
        if ($not_delete_first_vrs)
            Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats 
                SET s_nb_contrib = s_nb_contrib - 1
                WHERE s_id_membre = '.intval($donnees['v_id_membre'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

        if ($not_delete_first_vrs == false)
            $not_delete_first_vrs = true;
    }

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats 
        SET s_nb_news = s_nb_news - 1
        WHERE s_id_membre = '.intval($dn_news['n_id_auteur'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

    //On supprime une news de la catégorie
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'categories 
        SET c_nbr_news=c_nbr_news-1
        WHERE c_id='.$id_cat) OR Nw::$DB->trigger(__LINE__, __FILE__);
        
    // Longue requête (suppression de TOUTE la news)
    Nw::$DB->query('DELETE t1, t2, t3, t4, t5, t6, t7
            FROM '.Nw::$prefix_table.'news t1
                LEFT JOIN '.Nw::$prefix_table.'news_versions t2 ON t1.n_id=t2.v_id_news
                LEFT JOIN '.Nw::$prefix_table.'news_commentaires t3 ON t1.n_id=t3.c_id_news
                LEFT JOIN '.Nw::$prefix_table.'plussoies t4 ON t4.p_id_com=t3.c_id
                LEFT JOIN '.Nw::$prefix_table.'news_vote t5 ON t5.v_id_news=t1.n_id
                LEFT JOIN '.Nw::$prefix_table.'news_favs t6 ON t6.f_id_news=t1.n_id
                LEFT JOIN '.Nw::$prefix_table.'news_flags t7 ON t7.f_id_news=t1.n_id
            WHERE t1.n_id='.$id) OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    $add_champs_sql = '';
    $add_value_sql = '';
    $text_log = array();
    $text_log[] = Nw::$lang['news']['log_news_9'];
    
    if (check_auth('can_delete_news'))
    {
        if (!empty($_POST['raison']))
        {
            $text_log[] = sprintf(Nw::$lang['news']['log_del_add_raison'], $_POST['raison']);
        }
        if (!empty($_POST['contenu']))
        {
            $titre_mail = sprintf(Nw::$lang['news']['mail_titre_news_del'], $titre_news);
            $content_mail = sprintf(Nw::$lang['news']['mail_news_del'], 
                $dn_news['u_pseudo'], 
                Nw::$site_url.'profile/'.$dn_news['u_alias'].'/', 
                Nw::$dn_mbr['u_pseudo'], 
                Nw::$site_name, 
                $titre_news,
                nl2br($_POST['contenu']),
                Nw::$site_url.'help-rules.html');
            
            @envoi_mail($dn_news['u_email'], $titre_mail, $content_mail);
            $text_log[] = sprintf(Nw::$lang['news']['log_del_add_email'], Nw::$dn_mbr['u_pseudo'], $_POST['contenu']);
        }
    }
    
    if (count($text_log) > 1)
    {
        $add_champs_sql = 'l_texte, ';
        $add_value_sql = '\''.Nw::$DB->real_escape_string(implode("\r", $text_log)).'\', ';
    }
    
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_titre, l_action, '.$add_champs_sql.'l_date, l_ip) VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', \''.insertBD($titre_news).'\', 9, '.$add_value_sql.'NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

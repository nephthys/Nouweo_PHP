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

class Page extends Core
{
    protected function main()
    {
        // Si le paramètre ID manque
        if (empty($_GET['id']))
            header('Location: ./');

        inc_lib('users/mbr_exists');
        if (mbr_exists($_GET['id']) == false)
            redir(Nw::$lang['users']['mbr_dont_exist'], false, 'users.html');

        inc_lib('users/get_info_mbr');
        $donnees_profile = get_info_mbr($_GET['id']);
        
        $this->load_lang_file('users');
        $this->load_lang_file('news');
        
        $this->add_wid_in_content('view_profile.'.$donnees_profile['u_id']);
        $this->set_tpl('profile/list_comments.html');
        $this->set_title(sprintf(Nw::$lang['profile']['profile_title'], $donnees_profile['u_pseudo']));
        $this->add_css('code.css');
        $this->add_js('profil.js');
        $this->set_filAriane(array(
            Nw::$lang['users']['members_section']           => array('users.html'),
            $donnees_profile['u_pseudo']                    => array('./profile/'.$donnees_profile['u_alias'].'/'),
            Nw::$lang['profile']['title_cmts_author']       => array(''),
        ));
        
        $params_contrib = array();
        $params_contrib[] = 'c_id_membre = '.intval($_GET['id']);
        
        if (!is_logged_in())
            $params_contrib[] = 'n_etat = 3';

        inc_lib('profile/count_comments_mbr');
        $nombre_cmts = count_comments_mbr(implode(' AND ', $params_contrib));
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_cmts / Nw::$pref['ppl_nb_comments'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');

        inc_lib('profile/get_comments_mbr');
        $com_cours = 0;
        $list_cmts = get_comments_mbr(implode(' AND ', $params_contrib), 'c_date DESC', $page, Nw::$pref['ppl_nb_comments']);
        
        foreach($list_cmts AS $donnees_cmts)
        {
            ++$com_cours;
            $droit_edit = false;
            $droit_delete = false;
                
            if(is_logged_in()) 
            {
                $droit_edit = (bool) (Nw::$droits['can_edit_my_comments'] && $donnees_cmts['u_id'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_edit_all_comments'];
                $droit_delete = (bool) (Nw::$droits['can_del_my_comments'] && $donnees_cmts['u_id'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_del_all_comments'];
            }
                
            $date_cmt = date_sql($donnees_cmts['date'], $donnees_cmts['heures_date'], $donnees_cmts['jours_date']);
            $masque_motif = '';
                
            if($donnees_cmts['c_masque'])
            {
                $date_cmt = sprintf(Nw::$lang['news']['del_cmt_with_reason'], strtolower(date_sql($donnees_cmts['date'], $donnees_cmts['heures_date'], $donnees_cmts['jours_date'])));
                    
                if (!empty($donnees_cmts['c_masque_raison']))
                        $masque_motif = ' ('.sprintf(Nw::$lang['news']['motif_delete_cmt'], $donnees_cmts['c_masque_raison']).')';
            }
                
            Nw::$tpl->setBlock('cmt', array(
                'ID'            => $donnees_cmts['c_id'],
                'ID_NEWS'       => $donnees_cmts['c_id_news'],
                'REWRITE'       => rewrite($donnees_cmts['n_titre']),
                'CAT_REWRITE'   => $donnees_cmts['c_rewrite'],
                
                'NUM'           => (($page-1)*Nw::$pref['nb_cmts_page'])+$com_cours,    
                'DATE'          => $date_cmt,
                    
                'AVATAR'        => $donnees_cmts['u_avatar'],
                'LANG_AVATAR'   => sprintf(Nw::$lang['news']['lang_avatar'], $donnees_cmts['u_pseudo']),
                    
                'AUTEUR'        => $donnees_cmts['u_pseudo'],
                'AUTEUR_ID'     => $donnees_cmts['u_id'],
                'AUTEUR_ALIAS'  => $donnees_cmts['u_alias'],
                    
                'TEXTE'         => $donnees_cmts['c_texte'],
                'PLUSSOIE'      => $donnees_cmts['c_plussoie'],
                    
                'GRP_TITRE'     => $donnees_cmts['g_titre'],
                'GRP_ICON'      => $donnees_cmts['g_icone'],
                'IP'            => long2ip($donnees_cmts['c_ip']),
                    
                'MASQUE'        => $donnees_cmts['c_masque'],
                'MASQUE_MOTIF'  => $masque_motif,
                    
                'EDIT'          => $droit_edit,
                'DELETE'        => $droit_delete,
            ));
        }
        
        
        Nw::$tpl->set(array(
            'NOMBRE_CMTS'       => $nombre_cmts,
            'LIST_PG'           => list_pg($nombreDePages, $page, 'profile-135-'.$_GET['id'].'%s.html'),
        ));
        
        inc_lib('profile/assign_required_vars_profile');
        assign_required_vars_profile($donnees_profile);
    }
}

/*  *EOF*   */

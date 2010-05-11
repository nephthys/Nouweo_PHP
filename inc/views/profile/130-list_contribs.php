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
        $this->set_tpl('profile/list_contribs.html');
        $this->set_title(sprintf(Nw::$lang['profile']['profile_title'], $donnees_profile['u_pseudo']));
        $this->add_css('code.css');
        $this->add_js('profil.js');
        $this->set_filAriane(array(
            Nw::$lang['users']['members_section']           => array('users.html'),
            $donnees_profile['u_pseudo']                    => array('./profile/'.$donnees_profile['u_alias'].'/'),
            Nw::$lang['profile']['title_news_contrib']      => array(),
        ));
        
        $params_contrib = array();
        $params_contrib[] = 'v_id_membre = '.intval($_GET['id']);
        
        if (!is_logged_in())
            $params_contrib[] = 'n_etat = 3';

        inc_lib('profile/count_news_contrib');
        $nombre_contrib = count_news_contrib(implode(' AND ', $params_contrib));
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_contrib / Nw::$pref['ppl_nb_contribs'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');

        inc_lib('profile/get_news_contrib');
        $cours_news = 0;
        $contrib_cours = '';
        $count_section = 0;
        $list_contrib = get_news_contrib(implode(' AND ', $params_contrib), 'v_date DESC, n_date DESC', $page, Nw::$pref['ppl_nb_contribs']);
        
        foreach($list_contrib AS $donnees_contrib)
        {
            Nw::$tpl->setBlock('contrib', array(
                'ID'        => $donnees_contrib['v_id'],
                'ID_NEWS'   => $donnees_contrib['v_id_news'],
                'MOTIF'     => $donnees_contrib['v_raison'],
                
                'NB_MOTS'   => sprintf(Nw::$lang['news']['nbr_caract'], $donnees_contrib['v_nb_mots']),
                'DIFF_MOTS' => $donnees_contrib['v_diff_mots'],
                
                'IP'        => long2ip($donnees_contrib['v_ip']),
                'COURS'     => $cours_news%2,
                
                'DATE'      => date_sql($donnees_contrib['date'], $donnees_contrib['heures_date'], $donnees_contrib['jours_date']),
            ));
            
            ++$cours_news;
            
            if ($contrib_cours != $donnees_contrib['v_id_news'])
            {
                Nw::$tpl->setBlock('contrib.news', array(
                    'ID'            => $donnees_contrib['n_id'],
                    'TITRE'         => $donnees_contrib['n_titre'],
                    'REWRITE'       => rewrite($donnees_contrib['n_titre']),
                    'CAT_REWRITE'   => $donnees_contrib['c_rewrite'],
                    
                    'ETAT'          => $donnees_contrib['n_etat'],
                    'ETAT_LANG'     => Nw::$lang['news']['etat_news_'.$donnees_contrib['n_etat']],
                    'ETAT_ACT'      => ($donnees_contrib['n_etat'] == 1) ? 70 : 80,
                    
                    'IMAGE_ID'      => $donnees_contrib['i_id'],
                    'IMAGE_NOM'     => $donnees_contrib['i_nom'],
                    
                    'END'           => ($count_section > 0) ? '</div></div>' : '',
                ));
                
                $contrib_cours = $donnees_contrib['v_id_news'];
                ++$count_section;
            }
        }
        
        
        Nw::$tpl->set(array(
            'END_DIV'           => ($count_section > 0) ? '</div></div>' : '',
            'NOMBRE_CONTRIB'    => $nombre_contrib,
            'LIST_PG'           => list_pg($nombreDePages, $page, 'profile-130-'.$_GET['id'].'%s.html'),
        ));
        
        inc_lib('profile/assign_required_vars_profile');
        assign_required_vars_profile($donnees_profile);
    }
}

/*  *EOF*   */

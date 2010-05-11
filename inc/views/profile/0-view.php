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
        $this->set_tpl('profile/list_news.html');
        $this->set_title(sprintf(Nw::$lang['profile']['profile_title'], $donnees_profile['u_pseudo']));
        $this->add_css('code.css');
        $this->add_js('profil.js');
        $this->base_enabled(true);  
        $this->set_filAriane(array(
            Nw::$lang['users']['members_section']           => array('users.html'),
            $donnees_profile['u_pseudo']                    => array('./profile/'.$donnees_profile['u_alias'].'/'),
            Nw::$lang['profile']['title_news_author']       => array(),
        ));
        
        $params_news = array();
        $params_news[] = 'n_id_auteur = '.intval($donnees_profile['u_id']);
        
        if (!is_logged_in())
            $params_news[] = 'n_etat = 3';

        inc_lib('profile/count_news_author');
        $nombre_news = count_news_author(implode(' AND ', $params_news));
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['ppl_nb_news'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');

        inc_lib('profile/get_news_author');
        $cours_news = 0;
        $list_news = get_news_author(implode(' AND ', $params_news), 'n_date DESC', $page, Nw::$pref['ppl_nb_news']);
        
        foreach($list_news AS $donnees_news)
        {
            ++$cours_news;
            
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                'TITRE'         => $donnees_news['n_titre'],
                'CAT_REWRITE'   => $donnees_news['c_rewrite'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                'RESUME'        => $donnees_news['n_resume'],
                
                
                'ETAT'          => $donnees_news['n_etat'],
                'ETAT_LANG'     => Nw::$lang['news']['etat_news_'.$donnees_news['n_etat']],
                'ETAT_ACT'      => ($donnees_news['n_etat'] == 1) ? 70 : 80,
                
                'NBR_VOTES'     => $donnees_news['n_nb_votes'],
                'HAS_VOTED'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                
                'DATE'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
                
                'IMAGE_ID'      => $donnees_news['i_id'],
                'IMAGE_NOM'     => $donnees_news['i_nom'],
                
                'COURS'         => $cours_news%2,
            ));
        }
        
        
        Nw::$tpl->set(array(
            'NOMBRE_NEWS'       => $nombre_news,
            'LIST_PG'           => list_pg($nombreDePages, $page, 'profile/%s.html'),
        ));
        
        inc_lib('profile/assign_required_vars_profile');
        assign_required_vars_profile($donnees_profile);
    }
}

/*  *EOF*   */

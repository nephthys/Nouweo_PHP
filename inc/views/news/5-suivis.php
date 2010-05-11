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
        $this->set_title(Nw::$lang['news']['suivis_news']);
        $this->set_tpl('news/suivis.html');
        $this->add_css('code.css');
        $this->add_wid_in_content('suivis');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']               => array('news-70.html'),
            Nw::$lang['news']['suivis_news']                => array('')
        ));
        
        // Paramètres
        $get_params = array();
        $param_order = 0;
        
        $array_sort = array(
            0   => 'n_date DESC',
            1   => 'n_nb_versions DESC',
            2   => 'n_nbr_coms DESC',
            3   => 'n_nb_votes DESC',
        );
            
        $flag   = (isset($_GET['flag'])) ? $_GET['flag'] : 2;
        $status = (isset($_GET['status'])) ? $_GET['status'] : 3;
        $sort   = (isset($_GET['sort'])) ? $_GET['sort'] : 0;
        $cat    = (isset($_GET['cat'])) ? $_GET['cat'] : 0;
        
        // Option Type
        if (is_logged_in() && $flag != 0)
        {
            if (isset($_GET['flag']) && in_array($_GET['flag'], array(0, 1, 2, 3)))
                $get_params[] = 'f_type = '.intval($_GET['flag']);
            else
                $get_params[] = 'f_type = 2';
        }
        
        // Option Etat
        if (is_logged_in() && isset($_GET['status']) && in_array($_GET['status'], array(0, 1, 2, 3)))
        {
            if (($_GET['status'] == 0 && is_logged_in() && Nw::$droits['can_edit_news_online']) OR $_GET['status'] != 0)
                $get_params[] = 'n_etat = '.intval($_GET['status']);
        }
        else
            $get_params[] = 'n_etat = 3';
            
        // Option Sort
        if (isset($_GET['sort']) && in_array($_GET['sort'], array(0, 1, 2, 3)))
            $param_order = $_GET['sort'];
            
        // Option Catégorie
        if (is_logged_in() && !empty($_GET['cat']) && is_numeric($_GET['cat']))
            $get_params[] = 'n_id_cat = '.intval($_GET['cat']);
    

        // Pagination
        inc_lib('news/count_news');
        $nombre_news = count_news(implode(' AND ', $get_params));
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_homepage'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');
            
        // On recherche toutes les news en rédaction
        inc_lib('news/get_list_news');
        $list_dn_news = get_list_news(implode(' AND ', $get_params), $array_sort[$param_order], $page, Nw::$pref['nb_news_homepage']);
        $cours_news = 0;
        $cours_news2 = 0;

        inc_lib('news/can_edit_news');
        foreach($list_dn_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                'COURS'         => $cours_news%2,
                'COURS2'        => $cours_news2%2,
                
                'CAT_ID'        => $donnees_news['c_id'],
                'CAT_TITRE'     => $donnees_news['c_nom'],
                'CAT_REWRITE'   => $donnees_news['c_rewrite'],
                'IMAGE_ID'      => $donnees_news['i_id'],
                'IMAGE_NOM'     => $donnees_news['i_nom'],
                
                'TITRE'         => $donnees_news['n_titre'],
                'RESUME'        => $donnees_news['n_resume'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                
                'AUTEUR'        => $donnees_news['u_pseudo'],
                'AUTEUR_ID'     => $donnees_news['u_id'],
                'AUTEUR_ALIAS'  => $donnees_news['u_alias'],
                'AUTEUR_AVATAR' => $donnees_news['u_avatar'],
                
                'ETAT'          => $donnees_news['n_etat'],
                'DATE'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
                'HAS_VOTED'     => $donnees_news['v_id_membre'],
                
                'NBR_VOTES'     => $donnees_news['n_nb_votes'],
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                
                'DRT_EDIT'      => (is_logged_in()) ? can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat']) : false,
                'DRT_DELETE'    => (is_logged_in() && (($donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id'] && Nw::$droits['can_delete_mynews']) || Nw::$droits['can_delete_news'])) ? true : false,
            ) );
            
            if($donnees_news['i_id'] != 0)
                ++$cours_news;
                
            ++$cours_news2;
        }

        inc_lib('news/count_flags_news');
        Nw::$tpl->set(array(
            'NB_FLAGS'      => (is_logged_in()) ? count_flags_news(Nw::$dn_mbr['u_id']) : '',
            'LIST_PG'       => list_pg($nombreDePages, $page, 'news-5%s.html?flag='.$flag.'&status='.$status.'&sort='.$sort.'&cat='.$cat),
            
            'FLAG'          => $flag,
            'STATUS'        => $status,
            'SORT'          => $sort,
            'CAT'           => $cat,
        ));
    }
}

/*  *EOF*   */

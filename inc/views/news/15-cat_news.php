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
        inc_lib('news/get_info_cat');
        $donnees_cat_news = get_info_cat($_GET['ct'], 'rewrite');
        
        if (count($donnees_cat_news) == 0 || empty($donnees_cat_news) || empty($_GET['ct']))
             header('Location: ./');
        
        $this->set_title($donnees_cat_news['c_nom']);
        $this->set_tpl('news/cat_news.html');
        $this->add_css('code.css');
        $this->base_enabled(true);
        $this->add_wid_in_content('view_cat.'.$donnees_cat_news['c_id']);
        
        // On compte le nbr de news publiées
        inc_lib('news/count_news');
        $nombre_news = count_news('n_etat = 3 AND n_id_cat = '.$donnees_cat_news['c_id']);
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_homepage'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');
            
        // On recherche toutes les news en rédaction
        inc_lib('news/get_list_news');
        inc_lib('news/can_edit_news');
        $list_dn_news = get_list_news('n_etat = 3 AND n_id_cat = '.$donnees_cat_news['c_id'], 'n_date DESC', $page, Nw::$pref['nb_news_homepage']);
        $cours_news = 0;
        $cours_news2 = 0;
        
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
                
                'DATE'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
                'HAS_VOTED'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
                
                'NBR_VOTES'     => $donnees_news['n_nb_votes'],
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                
                'DRT_EDIT'      => (is_logged_in()) ? can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat']) : false,
                'DRT_DELETE'    => (is_logged_in() && (($donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id'] && Nw::$droits['can_delete_mynews']) || Nw::$droits['can_delete_news'])) ? true : false,
            ) );
            
            if($donnees_news['i_id'] != 0)
                ++$cours_news;
                
            ++$cours_news2;
        }
        /**
        *   Nuage de tags
        **/
        inc_lib('news/nuage_tags');
        $tags_a_afficher = 30;
        $list_tags_metas = array();
        $nuage_tags = nuage_tags($tags_a_afficher, $donnees_cat_news['c_id']);
        
        foreach($nuage_tags AS $donnees_tags)
        {
            $list_tags_metas[] = $donnees_tags['t_tag'];
            
            Nw::$tpl->setBlock('nuage', array(
                'INT'           => $donnees_tags['t_tag'],
                'REWRITE'       => urlencode($donnees_tags['t_tag']),
                'SIZE'          => $donnees_tags['size'],
                'COLOR'         => $donnees_tags['c_couleur'],
            ));
        }
        
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']  => array('news-70.html'),
            $donnees_cat_news['c_nom']         => array($donnees_cat_news['c_rewrite'].'/')
        ));
        
        $this->metas(array(
            'desc'      => $donnees_cat_news['c_desc'],
            'tags'      => implode(', ', $list_tags_metas),
        ));

        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, $donnees_cat_news['c_rewrite'].'/%s', ''),
            'TITRE'         => $donnees_cat_news['c_nom'],
        ));
    }
}

/*  *EOF*   */

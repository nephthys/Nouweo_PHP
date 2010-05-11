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
        if (empty($_GET['s']) OR strlen(trim($_GET['s'])) < 2)
            header('Location: ./');
        
        $title_recherche = htmlspecialchars($_GET['s']);
    
        $this->set_title(sprintf(Nw::$lang['search']['titre_recherche'], $title_recherche));
        $this->set_tpl('search/results.html');
        $this->load_lang_file('news');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['search']['fa_recherche']         => './',
            $title_recherche                            => '',
        ));
        
        $etat_news_afficher = (is_logged_in()) ? 0 : 3;
        
        // On compte le nbr de news publiées
        inc_lib('search/count_search_results');
        $nombre_news = count_search_results($_GET['s'], $etat_news_afficher);
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_homepage'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');
        
        // On recherche toutes les news en rédaction
        inc_lib('search/search_news_bytag');
        inc_lib('news/can_edit_news');
        $list_dn_news = search_news_bytag($_GET['s'], $etat_news_afficher, $page, Nw::$pref['nb_news_homepage']);
        $cours_news = 0;
        $cours_news2 = 0;
        
        foreach($list_dn_news AS $donnees_news)
        {
            $ids_all_news[] = $donnees_news['n_id'];
            
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                'COURS'         => $cours_news%2,
                'COURS2'        => $cours_news2%2,
                
                'CAT_ID'        => $donnees_news['c_id'],
                'CAT_TITRE'     => $donnees_news['c_nom'],
                'CAT_REWRITE'   => $donnees_news['c_rewrite'],
                
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
            
            ++$cours_news2;
        }
        
        // Historique des recherches
        if ($nombre_news > 0)
        {
            inc_lib('search/add_search_log');
            inc_lib('admin/gen_cachefile_top_search');
            add_search_log(urldecode($_GET['s']), $cours_news2);
            
            inc_lib('admin/gen_cachefile_top_search');
            gen_cachefile_top_search();
        }
        
        /**
        *   Nuage de tags
        **/
        inc_lib('news/nuage_tags');
        $tags_a_afficher = 30;
        $nuage_tags = nuage_tags($tags_a_afficher);
        
        foreach($nuage_tags AS $donnees_tags)
        {
            Nw::$tpl->setBlock('nuage', array(
                'INT'           => $donnees_tags['t_tag'],
                'REWRITE'       => urlencode($donnees_tags['t_tag']),
                'SIZE'          => $donnees_tags['size'],
                'COLOR'         => $donnees_tags['c_couleur'],
            ));
        }

        inc_lib('search/get_tags_search');
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'search%s.html?s='.$_GET['s']),
            'SEARCH'        => $title_recherche,
            'SUGGEST'       => get_tags_search($_GET['s'], 0, $etat_news_afficher, 1),
        ));
    }
}

/*  *EOF*   */

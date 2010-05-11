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
    
        $this->load_lang_file('search');
        $this->load_lang_file('news');
        $this->set_tpl('mobile/search/results.html');
        
        $etat_news_afficher = (is_logged_in()) ? 0 : 3;
        
        // On compte le nbr de news publiées
        inc_lib('search/count_search_results');
        $nombre_news = count_search_results($_GET['s'], $etat_news_afficher);
        
        // Pagination
        $page = (!empty($_GET['page'])) ? (int) $_GET['page'] : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_homepage'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './');
        
        // On recherche toutes les news en rédaction
        inc_lib('search/search_news_bytag');
        inc_lib('news/can_edit_news');
        $list_dn_news = search_news_bytag($_GET['s'], $etat_news_afficher, $page, Nw::$pref['nb_news_homepage']);
        $cours_news = 0;
        
        foreach($list_dn_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                
                'CAT_ID'        => $donnees_news['c_id'],
                'CAT_TITRE'     => $donnees_news['c_nom'],
                
                'TITRE'         => $donnees_news['n_titre'],
                'RESUME'        => $donnees_news['n_resume'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                
                'IMAGE_ID'      => $donnees_news['i_id'],
                'IMAGE_NOM'     => $donnees_news['i_nom'],
                
                'DATE'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
                'HAS_VOTED'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
                
                'NBR_VOTES'     => $donnees_news['n_nb_votes'],
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
            ) );
            
            ++$cours_news;
        }
        
        // Historique des recherches
        if ($nombre_news > 0)
        {
            inc_lib('search/add_search_log');
            inc_lib('admin/gen_cachefile_top_search');
            
            add_search_log(urldecode($_GET['s']), $cours_news);
            gen_cachefile_top_search();
        }

        inc_lib('search/get_tags_search');
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'mobile-1%s.html?s='.$_GET['s']),
            'SEARCH'        => $title_recherche,
            'NB_NEWS'       => $cours_news,
            'SUGGEST'       => get_tags_search($_GET['s'], 0, $etat_news_afficher, 1),
            'INC_HEAD'      => empty($_SERVER['HTTP_AJAX']),
        ));
    }
}

/*  *EOF*   */

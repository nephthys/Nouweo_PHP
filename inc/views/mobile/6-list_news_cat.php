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
        $this->set_tpl('mobile/categories/list_news.html');
        $this->load_lang_file('news');
        
        $id_cat = (!empty($_GET['id']) AND is_numeric($_GET['id']) AND $_GET['id'] > 0) ? (int) $_GET['id'] : 0;
        
        //récupération des infos de la catégorie
        inc_lib('news/get_info_cat');
        $dn_cat = get_info_cat($id_cat);
        
        if(empty($dn_cat))
            redir(Nw::$lang['common']['pg_not_exist'], false, './');
        else
        {
            Nw::$tpl->set(array(
                'TITLE'           => $dn_cat['c_nom'],
                'TITLE_REWRITE'   => rewrite($dn_cat['c_nom']),
            ));
        }
        
        // On compte le nbr de news publiées
        inc_lib('news/count_news');
        $nombre_news = count_news('n_etat = 3 AND n_id_cat = '.$id_cat);
        $ids_all_news = array();// ?
        
        // Pagination
        $page = (!empty($_GET['page']) AND is_numeric($_GET['page']) AND $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_homepage'] );
        
        // On vérifie que la page existe bien
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './mobile.html');
        
        // On recherche toutes les news publiées
        inc_lib('news/get_list_news');
        $list_dn_news = get_list_news('n_etat = 3 AND n_id_cat = '.$id_cat, 'n_date DESC', $page, Nw::$pref['nb_news_homepage']);
        $cours_news = 0;
        
        foreach($list_dn_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                
                'CAT_ID'        => $donnees_news['c_id'],
                'CAT_TITRE'     => $donnees_news['c_nom'],
                'CAT_REWRITE'   => rewrite($donnees_news['c_nom']),
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
                
                'NBR_VOTES'     => $donnees_news['n_nb_votes'],
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
            ) );
            
            ++$cours_news;
        }
        
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'mobile-2%s.html'),
            'NB_NEWS'       => $cours_news,
            'INC_HEAD'      => empty($_SERVER['HTTP_AJAX']),
        ));
    }
}

/*  *EOF*   */

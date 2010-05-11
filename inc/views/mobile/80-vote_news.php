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
        if(!is_logged_in())
            header('Location: mobile.html');
        
        if(empty($_GET['id']) OR !is_numeric($_GET['id']) OR $_GET['id'] <= 0)
            $this->show_waiting_news_list();
        else
            $this->vote();
        
        Nw::$tpl->set('INC_HEAD', empty($_SERVER['HTTP_AJAX']));
    }
    
    private function vote()
    {
        $id_news = (int) $_GET['id'];
        
        // Cette news existe vraiment ?
        inc_lib('news/news_exists');
        if (!news_exists($_GET['id']))
            header('Location: mobile.html');
        
        $type_vote = True;
        if(isset($_GET['id2']) AND $_GET['id2'] != '')
            $type_vote = ((int) $_GET['id2'] == 1);
        
        inc_lib('news/add_vote_news');
        add_vote_news($id_news, $type_vote);
        
        header('Location: mobile-10-'.$id_news.'.html');
    }
    
    private function show_waiting_news_list()
    {
        $this->set_tpl('mobile/categories/list_news.html');
        $this->load_lang_file('news');
        
        // On compte le nbr de news en rédaction
        inc_lib('news/count_news');
        $nombre_news = count_news('n_etat = 2');
        
        // Pagination
        $page = (!empty($_GET['page']) AND is_numeric($_GET['page']) AND $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_redac'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, './mobile.html');
        
        // On recherche toutes les news en rédaction
        inc_lib('news/get_list_news');
        $list_dn_news = get_list_news('n_etat = 2', 'n_date DESC', $page, Nw::$pref['nb_news_redac']);
        
        // On affiche toutes les news en rédaction
        foreach($list_dn_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                
                'CAT_ID'        => $donnees_news['c_id'],
                'CAT_TITRE'     => $donnees_news['c_nom'],
                
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
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                'NBR_VERSIONS'  => ($donnees_news['n_nb_versions'] > 1) ? '<a href="news-16-'.$donnees_news['n_id'].'.html">'.sprintf(Nw::$lang['news']['nbr_versions_news'], $donnees_news['n_nb_versions']).'</a>' : Nw::$lang['news']['none_versions'],
                
                'VOTES'         => $donnees_news['n_nb_votes'],
                'VOTES_NEG'     => $donnees_news['n_nb_votes_neg'],
                'HAS_VOTED'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
            ) );
        }
        
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'mobile-2%s.html'),
            'NB_NEWS'       => $nombre_news,
            'TITLE'         => Nw::$lang['news']['en_attente_title'],
            'TITLE_REWRITE' => 'news_en_attente',
        ));
    }
}

/*  *EOF*   */

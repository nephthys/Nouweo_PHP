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
        if (empty($_GET['id']) || !is_numeric($_GET['id']))
            header('Location: news-70.html');
    
        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false)
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        
        // Ce membre a le droit d'éditer la news ?
        if ($donnees_news['n_etat'] != 3 && !is_logged_in())
            redir(Nw::$lang['news']['not_view_news_perm'], false, './');
        
        $this->set_tpl('mobile/news/view_news.html');
        $this->load_lang_file('news');

        inc_lib('news/has_voted_news');
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'ETAT'              => $donnees_news['n_etat'],
            'CAT_ID'            => $donnees_news['c_id'],
            'CAT_TITRE'         => $donnees_news['c_nom'],
            
            'AUTEUR'            => $donnees_news['u_pseudo'],
            'AUTEUR_ALIAS'      => $donnees_news['u_alias'],
            'AUTEUR_AVATAR'     => $donnees_news['u_avatar'],
            
            'DATE'              => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
            
            'NBR_COMS'          => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
            'COMS'              => $donnees_news['n_nbr_coms'],
            
            'NB_VOT_VALID'      => Nw::$pref['nb_votes_valid_news'],
            'VOTES'             => $donnees_news['n_nb_votes'],
            'VOTES_NEG'         => $donnees_news['n_nb_votes_neg'],
            
            'IMAGE_ID'          => $donnees_news['i_id'],
            'IMAGE_NOM'         => $donnees_news['i_nom'],
            
            'SOURCE'            => (!empty($donnees_news['n_src_url'])) ? $donnees_news['n_src_url'] : '',
            'SOURCE_NOM'        => (!empty($donnees_news['n_src_nom'])) ? $donnees_news['n_src_nom'] : '',
            
            'TITRE'             => $donnees_news['n_titre'],
            'REWRITE'           => rewrite($donnees_news['n_titre']),
            'CONTENU'           => $donnees_news['v_texte'],
        
            'HAS_VOTED'         => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
        ));
        
        // Màj du nombre de visualisations
        inc_lib('news/update_pg_vues');
        update_pg_vues($_GET['id']);
        
        Nw::$tpl->set('INC_HEAD', empty($_SERVER['HTTP_AJAX']));
    }
}

/*  *EOF*   */

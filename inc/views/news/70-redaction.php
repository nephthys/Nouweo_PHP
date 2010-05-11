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
        if (!is_logged_in()) {
            redir(Nw::$lang['common']['need_login'], false, 'users-10.html');
        }
        
        $this->set_title(Nw::$lang['news']['en_redaction_title']);
        $this->set_tpl('news/redaction.html');
        $this->add_wid_in_content('redaction');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']           => array('news-70.html'),
            Nw::$lang['news']['en_redaction_title']     => array('')
        ));
        
        // On compte le nbr de news en rédaction
        inc_lib('news/count_news');
        $nombre_news = count_news('n_etat = 1');
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_redac'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, 'news-70.html');
        
        // On recherche toutes les news en rédaction
        inc_lib('news/get_list_news');
        $list_dn_news = get_list_news('n_etat = 1', 'f_type DESC, n_last_mod DESC', $page, Nw::$pref['nb_news_redac']);
        $news_act = 'null';
        
        if($nombre_news > 0)
        {
            inc_lib('news/get_list_tags_news');
            inc_lib('news/get_list_flags_news');
            $list_tags = array();
            $list_flags = array();
            $array_favoris = array();
            $donnees_tags = get_list_tags_news(1);
            $donnees_flags = get_list_flags_news(1);
            
            foreach($donnees_flags AS $all_flags)
            {
                if ($all_flags['f_type'] == 1)
                    $array_favoris[] = $all_flags['f_id_news'];
                
                $list_flags[$all_flags['f_id_news']][] = '<li class="netat"><a href="news-5.html?flag='.$all_flags['f_type'].'&amp;status=1">'.$all_flags['txt_lang'].'</a></li>';
            }
            
            foreach($donnees_tags AS $all_tags)
            {
                $list_tags[$all_tags['t_id_news']][] = '<li class="ntag"><a href="search.html?s='.$all_tags['rewrite'].'">'.$all_tags['t_tag'].'</a></li>';
            }
        }
        
        
        $nbr_news = 0;
        
        // On affiche toutes les news en rédaction
        inc_lib('news/can_edit_news');
        foreach($list_dn_news AS $donnees_news)
        {
            $flags_html = '';
            
            // Affichage du séparateur
            if( $donnees_news['f_type'] != $news_act )
            {
                $texte_title = '';
                
                //  Séparation des news
                if( $donnees_news['f_type'] == 0 ) {
                    $texte_title = '<div class="separation_news"></div>';
                }
                
                Nw::$tpl->setBlock('redaction', array('NAME' => $texte_title) );
                $news_act = $donnees_news['f_type'];
            }
            
            // Cette news a des flags? (Je rédige, etc.)
            if( isset( $list_flags[$donnees_news['n_id']]) && count( $list_flags[$donnees_news['n_id']] ) > 0 )
                $flags_html = implode( ' ', $list_flags[$donnees_news['n_id']] );
            
            if( isset( $list_tags[$donnees_news['n_id']] ) )
                $tags_news = array_slice($list_tags[$donnees_news['n_id']], 0, 5);
            else
                $tags_news = array();
            
            // Il y a au moins un tag
            if( count( $tags_news ) > 0 )
                $tags_html = implode( ' ', $tags_news );
            else
                $tags_html = '<li class="ntag"><span>'.Nw::$lang['news']['none_tag'].'</span></li>';
            
            Nw::$tpl->setBlock('redaction.news', array(
                'ID'            => $donnees_news['n_id'],
                
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
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                'NBR_VERSIONS'  => ($donnees_news['n_nb_versions'] > 1) ? '<a href="news-16-'.$donnees_news['n_id'].'.html">'.sprintf(Nw::$lang['news']['nbr_versions_news'], $donnees_news['n_nb_versions']).'</a>' : Nw::$lang['news']['none_versions'],
                'TAGS'          => $tags_html,
                'FLAGS'         => $flags_html,
                'COLOR'         => ($nbr_news%2),
                'FLAGS_FAV'     => (bool) (in_array($donnees_news['n_id'], $array_favoris)),
                
                
                'EDIT'          => can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat']),
                'DELETE'        => (($donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id'] && Nw::$droits['can_delete_mynews']) || Nw::$droits['can_delete_news']) ? true : false,
            ) );
            
            ++$nbr_news;
        }
        
        /**
        *   Dernières news créées
        **/
        inc_lib('news/get_list_news');
        $last_news = get_list_news('n_etat = 1', 'n_date DESC', 1, 7);
        
        foreach($last_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('ln', array(
                'ID'            => $donnees_news['n_id'],
                'TITRE'         => $donnees_news['n_titre'],
                'CAT_REWRITE'   => $donnees_news['c_rewrite'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                'NBR_VERSIONS'  => ($donnees_news['n_nb_versions'] > 1) ? '<a href="news-16-'.$donnees_news['n_id'].'.html">'.sprintf(Nw::$lang['news']['nbr_versions_news'], $donnees_news['n_nb_versions']).'</a>' : Nw::$lang['news']['none_versions'],
            ));
        }
        
        
        /**
        *   Dernière contribution
        **/
        inc_lib('news/get_list_vrs');
        $last_contrib = get_list_vrs(0, 7);
        
        foreach($last_contrib AS $donnees_contrib)
        {
            Nw::$tpl->setBlock('lc', array(
                'ID'            => $donnees_contrib['v_id'],
                'ID_NEWS'       => $donnees_contrib['v_id_news'],
                'RAISON'        => $donnees_contrib['v_raison'],
                'AUTEUR'        => $donnees_contrib['u_pseudo'],
                'AUTEUR_ID'     => $donnees_contrib['u_id'],
                'AUTEUR_ALIAS'  => $donnees_contrib['u_alias'],
                'AUTEUR_AVATAR' => $donnees_contrib['u_avatar'],
                
                'REWRITE'       => rewrite($donnees_contrib['n_titre']),
                'CAT_REWRITE'   => $donnees_contrib['c_rewrite'],
                'INT'           => sprintf(Nw::$lang['news']['version_x'], $donnees_contrib['v_id']),
                'DATE'          => date_sql($donnees_contrib['date'], $donnees_contrib['heures_date'], $donnees_contrib['jours_date']),
            ));
        }
        
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'news-70%s.html'),
        ));
        
    }
}

/*  *EOF*   */

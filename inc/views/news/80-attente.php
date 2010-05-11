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
        // Seuls les membres peuvent créer des brouillons
        if (!is_logged_in()) {
            redir(Nw::$lang['common']['need_login'], false, 'users-10.html');
        }
        
        $this->set_title(Nw::$lang['news']['en_attente_title']);
        $this->set_tpl('news/attente.html');
        $this->add_css('code.css');
        $this->add_js('news.attente.js');
        $this->add_wid_in_content('attente');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']           => array('news-70.html'),
            Nw::$lang['news']['en_attente_title']       => array('')
        ));
        
        // On compte le nbr de news en rédaction
        inc_lib('news/count_news');
        $nombre_news = count_news('n_etat = 2');
        
        if($nombre_news > 0)
        {
            inc_lib('news/get_list_tags_news');
            inc_lib('news/get_list_flags_news');
            $list_tags = array();
            $list_flags = array();
            $array_favoris = array();
            $donnees_tags = get_list_tags_news(2);
            $donnees_flags = get_list_flags_news(2);
            
            foreach($donnees_flags AS $all_flags)
            {
                if ($all_flags['f_type'] == 1)
                    $array_favoris[] = $all_flags['f_id_news'];
                
                $list_flags[$all_flags['f_id_news']][] = '<li class="netat"><a href="news-5.html?flag='.$all_flags['f_type'].'&amp;status=1">'.$all_flags['txt_lang'].'</a></li>';
            }
            
            foreach($donnees_tags AS $all_tags)
                $list_tags[$all_tags['t_id_news']][] = '<li class="ntag"><a href="search.html?s='.$all_tags['rewrite'].'">'.$all_tags['t_tag'].'</a></li>';
        }
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_news / Nw::$pref['nb_news_redac'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, 'news-70.html');
        
        // On recherche toutes les news en rédaction
        inc_lib('news/get_list_news');
        inc_lib('news/can_edit_news');
        $list_dn_news = get_list_news('n_etat = 2', 'n_date DESC', $page, Nw::$pref['nb_news_redac']);
        $nbr_news = 0;
        
        // On affiche toutes les news en rédaction
        foreach($list_dn_news AS $donnees_news)
        {
            $flags_html = '';
            
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
                
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                
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
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                'NBR_VERSIONS'  => ($donnees_news['n_nb_versions'] > 1) ? '<a href="news-16-'.$donnees_news['n_id'].'.html">'.sprintf(Nw::$lang['news']['nbr_versions_news'], $donnees_news['n_nb_versions']).'</a>' : Nw::$lang['news']['none_versions'],
                'COLOR'         => ($nbr_news%2),
                
                'VOTES'         => $donnees_news['n_nb_votes'],
                'VOTES_NEG'     => $donnees_news['n_nb_votes_neg'],
                'HAS_VOTED'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
                
                'TAGS'          => $tags_html,
                'FLAGS'         => $flags_html,
                'FLAGS_FAV'     => (bool) (in_array($donnees_news['n_id'], $array_favoris)),
                
                'EDIT'          => can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat']),
                'DELETE'        => (($donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id'] && Nw::$droits['can_delete_mynews']) || Nw::$droits['can_delete_news']) ? true : false,
            ) );
            
            ++$nbr_news;
        }
        
        
        /**
        *   Bientot en ligne 
        **/
        $nb_promus = 0;
        inc_lib('news/get_list_news_light');
        $nbr_votes_promus = round(Nw::$pref['nb_votes_valid_news']/2);
        $presque_promus = get_list_news_light('n_etat = 2 AND n_nb_votes >= '.$nbr_votes_promus, 'n_nb_votes DESC, n_date DESC', 1, 7);
        
        foreach($presque_promus AS $donnees_news)
        {
            Nw::$tpl->setBlock('pp', array(
                'ID'            => $donnees_news['n_id'],
                'TITRE'         => $donnees_news['n_titre'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                'CAT_REWRITE'   => $donnees_news['c_rewrite'],
                
                'AUTEUR'        => $donnees_news['u_pseudo'],
                'AUTEUR_ID'     => $donnees_news['u_id'],
                'AUTEUR_ALIAS'  => $donnees_news['u_alias'],
                'AUTEUR_AVATAR' => $donnees_news['u_avatar'],
                
                'DATE'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
                
                'HAS_VOTED'     => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
                'NBR_VOTES'     => $donnees_news['n_nb_votes'],
                'NBR_COMS'      => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
                'NBR_VERSIONS'  => ($donnees_news['n_nb_versions'] > 1) ? '<a href="news-16-'.$donnees_news['n_id'].'.html">'.sprintf(Nw::$lang['news']['nbr_versions_news'], $donnees_news['n_nb_versions']).'</a>' : Nw::$lang['news']['none_versions'],
            ));
            ++$nb_promus;
        }
        
        /**
        *   Derniers commentaires
        **/
        inc_lib('bbcode/clearer');
        inc_lib('news/get_list_last_cmt');
        $last_comments = get_list_last_cmt(0, 'com.c_date DESC', 1, 5);
        
        foreach($last_comments AS $donnees_cmt)
        {
            $content_cmt = CoupeChar(clearer($donnees_cmt['c_texte'], 0), '...', 150);
            
            Nw::$tpl->setBlock('lc', array(
                'ID'            => $donnees_cmt['n_id'],
                'ID_COMMENT'    => $donnees_cmt['c_id'],
                'TITRE'         => $donnees_cmt['n_titre'],
                'REWRITE'       => rewrite($donnees_cmt['n_titre']),
                'CAT_REWRITE'   => $donnees_cmt['c_rewrite'],
                
                'AUTEUR'        => $donnees_cmt['u_pseudo'],
                'AUTEUR_ID'     => $donnees_cmt['u_id'],
                'AUTEUR_ALIAS'  => $donnees_cmt['u_alias'],
                'AUTEUR_AVATAR' => $donnees_cmt['u_avatar'],
                
                'DATE'          => date_sql($donnees_cmt['date'], $donnees_cmt['heures_date'], $donnees_cmt['jours_date']),
                'EXTRAIT'       => $content_cmt,
            ));
        }
        
        
        /**
        *   Top voters
        **/
        inc_lib('news/get_list_top_voters');
        $top_voters = get_list_top_voters(5);
        
        foreach($top_voters AS $donnees)
        {
            $text_int_votes = ($donnees['s_nb_votes'] > 1) ? Nw::$lang['news']['text_nbr_votes'] : Nw::$lang['news']['text_nbr_vote'];
            
            Nw::$tpl->setBlock('tv', array(
                'NBR_VOTES'     => sprintf($text_int_votes, $donnees['s_nb_votes']),
                
                'AUTEUR'        => $donnees['u_pseudo'],
                'AUTEUR_ID'     => $donnees['u_id'],
                'AUTEUR_ALIAS'  => $donnees['u_alias'],
                'AUTEUR_AVATAR' => $donnees['u_avatar'],
            ));
        }
        
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'news-80%s.html'),
            'NB_VOT_VALID'  => Nw::$pref['nb_votes_valid_news'],
            'NB_PROMUS'     => $nb_promus,
        ));
        
    }
}

/*  *EOF*   */

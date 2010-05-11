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
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: news-70.html');
        }
        
        //exit('<pre>'.print_r($_GET, true).'</pre>');
        
        $id_version_load = 0;
        $txt_other_vrs = '';
        $droit_edit_news = false;
        $droit_delete_news = false;
        $author_has_news = false;
        
        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }

        inc_lib('news/vrs_exists');
        if (!empty($_GET['vrs']) && is_numeric($_GET['vrs']) && vrs_exists($_GET['id'], $_GET['vrs']) == true) 
        {
            inc_lib('news/get_info_vrs');
            $id_version_load = $_GET['vrs'];
            $donnees_vrs = get_info_vrs($_GET['vrs']);
            $txt_other_vrs = sprintf(Nw::$lang['news']['view_news_vrs_archived'], $donnees_vrs['v_number']);
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id'], $id_version_load);
        $rewrite_news = rewrite($donnees_news['n_titre']);
        
        // Ancien permalien des news qui redirige vers le nouveau (nw.com/news-10-1-ma-news.html -> nw.com/politique/ma-news-1/)
        if (strpos($_SERVER['REQUEST_URI'], 'news-10-'.$_GET['id']) !== false)
        {
            header('Status: 301 Moved Permanently', false, 301);
            header('Location: '.Nw::$site_url.$donnees_news['c_rewrite'].'/'.$rewrite_news.'-'.$_GET['id'].'/');
            exit();
        }
        
        // Ce membre a le droit d'éditer la news ?
        if ($donnees_news['n_etat'] != 3 && !is_logged_in()) {
            redir(Nw::$lang['news']['not_view_news_perm'], false, Nw::$site_url);
        }
        
        $this->set_title($donnees_news['n_titre'].' | '.$donnees_news['c_nom']);
        $this->set_tpl('news/view.html');
        $this->add_css('code.css');
        $this->add_css('forms.css');
        $this->base_enabled(true);
        $this->add_wid_in_content('view_news.'.$_GET['id']);
        
        if ($donnees_news['n_etat'] == 2)
            $this->add_js('news.attente.js');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']           => array('news-70.html'),
            $donnees_news['c_nom']                      => array($donnees_news['c_rewrite'].'/'),
            $donnees_news['n_titre']                    => array($donnees_news['c_rewrite'].'/'.$rewrite_news.'-'.$_GET['id'].'/'),
            Nw::$lang['news']['view_fil_ariane']        => array(''),
        ));
        
        /**
        *   Liste des contributeurs
        **/
        inc_lib('news/get_list_contrib');
        $list_contribs = get_list_contrib($_GET['id'], $donnees_news['n_id_auteur'], 'v_mineure = 0');
        
        foreach($list_contribs AS $donnees_contribs)
        {
            Nw::$tpl->setBlock('ctb', array(
                'MEMBRE_ID'         => $donnees_contribs['u_id'],
                'MEMBRE_PSEUDO'     => $donnees_contribs['u_pseudo'],
                'MEMBRE_ALIAS'      => $donnees_contribs['u_alias'],
                'MEMBRE_AVATAR'     => $donnees_contribs['u_avatar'],
                'VERSIONS'          => sprintf(Nw::$lang['news']['nbr_contrib'], $donnees_contribs['nb_version'], (($donnees_contribs['nb_version'] > 1) ? Nw::$lang['news']['add_s_versions'] : '')),
            ));
        }
        
        
        /**
        *   News de l'auteur
        **/
        inc_lib('news/get_list_news_byauthor');
        $news_author = get_list_news_byauthor($donnees_news['n_id_auteur'], array(), 3);
        
        foreach($news_author AS $donnees_author)
        {
            $author_has_news = true;
            
            Nw::$tpl->setBlock('nauthor', array(
                'ID'            => $donnees_author['n_id'],
                'TITRE'         => $donnees_author['n_titre'],
                'CAT_REWRITE'   => $donnees_author['c_rewrite'],
                'REWRITE'       => rewrite($donnees_author['n_titre']),
            ));
        }
        
        
        if ($donnees_news['n_etat'] == 2)
        {
            inc_lib('news/get_list_votes_news');
            
            $recents_votes = get_list_votes_news('v_id_news = '.intval($_GET['id']), 'v_date DESC', 1, 20);
            
            foreach($recents_votes AS $donnees_vote)
            {
                Nw::$tpl->setBlock('rvotes', array(
                    'DATE'          => date_sql($donnees_vote['date'], $donnees_vote['heures_date'], $donnees_vote['jours_date']),
                    'AUTEUR'        => $donnees_vote['u_pseudo'],
                    'AUTEUR_ID'     => $donnees_vote['u_id'],
                    'AUTEUR_ALIAS'  => $donnees_vote['u_alias'],
                    'TYPE'          => $donnees_vote['v_type'],
                ));
            }
        }
        
        
        /**
        *   Liste des commentaires
        **/
        
        if ($donnees_news['n_nbr_coms'] > 0)
        {
            // Pagination
            $page = ( isset( $_GET['page'] ) ) ? intval($_GET['page']) : 1;
            $nombreDePages = ceil($donnees_news['n_nbr_coms'] / Nw::$pref['nb_cmts_page']);
            
            // On vérifie bien que la page existe
            if ($nombreDePages > 0 && $page > $nombreDePages)
                redir(Nw::$lang['common']['pg_not_exist'], false, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/');
            
            // L'utilisateur demande un commentaire particulier, on le redirige sur la bonne page
            if (!empty($_GET['id2']) && is_numeric($_GET['id2']))
            {
                inc_lib('news/count_cmt_before_idc');
                $nbr_cmts_before = count_cmt_before_idc($_GET['id'], $_GET['id2']);
                $page = ceil($nbr_cmts_before / Nw::$pref['nb_cmts_page']);
            }

            inc_lib('news/get_list_cmt_news');
            $list_cmts = get_list_cmt_news($_GET['id'], 'c_date ASC', $page, Nw::$pref['nb_cmts_page']);
            $com_cours = 0;
            
            // Affichage de tous les commentaires de la page
            foreach($list_cmts AS $donnees_cmts)
            {
                ++$com_cours;
                $droit_edit = false;
                $droit_delete = false;
                
                if(is_logged_in()) 
                {
                    $droit_edit = (bool) (Nw::$droits['can_edit_my_comments'] && $donnees_cmts['u_id'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_edit_all_comments'];
                    $droit_delete = (bool) (Nw::$droits['can_del_my_comments'] && $donnees_cmts['u_id'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_del_all_comments'];
                }
                
                $date_cmt = date_sql($donnees_cmts['date'], $donnees_cmts['heures_date'], $donnees_cmts['jours_date']);
                $masque_motif = '';
                
                if($donnees_cmts['c_masque'])
                {
                    $date_cmt = sprintf(Nw::$lang['news']['del_cmt_with_reason'], strtolower(date_sql($donnees_cmts['date'], $donnees_cmts['heures_date'], $donnees_cmts['jours_date'])));
                    
                    if (!empty($donnees_cmts['c_masque_raison']))
                        $masque_motif = ' ('.sprintf(Nw::$lang['news']['motif_delete_cmt'], $donnees_cmts['c_masque_raison']).')';
                }
                
                Nw::$tpl->setBlock('cmt', array(
                    'ID'            => $donnees_cmts['c_id'],
                    'NUM'           => (($page-1)*Nw::$pref['nb_cmts_page'])+$com_cours,
                    
                    'DATE'          => $date_cmt,
                    
                    'AVATAR'        => $donnees_cmts['u_avatar'],
                    'LANG_AVATAR'   => sprintf(Nw::$lang['news']['lang_avatar'], $donnees_cmts['u_pseudo']),
                    
                    'AUTEUR'        => $donnees_cmts['u_pseudo'],
                    'AUTEUR_ID'     => $donnees_cmts['u_id'],
                    'AUTEUR_ALIAS'  => $donnees_cmts['u_alias'],
                    
                    'TEXTE'         => $donnees_cmts['c_texte'],
                    'PLUSSOIE'      => $donnees_cmts['c_plussoie'],
                    
                    'GRP_TITRE'     => $donnees_cmts['g_titre'],
                    'GRP_ICON'      => $donnees_cmts['g_icone'],
                    'IP'            => long2ip($donnees_cmts['c_ip']),
                    
                    'MASQUE'        => $donnees_cmts['c_masque'],
                    'MASQUE_MOTIF'  => $masque_motif,
                    
                    'EDIT'          => $droit_edit,
                    'DELETE'        => $droit_delete,
                ));
            }
        }
        
        if ($donnees_news['n_nb_src'] > 0)
        {
            inc_lib('news/get_list_src');
            $donnees_src = get_list_src($_GET['id']);
            
            foreach($donnees_src AS $donnees)
            {
                Nw::$tpl->setBlock('src', array(
                    'LINK'          => $donnees['src_url'],
                    'MEDIA'         => $donnees['src_media'],
                ));
            }
        }
        
        if(is_logged_in()) 
        {
            if(($donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id'] && Nw::$droits['can_delete_mynews']) || Nw::$droits['can_delete_news']) 
                $droit_delete_news = true;

            inc_lib('news/can_edit_news');
            $droit_edit_news = can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat']);
        }
        
        // Tags de la news
        inc_lib('news/get_list_tags_news');
        $list_tags_metas = array();
        $list_dn_tags = get_list_tags_news(($donnees_news['n_etat'] != 3) ? 0 : 3, $_GET['id']);
        
        foreach($list_dn_tags AS $donnees_tags)
            $list_tags_metas[] = $donnees_tags['t_tag'];

        inc_lib('news/get_list_news_related');
        inc_lib('news/get_list_flags_news');
        inc_lib('news/has_voted_news');
        inc_lib('bbcode/parse');
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'ETAT'              => $donnees_news['n_etat'],
            'CAT_ID'            => $donnees_news['c_id'],
            'CAT_TITRE'         => $donnees_news['c_nom'],
            'CAT_REWRITE'       => $donnees_news['c_rewrite'],
            'REWRITE'           => $rewrite_news,
            
            'AUTEUR'            => $donnees_news['u_pseudo'],
            'AUTEUR_ID'         => $donnees_news['u_id'],
            'AUTEUR_BIO'        => CoupeChar($donnees_news['u_bio'], '...', 300),
            'AUTEUR_ALIAS'      => $donnees_news['u_alias'],
            'AUTEUR_AVATAR'     => $donnees_news['u_avatar'],
            'AUTEUR_HASN'       => $author_has_news,
            
            'DATE'              => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
            
            'NBR_COMS'          => sprintf(Nw::$lang['news']['nbr_comments_news'], $donnees_news['n_nbr_coms'], ($donnees_news['n_nbr_coms']>1) ? Nw::$lang['news']['add_s_comments'] : ''),
            'COMS'              => $donnees_news['n_nbr_coms'],
            
            'NB_VOT_VALID'      => Nw::$pref['nb_votes_valid_news'],
            'VOTES'             => $donnees_news['n_nb_votes'],
            'VOTES_NEG'         => $donnees_news['n_nb_votes_neg'],
            
            'VERSIONS'          => $donnees_news['n_nb_versions'],
            
            
            'IMAGE_ID'          => $donnees_news['i_id'],
            'IMAGE_NOM'         => $donnees_news['i_nom'],
            
            'NB_SRC'            => $donnees_news['n_nb_src'],
            
            'TITRE'             => $donnees_news['n_titre'],
            'CONTENU'           => parse_widgets($donnees_news['v_texte']),
            'VRS_LOAD'          => $id_version_load,
            'TXT_OTHER_VRS'     => $txt_other_vrs,
            
            
            'DRT_EDIT'          => $droit_edit_news,
            'DRT_DELETE'        => $droit_delete_news,
            
            'RELATED'           => get_list_news_related($_GET['id'], 5, $donnees_news['n_etat']),
            'TAGS'              => $list_dn_tags,
            'FLAGS'             => (is_logged_in()) ? get_list_flags_news($donnees_news['n_etat'], $_GET['id']) : array(),
            'FLAGS_FAV'         => (is_logged_in()) ? $donnees_news['f_type'] : 0,
            'HAS_VOTED'         => (is_logged_in()) ? $donnees_news['v_id_membre'] : 0,
            
            'LINK_NB_CONTRIB'   => sprintf(Nw::$lang['news']['edit_nb_contrib'], $donnees_news['n_nb_versions']),
            'NB_VERSIONS'       => sprintf(($donnees_news['n_nb_versions'] > 1) ? Nw::$lang['news']['nb_versions'] : Nw::$lang['news']['nb_version'], $donnees_news['n_nb_versions']),
            'LIST_PG'           => ($donnees_news['n_nbr_coms'] > 0) ? list_pg($nombreDePages, $page, $donnees_news['c_rewrite'].'/'.$rewrite_news.'-'.$_GET['id'].'/%s', '') : '',
            
            'DRT_COMMENT'       => (is_logged_in()) ? Nw::$droits['can_post_comment'] : false,
        ));
        
        $this->metas(array(
            'desc'      => $donnees_news['n_resume'],
            'tags'      => implode(', ', $list_tags_metas),
        ));
        
        // Màj du nombre de visualisations
        inc_lib('news/update_pg_vues');
        update_pg_vues($_GET['id']);
    }
}

/*  *EOF*   */

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
        
        if (!Nw::$droits['can_post_comment']) {
            redir(Nw::$lang['news']['acn_droit_comment'], false, './');
        }
        
        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: ./');
        }

        inc_lib('news/news_exists');
        $edit = false;      
        if (news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        $this->set_title(sprintf(Nw::$lang['news']['title_cmt_news'], $donnees_news['n_titre']));
        $this->set_tpl('news/post_cmt.html');
        $this->add_css('forms.css');
        $this->add_css('code.css');
        $this->add_js('ajax.js');
        $this->add_js('write.js');
        $this->add_form('contenu');
        
        inc_lib('bbcode/unparse');
        inc_lib('bbcode/parse');
        
        $content_defaut_cmt = '';
        $title_last_cmts = '';
        $edition_invisible = false;
        $last_item_fa = Nw::$lang['news']['nv_cmt_fil_ariane'];
        $id2 = 0;
        
        $donnees_antiflood = array();
        
        /**
        *   Édition de commentaire
        **/
        if (!empty($_GET['id2']) && is_numeric($_GET['id2']))
        {
            // Le commentaire existe-t-il ?
            inc_lib('news/cmt_news_exists');
            if (cmt_news_exists($_GET['id2']) == true)
            {
                inc_lib('news/get_info_cmt_news');
                $donnees_cmt = get_info_cmt_news($_GET['id2']);
                
                // Le membre a le droit d'éditer le commentaire?
                if((Nw::$droits['can_edit_my_comments'] && $donnees_cmt['c_id_membre'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_edit_all_comments'])
                {
                    if (Nw::$droits['edit_hidden_comments'])
                        $edition_invisible = true;

                    $edit = true;
                    $content_defaut_cmt = unparse($donnees_cmt['c_texte']);
                    $id2 = $_GET['id2'];
                    $last_item_fa = Nw::$lang['news']['update_comment'];
                }
                else
                    redir(Nw::$lang['news']['no_drt_edit_cmt'], false, 'news-10-'.$_GET['id'].'-'.$_GET['id2'].'.html#c'.$_GET['id2']);
            }
            else
                redir(Nw::$lang['news']['cmt_no_exist'], false, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/');
        }
        
        /**
        *   Citation d'un commentaire
        **/
        if (!empty($_GET['qid']) && is_numeric($_GET['qid']))
        {
            // Le commentaire existe-t-il ?
            inc_lib('news/cmt_news_exists');
            if (cmt_news_exists($_GET['qid']) == true)
            {
                inc_lib('news/get_info_cmt_news');
                $donnees_cmt = get_info_cmt_news($_GET['qid']);
                $content_defaut_cmt = '<citation auteur="'.$donnees_cmt['u_pseudo'].'">'.unparse($donnees_cmt['c_texte']).'</citation>';
            }
        }
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']       => array('news-70.html'),
            $donnees_news['c_nom']                  => array($donnees_news['c_rewrite'].'/'),
            $donnees_news['n_titre']                => array($donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/'),
            $last_item_fa                           => array(''),
        ));
        
        // On affiche les x derniers commentaires
        if (!$edit)
        {
            inc_lib('news/get_list_cmt_news');
            $page = 1;
            $list_cmts = get_list_cmt_news($_GET['id'], 'c_date DESC', $page, Nw::$pref['nb_cmts_page']);
            $com_cours = 0;
            $title_last_cmts = sprintf(Nw::$lang['news']['title_last_cmts'], Nw::$pref['nb_cmts_page']);
            
            // Affichage de tous les commentaires de la page
            foreach($list_cmts AS $donnees_cmts)
            {
                if (count($donnees_antiflood) == 0)
                    $donnees_antiflood = array('c_id' => $donnees_cmts['c_id'], 'c_id_membre' => $donnees_cmts['u_id'], 'c_texte' => $donnees_cmts['c_texte']);
                
                ++$com_cours;
                $droit_edit = false;
                $droit_delete = false;
                
                if (is_logged_in()) 
                {
                    $droit_edit = (bool) (Nw::$droits['can_edit_my_comments'] && $donnees_cmts['u_id'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_edit_all_comments'];
                    $droit_delete = (bool) (Nw::$droits['can_del_my_comments'] && $donnees_cmts['u_id'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_del_all_comments'];
                }
                
                $date_cmt = date_sql($donnees_cmts['date'], $donnees_cmts['heures_date'], $donnees_cmts['jours_date']);
                $masque_motif = '';
                
                if ($donnees_cmts['c_masque'])
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
        
        // Formulaire soumis
        if (isset($_POST['submit']))
        {
            $array_post = array(    
                'contenu'           => $_POST['contenu'],
            );
            
            // Les champs titre & contenu ne sont pas vides
            if (!multi_empty(trim($_POST['contenu'])))
            {
                /**
                *   Edition d'un commentaire
                **/
                if ($edit)
                {
                    inc_lib('news/edit_cmt_news');
                    edit_cmt_news($_GET['id'], $_GET['id2']);
                    redir(Nw::$lang['news']['msg_edit_cmt'], true, 'news-10-'.$_GET['id'].'-'.$_GET['id2'].'.html#c'.$_GET['id2']);
                }
                /**
                *   Ajout d'un nouveau commentaire
                **/
                else
                {
                    $contenu_cmt = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($_POST['contenu']))));
                    
                    // Si le dernier commentaire est exactement le même que celui que le membre est en train de poster : on affiche un message d'erreur
                    if (count($donnees_antiflood) > 0 && $donnees_antiflood['c_texte'] == $contenu_cmt && $donnees_antiflood['c_id_membre'] == Nw::$dn_mbr['u_id'])
                        redir(Nw::$lang['news']['antispam_post_cmt'], false, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/comment/'.$donnees_antiflood['c_id'].'/#c'.$donnees_antiflood['c_id']);
                        
                    // On édite la news
                    inc_lib('news/add_cmt_news');
                    $id_new_comment = add_cmt_news($_GET['id']);
                    redir(Nw::$lang['news']['msg_new_cmt'], true, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/comment/'.$id_new_comment.'/#c'.$id_new_comment);
                }
            }
        }
        
        
        
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'ID2'               => $id2,
            'TITRE'             => $donnees_news['n_titre'],
            'REWRITE'           => rewrite($donnees_news['n_titre']),
            'CAT_REWRITE'       => $donnees_news['c_rewrite'],
            'ID_CAT'            => $donnees_news['n_id_cat'],
            'NB_COMS'           => $donnees_news['n_nbr_coms'],
            'LST_CMTS'          => $title_last_cmts,
            'BAL_CHAMP'         => 'contenu',
            'EDIT'              => $edit,
            'EDIT_HIDDEN'       => $edition_invisible,
        ));

        
        // On affiche le template
        display_form(array( 
                'contenu'           => $content_defaut_cmt,
            )
        );
    }
}

/*  *EOF*   */

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

        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['id2'])) {
            header('Location: ./');
        }
        
        // News existe pas
        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }
        
        // Commentaire existe pas
        inc_lib('news/get_info_cmt_news');
        inc_lib('news/get_info_news');

        $donnees_cmt = get_info_cmt_news($_GET['id2']);
        if(empty($donnees_cmt))
            redir(Nw::$lang['news']['cmt_no_exist'], false, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/');
        $donnees_news = get_info_news($_GET['id']);
        
        $this->set_title(sprintf(Nw::$lang['news']['title_del_cmt_news'], $donnees_news['n_titre']));
        $this->set_tpl('news/delete_cmt.html');
        $this->add_css('forms.css');

        if((Nw::$droits['can_del_my_comments'] && $donnees_cmt['c_id_membre'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_del_all_comments'])
        {
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['news']['news_section']               => array('news-70.html'),
                $donnees_news['c_nom']                          => array($donnees_news['c_rewrite'].'/'),
                $donnees_news['n_titre']                        => array($donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/'),
                Nw::$lang['news']['del_cmt_news']               => array(''),
            ));
            
            // Formulaire soumis
            if (isset($_POST['submit']))
            {
                inc_lib('news/delete_cmt_news');
                delete_cmt_news($_GET['id'], $_GET['id2']);
                redir(Nw::$lang['news']['cmt_deleted'], true, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/');
            }
            
            if (Nw::$droits['can_del_all_comments'])
            {
                $phrase_del = sprintf(Nw::$lang['news']['phrase_del_cmt_modos'], $_GET['id'], $_GET['id2'], $_GET['id2'], $donnees_news['n_titre']);
            }
            else
            {
                $phrase_del = sprintf(Nw::$lang['news']['phrase_del_cmt'], $_GET['id'], $_GET['id2'], $_GET['id2'], $donnees_news['n_titre']);
            }
            
            Nw::$tpl->set(array(
                'ID'                => $_GET['id'],
                'ID2'               => $_GET['id2'],
                'TITRE'             => $donnees_news['n_titre'],
                'PHRASE_DEL'        => $phrase_del,
                'IS_MODO'           => Nw::$droits['can_del_all_comments'],
            ));
        }
        else
            redir(Nw::$lang['news']['no_drt_del_cmt'], false, 'news-10-'.$_GET['id'].'-'.$_GET['id2'].'.html#c'.$_GET['id2']);
    }
}

/*  *EOF*   */

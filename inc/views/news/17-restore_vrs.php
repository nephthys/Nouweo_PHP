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
        if (empty($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['id2']) || !is_numeric($_GET['id2'])) {
            header('Location: ./');
        }
        
        // Cette news existe vraiment ?
        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, './');
        }

        inc_lib('news/vrs_exists');
        if (vrs_exists($_GET['id'], $_GET['id2']) == false) {
            redir(Nw::$lang['news']['version_not_exist'], false, 'news-16-'.$_GET['id'].'.html');
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        
        // Le membre a le droit de restaurer une version de news
        if((Nw::$droits['can_change_version_my_news'] && $donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id']) || Nw::$droits['can_change_version_all_news'])
        {
            // La version actuelle n'est pas la même que celle que l'on veut restaurer..
            if($donnees_news['n_last_version'] != $_GET['id2'])
            {
                inc_lib('news/restore_vrs');
                restore_vrs($_GET['id'], $_GET['id2']);
                redir(Nw::$lang['news']['vrs_restored'], true, 'news-16-'.$_GET['id'].'.html');
            }
            else
                redir(Nw::$lang['news']['error_already_restored'], false, 'news-16-'.$_GET['id'].'.html');
        }
        else
            redir(Nw::$lang['news']['error_restore_vrs'], false, 'news-16-'.$_GET['id'].'.html');
    }
}

/*  *EOF*   */

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
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: news-70.html');
        }
        
        // Cette news existe vraiment ?
        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        
        // Ce n'est pas l'auteur de la news, il ne peut la proposer
        if($donnees_news['n_id_auteur'] != Nw::$dn_mbr['u_id']) {
            redir(Nw::$lang['news']['dont_propose_news'], false, 'news-70.html');
        }
    
        // La news a déjà été proposée
        if($donnees_news['n_etat'] == 2) {
            redir(Nw::$lang['news']['news_already_attente'], false, 'news-80.html');
        }
        
        // Proposition de la news
        inc_lib('news/propose_news_votes');
        propose_news_votes($_GET['id']);
        redir(Nw::$lang['news']['msg_news_attente'], true, 'news-80.html');
    }
}

/*  *EOF*   */

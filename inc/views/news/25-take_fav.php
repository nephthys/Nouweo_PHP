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
        
        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: ./');
        }
        
        // Cette news existe vraiment ?
        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, './');
        }
        
        // Pour rediriger le visiteur d'où il est venu
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.'news-25-'.$_GET['id'].'.html') === false) {
            $_SESSION['nw_referer_edit'] = $_SERVER['HTTP_REFERER'];
        }
        
        $link_redir = (!empty($_SESSION['nw_referer_edit'])) ? $_SESSION['nw_referer_edit'] : 'news-10-'.intval($_GET['id']).'.html';   

        inc_lib('news/get_info_news');
        inc_lib('news/manage_fav');
        $donnees_news = get_info_news($_GET['id']);
        $response = manage_fav($_GET['id']);
        
        // Nouveau favoris
        if($response == 1) 
            $text_redir = Nw::$lang['news']['news_favorite_ok'];
        // Suppression des favoris
        elseif($response == 2)
            $text_redir = Nw::$lang['news']['news_defavorite_ok'];
        
        redir( $text_redir, true, $link_redir );
    }
}

/*  *EOF*   */

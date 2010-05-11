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

        // Le commentaire n'existe pas
        inc_lib('news/cmt_news_exists');
        if (cmt_news_exists($_GET['id']) == false) {
            redir(Nw::$lang['news']['cmt_not_exist'], false, './');
        }

        inc_lib('news/get_info_cmt_news');
        inc_lib('news/add_vote_cmt');
        $donnees_cmt = get_info_cmt_news($_GET['id']);
        $response = add_vote_cmt($_GET['id']);
        
        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($donnees_cmt['c_id_news']);
        $rewrite_news = $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$donnees_news['n_id'].'/';
        
        // Pour rediriger le visiteur d'où il est venu
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.$rewrite_news) === false) {
            $_SESSION['nw_referer_edit'] = $_SERVER['HTTP_REFERER'];
        }

        // Vote bien ajouté
        if ($response)
            $text_redir = Nw::$lang['news']['vote_cmt_ok'];
        else
            $text_redir = Nw::$lang['news']['vote_cmt_pasok'];
            
        $link_redir = (!empty($_SESSION['nw_referer_edit'])) ? $_SESSION['nw_referer_edit'] : $rewrite_news;    
        redir( $text_redir, true, $link_redir );
    }
}

/*  *EOF*   */

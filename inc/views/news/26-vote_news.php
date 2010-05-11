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
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.'news-26-'.$_GET['id'].'.html') === false) {
            $_SESSION['nw_referer_edit'] = $_SERVER['HTTP_REFERER'];
        }
        
        $type_vote = true;
        
        // S'il s'agit d'un vote négatif (pour les news en attente)
        if (isset($_GET['id2']) && $_GET['id2'] == 0)
            $type_vote = false;
        
        inc_lib('news/get_info_news');
        inc_lib('news/add_vote_news');
        $donnees_news = get_info_news($_GET['id']);
        $response = add_vote_news($_GET['id'], $type_vote);
        
        $vote_redir_defaut = ($donnees_news['n_etat'] == 2) ? 'news-80.html' : 'news-10-'.intval($_GET['id']).'.html';
        
        // Vote bien ajouté
        if ($response[0])
        {
            // On publie la news automatiquement quand le nbr de votes nécessaires a été atteint
            if ($donnees_news['n_etat'] == 2 && $response[1] >= Nw::$pref['nb_votes_valid_news'])
            {
                if ($type_vote)
                {
                    inc_lib('news/valid_news_direct');
                    valid_news_direct($_GET['id']);
                    $text_redir = sprintf(Nw::$lang['news']['news_publiee_byvotes'], $response[1]);
                    $vote_redir_defaut = './';
                }
                else
                {
                    inc_lib('news/archive_news');
                    archive_news($_GET['id']);
                    $text_redir = sprintf(Nw::$lang['news']['news_archivee_byvotes'], $response[1]);
                    $vote_redir_defaut = './';
                }
            }
            else
                $text_redir = Nw::$lang['news']['vote_news_ok'];
        }
        else
            $text_redir = Nw::$lang['news']['vote_news_pasok'];
            
        $link_redir = (!empty($_SESSION['nw_referer_edit'])) ? $_SESSION['nw_referer_edit'] : $vote_redir_defaut;   
        
        redir( $text_redir, true, $link_redir );
    }
}

/*  *EOF*   */

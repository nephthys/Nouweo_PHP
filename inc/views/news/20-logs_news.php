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
        inc_lib('news/news_exists');
        $count_news_existe = news_exists($_GET['id']);
        
        if ($count_news_existe == false && (is_logged_in() && !check_auth('view_histo_all_news'))) {
            redir(Nw::$lang['news']['news_not_exist'], false, './');
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        
        $this->set_title(Nw::$lang['news']['historique_news'].' | '.$donnees_news['n_titre']);
        $this->set_tpl('news/log_news.html');
        $this->add_css('code.css');
        
        // Fil ariane
        if ($count_news_existe)
        {
            $this->set_filAriane(array(
                Nw::$lang['news']['news_section']       => array('news-70.html'),
                $donnees_news['c_nom']                  => array($donnees_news['c_rewrite'].'/'),
                $donnees_news['n_titre']                => array($donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/'),
                Nw::$lang['news']['historique_news']    => array(''),
            ));
        }
        else
        {
            $this->set_filAriane(array(
                Nw::$lang['news']['news_section']       => array('news-70.html'),
                Nw::$lang['news']['historique_news']    => array(''),
            ));
        }
        
        /**
        *   Affichage du logo
        **/
        inc_lib('news/get_news_logs');
        $donnees_logs = get_news_logs('l_id_news = '.intval($_GET['id']), 'l_date DESC');
        
        foreach($donnees_logs AS $donnees)
        {
            Nw::$tpl->setBlock('log', array(
                'ACTION'        => $donnees['l_action'],
                'ACTION_LOG'    => (isset(Nw::$lang['news']['log_news_'.$donnees['l_action']])) ? Nw::$lang['news']['log_news_'.$donnees['l_action']] : '',
                'TEXTE'         => nl2br($donnees['l_texte']),
                'TITRE'         => $donnees['l_titre'],
                
                'DATE'          => date_sql($donnees['date'], $donnees['heures_date'], $donnees['jours_date']),
                
                'AUTEUR'        => $donnees['u_pseudo'],
                'AUTEUR_ID'     => $donnees['u_id'],
                'AUTEUR_AVATAR' => $donnees['u_avatar'],
                'AUTEUR_ALIAS'  => $donnees['u_alias'],
                
                'IP'            => long2ip($donnees['l_ip']),
            ) );
        }
        
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'TITRE'             => $donnees_news['n_titre'],
        ));
    }
}

/*  *EOF*   */

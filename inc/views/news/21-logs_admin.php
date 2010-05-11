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
        if (!is_logged_in() && !check_auth('view_histo_all_news')) {
            header('Location: ./');
        }

        $this->set_title(Nw::$lang['news']['historiques_news']);
        $this->set_tpl('news/log_admin.html');
        $this->add_css('code.css');
        
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']       => array('news-70.html'),
            Nw::$lang['news']['historiques_news']   => array(''),
        ));
        
        $get_param = '';
        $param_tpl = '';
        
        if (!empty($_GET['t']))
        {
            $get_param = 'l_titre LIKE "%'.insertBD(urldecode($_GET['t'])).'%" OR l_texte LIKE "%'.insertBD(urldecode($_GET['t'])).'%"';
            $param_tpl = htmlspecialchars($_GET['t']);
        }
        
        inc_lib('news/count_news_logs');
        $nombre_logs = count_news_logs($get_param);
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_logs / Nw::$pref['nb_logs_admin'] );
        
        // On vérifie que la page existe bien
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, 'news-21.html?t='.$param_tpl);
        
        /**
        *   Affichage du logo
        **/
        inc_lib('news/get_news_logs');
        $donnees_logs = get_news_logs($get_param, 'l_date DESC', $page, Nw::$pref['nb_logs_admin']);
        
        foreach($donnees_logs AS $donnees)
        {
            Nw::$tpl->setBlock('log', array(
                'ACTION'        => $donnees['l_action'],
                'ACTION_LOG'    => (isset(Nw::$lang['news']['log_news_'.$donnees['l_action']])) ? Nw::$lang['news']['log_news_'.$donnees['l_action']] : '',
                'TEXTE'         => nl2br($donnees['l_texte']),
                
                
                'DATE'          => date_sql($donnees['date'], $donnees['heures_date'], $donnees['jours_date']),
                
                'AUTEUR'        => $donnees['u_pseudo'],
                'AUTEUR_ID'     => $donnees['u_id'],
                'AUTEUR_AVATAR' => $donnees['u_avatar'],
                'AUTEUR_ALIAS'  => $donnees['u_alias'],
                
                'NEWS_ID'       => $donnees['l_id_news'],
                'NEWS_TITRE'    => $donnees['n_titre'],
                'TITRE_ACTU'    => $donnees['l_titre'],
                
                'IP'            => long2ip($donnees['l_ip']),
            ) );
        }
        
        Nw::$tpl->set(array(
            'TITRE'         => urldecode($param_tpl),
            'LIST_PG'       => list_pg($nombreDePages, $page, 'news-21%s.html?t='.$param_tpl),
        ));
    }
}

/*  *EOF*   */

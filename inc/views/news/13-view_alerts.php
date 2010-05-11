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
        if(!is_logged_in() || !check_auth('solve_alertes'))
            redir(Nw::$lang['news']['error_cant_solve_alerts'], false, './');

        inc_lib('news/get_list_alerts_news');

        //Si on veut trier selon le statut
        if(isset($_GET['solved']) && in_array($_GET['solved'], array(0, 1)))
        {
            $solved = $_GET['solved'];
            Nw::$tpl->set('SOLVED', $solved);
        }
        else
        {
            $solved = null;
            Nw::$tpl->set('SOLVED', -1);
        }
        

        //Si on veut voir les alertes d'une news précise
        if(!empty($_GET['id']) && is_numeric($_GET['id']))
        {
            inc_lib('news/news_exists');
            if (news_exists($_GET['id']) == false)
                redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');

            inc_lib('news/get_info_news');
            $info_news = get_info_news($_GET['id']);
            $this->set_title($info_news['n_titre'].' | '.Nw::$lang['news']['alerts_list']);
            $list_alerts = get_list_alerts_news($_GET['id'], $solved);
            Nw::$tpl->set('ID_NEWS', $_GET['id']);
            Nw::$tpl->set('NEWS', $info_news['n_titre']);

            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['news']['news_section']   => array('news-70.html'),
                $info_news['c_nom']                 => array($info_news['c_rewrite'].'/'),
                $info_news['n_titre']               => array($info_news['c_rewrite'].'/'.rewrite($info_news['n_titre']).'-'.$_GET['id'].'/'),
                Nw::$lang['news']['alerts_list']    => array(''),
            ));
            
            Nw::$tpl->set(array(
                'CAT_REWRITE'       => $info_news['c_rewrite'],
                'REWRITE'           => rewrite($info_news['n_titre']),
            ));
        }
        else
        {
            $this->set_title(Nw::$lang['news']['alerts_list']);
            $list_alerts = get_list_alerts_news(null, $solved);
            Nw::$tpl->set('ID_NEWS', null);

            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['news']['news_section']   => array('news-70.html'),
                Nw::$lang['news']['alerts_list']    => array(''),
            ));
        }
        
        $this->set_tpl('news/list_alerts.html');
        $this->add_css('code.css');

        foreach($list_alerts as $a)
        {
            Nw::$tpl->setBlock('alerts', array(
                'ID'        => $a['a_id'],
                'AUTEUR'    => $a['pseudo_auteur'],
                'ALIAS'     => $a['u_alias'],
                'ADMIN'     => $a['pseudo_admin'],
                'ID_ADMIN'  => $a['a_admin'],
                'SOLVED'    => $a['a_solved'],
                'DATE'      => $a['date'],
                'RAISON'    => $a['a_texte'],
                'MOTIF'     => !empty($a['a_motif']) ? Nw::$lang['news']['motifs_list'][$a['a_motif']] : '-',
                'IP'        => long2ip($a['a_ip']),
                'ID_NEWS'   => $a['a_id_news'],
                'NEWS'      => $a['n_titre'],
                'REWRITE'   => rewrite($a['n_titre']),
                'CAT_REWRITE'   => rewrite($a['c_rewrite']),
                'SOLVED_BY' => $a['a_solved'] ? sprintf(Nw::$lang['news']['alert_solved_by'], $a['pseudo_admin']) : '',
            ));
        }
    }
}

/*  *EOF*   */

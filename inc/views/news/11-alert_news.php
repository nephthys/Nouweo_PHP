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
        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: news-70.html');
        }

        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false)
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');

        inc_lib('news/get_info_news');
        $info_news = get_info_news($_GET['id']);

        if (!is_logged_in()) {
            redir(Nw::$lang['news']['error_cant_alert'], false, './');
        }

        //Si on a envoyé des erreurs
        if(!empty($_POST['contenu']))
        {
            inc_lib('news/add_alert_news');
            add_alert_news(Nw::$dn_mbr['u_id'], $_GET['id'], $_POST['contenu'], $_POST['motif']);
            redir(Nw::$lang['news']['confirm_alert'], true, $info_news['c_rewrite'].'/'.rewrite($info_news['n_titre']).'-'.$_GET['id'].'/');
        }

        $this->set_title($info_news['n_titre'].' | '.$info_news['c_nom']);
        $this->set_tpl('news/alert.html');
        $this->add_css('forms.css');
        $this->add_css('code.css');
        $this->add_js('write.js');
        $this->add_js('forms.js');
        $this->add_form('contenu');
        Nw::$tpl->set('ID', $info_news['n_id']);
        inc_lib('bbcode/clearer');

        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'BAL_CHAMP'         => 'contenu',
            'NEWS'              => $info_news['n_titre'],
            'RESUME'            => CoupeChar(clearer($info_news['v_texte'])),
            'CAT_REWRITE'       => $info_news['c_rewrite'],
            'REWRITE'           => rewrite($info_news['n_titre']),
        ));
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']   => array('news-70.html'),
            $info_news['c_nom']                 => array($info_news['c_rewrite'].'/'),
            $info_news['n_titre']               => array($info_news['c_rewrite'].'/'.rewrite($info_news['n_titre']).'-'.$_GET['id'].'/'),
            Nw::$lang['news']['alert']          => array(''),
        ));
    }
}

/*  *EOF*   */

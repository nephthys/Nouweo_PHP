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
        if(is_logged_in() && check_auth('can_see_admin'))
        {
            $this->set_tpl('admin/index.html');
            $this->add_js('admin.js');
            $this->add_css('admin.css');
            $this->set_title(Nw::$lang['admin']['titre_accueil']);
            $this->set_filAriane(Nw::$lang['admin']['fa_admin']);
            
            //Chargement de tous les fichiers de langue utiles
            $this->load_lang_file('press');
            $this->load_lang_file('users');
            $this->load_lang_file('news');
            $this->load_lang_file('poll');
            
            if (isset($_POST['log_submit']) && !empty($_POST['log_titre']))
                header('Location: news-21.html?t='.urlencode(trim($_POST['log_titre'])));

            inc_lib('news/count_alerts_news');
            $count_alerts = count_alerts_news(null, false);
            Nw::$tpl->set('NEWS_ERRORS', sprintf(Nw::$lang['news']['nb_news_errors'], $count_alerts));
            Nw::$tpl->set('COUNT_ERRORS', $count_alerts);
        }
        else
            redir(Nw::$lang['admin']['error_cant_see_admin'], false, 'index.html');
    }
}


/*  *EOF*   */

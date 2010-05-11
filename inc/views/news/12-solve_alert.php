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
        
        if(empty($_GET['id']) || !is_numeric($_GET['id']))
            redir(Nw::$lang['news']['error_alert_dont_exist'], false, 'news-13.html');
        
        inc_lib('news/alert_news_exists');
        if(alert_news_exists($_GET['id']) == false)
            redir(Nw::$lang['news']['error_alert_dont_exist'], false, 'news-13.html');

        inc_lib('news/solve_alert_news');
        solve_alert_news($_GET['id'], Nw::$dn_mbr['u_id']);
        redir(Nw::$lang['news']['confirm_alert_solved'], false, 'news-13.html');
    }
}

/*  *EOF*   */

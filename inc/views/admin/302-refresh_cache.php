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
        if(is_logged_in() && check_auth('refresh_cache_droits'))
        {
            inc_lib('admin/refresh_cache_droits');
            refresh_cache_droits();
            redir(Nw::$lang['admin']['redir_cache_refreshed'], true, 'admin.html');
        }
        else
            redir(Nw::$lang['admin']['error_cant_see_admin'], false, './');
    }
}


/*  *EOF*   */

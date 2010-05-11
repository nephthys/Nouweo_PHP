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
        
        $this->set_title(Nw::$lang['users']['mes_options_title']);
        $this->add_css('code.css');
        $this->set_tpl('membres/options_index.html');
        $this->set_filAriane(Nw::$lang['users']['mes_options_title']);
        
        
    }
}

/*  *EOF*   */

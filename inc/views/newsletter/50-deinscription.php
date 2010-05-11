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
        if (empty($_GET['e']) || empty($_GET['t']))
            header('Location: ./');
            
        inc_lib('newsletter/count_abonnement');
        $count_abonne = count_abonnement('a_email = \''.insertBD(trim($_GET['e'])).'\' AND a_token = \''.insertBD(trim($_GET['t'])).'\'');

        if ($count_abonne == 1)
        {
            inc_lib('newsletter/remove_abonnement');
            remove_abonnement($_GET['e']);
            redir(Nw::$lang['newsletter']['desinscription_r'], true, 'newsletter.html');
        }
        else
            redir(Nw::$lang['newsletter']['abo_dont_exist'], false, 'newsletter.html');
    }
}

/*  *EOF*   */

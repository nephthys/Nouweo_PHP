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
        // Il y a bien tous les paramètres nécessaires à l'éxécution du script
        if (!is_logged_in() && !empty($_GET['idm']) && is_numeric($_GET['idm']) && !empty($_GET['ca']))
        {
            // Fil ariane
            $this->set_filAriane(Nw::$lang['users']['title_redef_pass']);
        
            $this->set_title(Nw::$lang['users']['title_redef_pass']);
            $this->set_tpl('membres/redefine_mdp.html');
            $this->add_css('forms.css');
        
            // Ce code existe bien avec ce code d'activation
            inc_lib('users/mbr_act_exists');
            if (!mbr_act_exists($_GET['idm'], $_GET['ca'])) {
                redir(Nw::$lang['users']['redef_mdp_echoue'], false, './');
            }
            
            //Si on redéfinit
            if (isset($_POST['submit']) && !multi_empty(trim($_POST['nw_pass1']), trim($_POST['nw_pass2'])))
            {
                if ($_POST['nw_pass1']==$_POST['nw_pass2'])
                {
                    inc_lib('users/chg_password');
                    chg_password($_POST['nw_pass1'], $_GET['idm'], $_GET['ca']);
                    redir(Nw::$lang['users']['new_redef_pwd'], true, './');
                }
                else
                    redir(Nw::$lang['users']['sames_password'], false, $_SERVER['REQUEST_URI']);
            }
        }
        else
            header('Location: ./');
    }
}

/*  *EOF*   */

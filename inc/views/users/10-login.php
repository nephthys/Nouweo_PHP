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
    /**
     *  Formulaire de connexion à l'espace membre
     *  @author Cam
     *  @return tpl
     */
    protected function main()
    {
        // Si le membre est déjà connecté
        if (is_logged_in()) {
            redir( Nw::$lang['common']['already_connected'], false, './' );
        }
        
        // On modifie le titre de la page
        $this->set_title(Nw::$lang['users']['title_connexion']);
        
        // Pour rediriger le visiteur d'où il est venu
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.'users-10.html') === false) {
            $_SESSION['nw_referer_login'] = $_SERVER['HTTP_REFERER'];
        }
        
        // Affichage du template
        $this->add_css('forms.css');
        $this->set_tpl('membres/login.html');
        
        
        // Fil ariane
        $this->set_filAriane(Nw::$lang['users']['fa_connexion']);
        
        //Si on a soumis le formulaire
        if (isset($_POST['submit']))
        {
            $array_post = array(
                'pseudo'    => $_POST['nw_nickname'],
                'remember'  => (isset($_POST['nw_remember']))
            );
            //On vérifie que les deux champs sont remplis
            if (!multi_empty(trim($_POST['nw_nickname']), trim($_POST['nw_password'])))
            {
                //wtf ? =D
                //echo 'oook';
                //On vérifie que la paire pseudo/mot de passe existe
                inc_lib('users/get_info_account');
                if ($dn_info_account = get_info_account($_POST['nw_nickname'], $_POST['nw_password']))
                {
                    //Si le compte est actif
                    if ($dn_info_account['u_active']==1)
                    {
                        $link_redir = ( !empty( $_SESSION['nw_referer_login'] ) ) ? $_SESSION['nw_referer_login'] : './';
                        $connex_auto = (bool) (isset($_POST['nw_remember']));

                        inc_lib('users/connect_auto_user');
                        connect_auto_user($dn_info_account['u_id'], $_POST['nw_password'], $connex_auto);
                        
                        // On redirige le membre
                        redir(sprintf(Nw::$lang['users']['welcome_user'], $_POST['nw_nickname']), true, $link_redir);
                    }
                    else
                        display_form($array_post, Nw::$lang['users']['not_active']); return;
                }
                else
                    display_form($array_post, Nw::$lang['users']['account_no_exist']); return;
            }
            else
                display_form($array_post, Nw::$lang['users']['champ_obligatoire']); return;
        }
        display_form(array('pseudo' => '', 'remember' => true));
    }
}

/*  *EOF*   */

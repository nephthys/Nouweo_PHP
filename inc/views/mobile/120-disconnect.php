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
     *  Déconnexion de l'utilisateur
     *  @author Cam
     *  @return void
     */
    protected function main()
    {
        // L'ID membre n'est pas renseigné, direction l'index
        if (empty($_GET['id'])) {
            header('Location: mobile.html');
        }
        
        // Pour rediriger le visiteur d'où il est venu
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.'membres-10.html') === false) {
            $_SESSION['nw_referer_deco'] = $_SERVER['HTTP_REFERER'];
        }
            
        $link_redir = ( !empty( $_SESSION['nw_referer_deco'] ) ) ? $_SESSION['nw_referer_deco'] : 'mobile.html';

        // Le membre est bien connecté
        if (is_logged_in() && $_GET['id'] == Nw::$dn_mbr['u_id'])
        {
            $_SESSION = array();
            session_destroy();
                
            // Si les cookies existent, on les supprime
            if (isset($_COOKIE['nw_ident']) && isset($_COOKIE['nw_pass']))
            {
                setcookie('nw_ident', null, time()-3600);
                setcookie('nw_pass', null, time()-3600);
            }
            
            // On affiche le message de confirmation et le redirige
            redir(Nw::$lang['users']['disconnect_msg'], true, $link_redir);
        }
        else
            header('Location: mobile.html');
    }
}

/*  *EOF*   */

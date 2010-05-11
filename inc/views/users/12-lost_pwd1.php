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
     *  Mot de passe oublié (partie 1)
     *  @author Cam
     *  @return tpl
     */
    protected function main()
    {
        // Si le membre est déjà connecté
        if (is_logged_in()) {
            redir( Nw::$lang['common']['already_connected'], false, './' );
        }
        
        $this->set_title(Nw::$lang['users']['title_lost_pwd']);
        $this->set_tpl('membres/oubli_mdp.html');
        $this->add_css('forms.css');
        
        // Fil ariane
        $this->set_filAriane(Nw::$lang['users']['title_lost_pwd']);
            
        //Si le formulaire a été validé
        if (isset($_POST['submit']))
        {
            // Cette adresse email existe bien sur le site
            inc_lib('users/email_exists');
            if (email_exists($_POST['mail']))
            {
                //On récupère les infos du membre
                inc_lib('users/get_info_mbr');
                $membre_mail=get_info_mbr($_POST['mail'], 'mail');
                $lien_password=Nw::$site_url.'users-13.html?idm='.$membre_mail['u_id'].'&ca='.$membre_mail['u_code_act'];
                
                
                //On prépare le texte de l'email
                $txt_mail=sprintf(Nw::$lang['users']['mail_oubli_pwd'], $membre_mail['u_pseudo'], $lien_password, $lien_password, $lien_password);
                
                @envoi_mail(trim($_POST['mail']), sprintf(Nw::$lang['users']['title_mail_lost_pwd'], Nw::$site_name), $txt_mail);
                
                redir(Nw::$lang['users']['send_mail_lost'], true, './');
            }
            else
                redir(Nw::$lang['users']['email_aucun_mbr'], false, 'users-12.html');
        }
    }
}

/*  *EOF*   */

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
        $this->set_title(Nw::$lang['newsletter']['sabonner']);
        $this->add_css('code.css');
        $this->set_tpl('newsletter/abonnement.html');
        $this->set_filAriane(Nw::$lang['newsletter']['sabonner']);
        $this->load_lang_file('users');
        
        $is_already_abonne = false;
        $id_membre_login = (is_logged_in()) ? Nw::$dn_mbr['u_id'] : 0;
        $phrase_abonne = '';
        $token_url = '';
        $email_url = '';
        
        if (is_logged_in())
        {
            inc_lib('newsletter/count_abonnement');
            $is_already_abonne = count_abonnement('a_id_membre = '.intval(Nw::$dn_mbr['u_id']));
            
            if ($is_already_abonne == 1)
            {
                inc_lib('newsletter/get_info_abonnement');
                $donnees_abo = get_info_abonnement('a_id_membre = '.intval(Nw::$dn_mbr['u_id']));
                
                $phrase_abonne = sprintf(Nw::$lang['newsletter']['already_register'], $donnees_abo['a_email']);
                $token_url = $donnees_abo['a_token'];
                $email_url = urlencode($donnees_abo['a_email']);
            }
        }
        
        // S'enregistrer à la newsletter
        if (isset($_POST['submit']) && !empty($_POST['email_newsletter']) && filter_var($_POST['email_newsletter'], FILTER_VALIDATE_EMAIL))
        {
            inc_lib('newsletter/count_abonnement');
            $is_already_abonne = count_abonnement('a_email = \''.insertBD(trim($_POST['email_newsletter'])).'\'');
            
            // Cette adresse email n'est pas déjà enregistrée dans la bdd
            if ($is_already_abonne == 0)
            {
                inc_lib('newsletter/add_abonnement');
                add_abonnement($_POST['email_newsletter'], $id_membre_login);
                
                redir(Nw::$lang['newsletter']['register_r_ok'], true, 'newsletter.html');
            }
            else
                redir(Nw::$lang['newsletter']['email_used'], false, 'newsletter.html');
        }
        
        Nw::$tpl->set(array(
            'IS_ABONNE'     => $is_already_abonne,
            'PHRASE_ABO'    => $phrase_abonne,
            'TOKEN'         => $token_url,
            'EMAIL'         => $email_url,
        ));
    }
}

/*  *EOF*   */

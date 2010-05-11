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
     *  Formulaire d'inscription au site
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
        $this->set_title(Nw::$lang['users']['title_inscription']);      
        $this->add_css('forms.css');
        $this->set_tpl('membres/register.html');
        
        // Fil ariane
        $this->set_filAriane(Nw::$lang['users']['fa_inscription']);
        
        Nw::$tpl->set(array(
            'ACCEPT_RULES'      => sprintf(Nw::$lang['users']['accept_rules'], Nw::$site_name)
        ));
        
        //Si on a soumis le formulaire
        if (isset($_POST['submit']))
        {
            $array_post = array(
                'nw_nickname'       => $_POST['nw_nickname'],
                'nw_pass1'          => $_POST['nw_pass1'],
                'nw_pass2'          => $_POST['nw_pass2'],
                'nw_email'          => $_POST['nw_email'],
                'code_cap'          => $_POST['code_cap'],
                'ac_rules'          => (isset($_POST['ac_rules']))
            );
            
            //On vérifie que les deux champs sont remplis
            if (multi_empty(trim($_POST['nw_nickname']), trim($_POST['nw_pass1']), trim($_POST['nw_pass2']), trim($_POST['nw_email']), trim($_POST['code_cap'])))
            {
                display_form($array_post, Nw::$lang['users']['champ_obligatoire']); 
                return;
            }
            
            // Les mots de passe doivent être identiques
            if ($_POST['nw_pass1'] != $_POST['nw_pass2'])
            {
                display_form($array_post, Nw::$lang['users']['sames_password']); 
                return;
            }
                
            // Le code anti-spam est mauvais
            if (trim($_POST['code_cap']) != $_SESSION['cap_nw'])
            {
                display_form($array_post, Nw::$lang['users']['wrong_antispam']); 
                return;
            }
        
            // L'email est bien sous la bonne forme (name@domain.tld)
            if (!filter_var($_POST['nw_email'], FILTER_VALIDATE_EMAIL))
            {   
                display_form($array_post, Nw::$lang['users']['format_email_false']); 
                return;     
            }
                            
            // On vérifie bien que cet email n'a jamais utilisé lors de l'inscription (doubles comptes)
            inc_lib('users/email_exists');
            if (email_exists($_POST['nw_email']) == true)
            {
                display_form($array_post, Nw::$lang['users']['email_already_used']); 
                return;
            }
            // On vérifie que le pseudo demandé est disponible
            inc_lib('users/pseudo_exists');
            if (pseudo_exists($_POST['nw_nickname']) == true)
            {
                display_form($array_post, Nw::$lang['users']['nickname_used']); 
                return;
            }
            // L'internaute a bien accepté les règles
            if (!isset($_POST['ac_rules']))
            {
                display_form($array_post, Nw::$lang['users']['accept_rules_msg']); 
                return;
            }
            
            // Si on est arrivé jusque là, on inscrit le nouvel utilisateur
            inc_lib('users/add_mbr');
            add_mbr($_POST['nw_nickname'], $_POST['nw_pass1'], $_POST['nw_email']);
                                        
            redir(Nw::$lang['users']['success_register'], true, './');          
        }
        
        // On affiche le template
        display_form(array(
                'nw_nickname'   => '', 
                'nw_pass1'      => '',
                'nw_pass2'      => '',
                'nw_email'      => '',
                'code_cap'      => '',
                'ac_rules'      => false
        ));
    }
}

/*  *EOF*   */

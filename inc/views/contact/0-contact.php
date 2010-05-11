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
        $this->set_title(Nw::$lang['contact']['contact']);
        $this->add_css('forms.css');
        $this->set_filAriane(array(
            Nw::$lang['contact']['contact']         => array(''),
        ));
        $this->set_tpl('contact/contact.html');
        $this->load_lang_file('users');
        $this->add_form('contenu');
        
        //Si on veut envoyer le mail
        if(isset($_POST['submit']))
        {
            if(empty($_POST['pseudo']))
                $msg_error = sprintf(Nw::$lang['contact']['error_empty'], Nw::$lang['contact']['_pseudo']);
            elseif(empty($_POST['mail']))
                $msg_error = sprintf(Nw::$lang['contact']['error_empty'], Nw::$lang['contact']['_mail']);
            elseif(empty($_POST['sujet']))
                $msg_error = sprintf(Nw::$lang['contact']['error_empty'], Nw::$lang['contact']['_sujet']);
            elseif(empty($_POST['contenu']))
                $msg_error = sprintf(Nw::$lang['contact']['error_empty'], Nw::$lang['contact']['_contenu']);
            elseif($_POST['code_cap'] != $_SESSION['cap_nw'])
                $msg_error = Nw::$lang['users']['wrong_antispam'];
            
            if(!empty($msg_error))
                display_form(array(
                    'pseudo' => $_POST['pseudo'], 
                    'mail' => $_POST['mail'], 
                    'sujet' => $_POST['sujet'],
                    'contenu' => $_POST['contenu'], 
                    'captcha' => $_POST['captcha'], 
                    'nom' => $_POST['nom'],
                    'code_cap' => '',
                ), $msg_error);
            else
            {
                inc_lib('mail/email_contact');
                
                if (email_contact($_POST['mail'], $_POST['pseudo'], $_POST['nom'], '[Contact] '.$_POST['sujet'], $_POST['contenu'], get_ip()))
                    redir(Nw::$lang['contact']['redir_ok'], true, 'contact.html');
            } 
        }
        
        else
        {
            display_form(array(
                'pseudo' => (is_logged_in()) ? Nw::$dn_mbr['u_pseudo'] : '', 
                'mail' => (is_logged_in()) ? Nw::$dn_mbr['u_email'] : '', 
                'sujet' => '', 
                'contenu' => '', 
                'captcha' => '', 
                'nom' => '',
                'code_cap' => '',
            ));
        }
    }
}

/*  *EOF*   */

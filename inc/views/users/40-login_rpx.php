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
        
        $token = (!empty($_GET['token'])) ? $_GET['token'] : $_GET['auth_token'];
    
        if (empty($token)) {
            header('Location: ./');
        }
        
        // On modifie le titre de la page
        $this->set_title(Nw::$lang['users']['login_rpx_title']);
        
        $post_data = array('token' => $token,
            'apiKey' => Nw::$rpx_login['api_key'],
            'format' => 'json'); 

        // make the api call using libcurl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $raw_json = curl_exec($curl);
        curl_close($curl);
        
        // parse the json response into an associative array
        $auth_info = json_decode($raw_json, true);
        
        // process the auth_info response
        if ($auth_info['stat'] == 'ok') 
        {
            inc_lib('users/identifier_exists');
            
            $profile = $auth_info['profile'];
            $identifier = $profile['identifier'];
            
            /**
            *   Le membre est déjà inscrit, on le loggue
            **/
            if (identifier_exists($identifier) == true)
            {
                inc_lib('users/get_info_mbr');
                inc_lib('users/connect_auto_user');
                $donnees_ident = get_info_mbr($identifier, 'identifier');
                
                if ($donnees_ident['u_active'] == 1)
                {
                    connect_auto_user($donnees_ident['u_id'], $donnees_ident['u_password'], true, False);
                    redir(sprintf(Nw::$lang['users']['welcome_user'], $donnees_ident['u_pseudo']), true, './');
                }
                else
                    redir(Nw::$lang['users']['not_active'], false, 'users-10.html');
            }
            /**
            *   Sinon on lui créé un compte
            **/
            else
            {
                $this->set_tpl('membres/rpx.html');
                $this->add_css('forms.css');
                
                $need_define_email = 0;
                $need_define_nick = 0;
                $email_inscription = (isset($profile['email'])) ? $profile['email'] : '';
                $nick_inscription = $profile['preferredUsername'];
                $no_errors = false;
                
                if (empty($email_inscription))
                    $need_define_email = 1;

                inc_lib('users/pseudo_exists');
                if (pseudo_exists($nick_inscription) == true)
                    $need_define_nick = 1;
                    
                Nw::$tpl->set(array(
                    'TXT_EDITO'     => sprintf(Nw::$lang['users']['txt_register_rpx'], $profile['preferredUsername'], $profile['providerName']),
                    'TITLE'         => sprintf(Nw::$lang['users']['title_register_rpx'], $profile['providerName']),
                    'DEF_NICK'      => $need_define_nick,
                    'DEF_EMAIL'     => $need_define_email,
                    'TXT_DEF_NICK'  => ($need_define_nick) ? '<span style="color: red;">'.sprintf(Nw::$lang['users']['need_def_nick'], $profile['preferredUsername']).'</span>' : '',
                ));
                
                display_form(array('nw_pseudo' => $nick_inscription, 'nw_email' => ''));
                $value_form_email = (isset($_POST['nw_email'])) ? $_POST['nw_email'] : '';
                $value_form_nick = (isset($_POST['nw_pseudo'])) ? $_POST['nw_pseudo'] : '';
                
                /**
                *   L'utilisateur doit spécifier une adresse email pour terminer son inscription
                **/
                
                if ($need_define_email)
                {
                    if (isset($_POST['submit']) && !empty($_POST['nw_email']))
                    {
                        $array_post = array('nw_email' => $value_form_email, 'nw_pseudo' => $value_form_nick);
                        
                        // L'email est bien sous la bonne forme (name@domain.tld)
                        if (!filter_var($_POST['nw_email'], FILTER_VALIDATE_EMAIL))
                        {   
                            display_form($array_post, Nw::$lang['users']['format_email_false']); 
                            $no_errors = true;
                            return;     
                        }
                            
                        // On vérifie bien que cet email n'a jamais utilisé lors de l'inscription (doubles comptes)
                        inc_lib('users/email_exists');
                        if (email_exists($_POST['nw_email']) == true)
                        {
                            display_form($array_post, Nw::$lang['users']['email_already_used']); 
                            $no_errors = true;
                            return;
                        }
                        
                        $email_inscription = $_POST['nw_email'];
                    }
                }
                
                /**
                *   Le pseudo du gars est déjà utilisé, on lui demande d'en prendre un autre
                **/
                
                if (isset($_POST['submit']) && !empty($_POST['nw_pseudo']))
                {
                    $array_post = array('nw_email' => $value_form_email, 'nw_pseudo' => $value_form_nick);
                        
                    // L'email est bien sous la bonne forme (name@domain.tld)
                    inc_lib('users/pseudo_exists');
                    if (pseudo_exists($_POST['nw_pseudo']) == true)
                    {   
                        display_form($array_post, Nw::$lang['users']['nickname_used']); 
                        $no_errors = true;
                        return;     
                    }

                    $nick_inscription = $_POST['nw_pseudo'];
                    
                    /**
                    *   On a toutes les infos pour inscrire le membre
                    **/
                    if (!$no_errors && !empty($nick_inscription) && !empty($email_inscription))
                    {
                        inc_lib('users/add_mbr');
                        $pass_compte = '?ZjZ'.$identifier.uniqid();
                        $active_compte = ($need_define_email == 1) ? 0 : 1;
                        $new_id = add_mbr($nick_inscription, $pass_compte, $email_inscription, $identifier, $active_compte);
                        
                        // Le compte est validé tout seul, pas besoin de validation par mail
                        if (!$need_define_email)
                        {
                            inc_lib('users/connect_auto_user');
                            connect_auto_user($new_id, $pass_compte, true);
                            redir(sprintf(Nw::$lang['users']['inscrit_rpx_no_valid'], $nick_inscription, $profile['providerName']), true, './');
                        }
                        else
                            redir(sprintf(Nw::$lang['users']['inscrit_rpx_with_val'], $nick_inscription, $profile['providerName']), true, './');
                    }
                }
            }
        }
        else
            header('Location: ./');
    }
}

/*  *EOF*   */

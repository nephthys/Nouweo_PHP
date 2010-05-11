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
        if (!is_logged_in()) {
            redir(Nw::$lang['common']['need_login'], false, 'users-10.html');
        }
        
        $this->set_title(Nw::$lang['users']['item_rpx']);
        $this->set_tpl('membres/options_rpx.html');
        $this->add_css('forms.css');
        
        $this->set_filAriane(array(
            Nw::$lang['users']['mes_options_title']     => array('users-60.html'),
            Nw::$lang['users']['item_rpx']              => array('')
        ));
        
        $name_service = '';
        
        /** 
        *   Ajout d'un service sur un compte existant
        **/
        if (isset($_GET['login']))
        {
            $token = (!empty($_GET['token'])) ? $_GET['token'] : $_GET['auth_token'];
    
            if (empty($token))
                header('Location: ./');
            
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
                if (identifier_exists($identifier) == false)
                {
                    inc_lib('users/update_rpx_login');
                    update_rpx_login(Nw::$dn_mbr['u_id'], $identifier);
                    
                    redir(sprintf(Nw::$lang['users']['redir_d_rpx_add'], $profile['providerName']), true, 'users-65.html');
                }
                else
                    redir(sprintf(Nw::$lang['users']['redir_d_ident_exists'], $profile['providerName']), false, 'users-65.html');
            }
            else
                header('Location: ./');
        }

        if (!empty(Nw::$dn_mbr['u_identifier']))
        {
            $domain_service = parse_url(Nw::$dn_mbr['u_identifier']);
            $domain_service = $domain_service['host'];
            
            if(strpos($domain_service, 'www.') !== false)
                $domain_service = substr($domain_service, 4);
            
            $explode_domain = explode('.', $domain_service);
            $name_service = ucfirst($explode_domain[0]);
        }
        
        if (isset($_GET['delete']) && !empty(Nw::$dn_mbr['u_identifier']))
        {
            inc_lib('users/update_rpx_login');
            update_rpx_login(Nw::$dn_mbr['u_id'], '');
            
            redir(sprintf(Nw::$lang['users']['redir_d_rpx_login'], $name_service), true, 'users-65.html');
        }
        
        Nw::$tpl->set(array(    
            'IDENTIFIER'            => Nw::$dn_mbr['u_identifier'],
            'SERVICE'               => $name_service,
            'PHRASE'                => sprintf(Nw::$lang['users']['now_logged_rpx'], $name_service),
            'TOKEN_URL'             => urlencode(Nw::$site_url.'users-65.html?login'),
        ));
    }
}

/*  *EOF*   */

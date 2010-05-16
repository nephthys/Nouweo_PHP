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

/**
*   Identifiants MySQL
**/
if (is_file(PATH_ROOT.'inc/configs/prod.php'))
{
    Nw::$is_prod = true;
    include_once(PATH_ROOT.'inc/configs/prod.php');
}
else
    include_once(PATH_ROOT.'inc/configs/local.php');

Nw::$prefix_table   = $ident_config['pref'];
Nw::$site_url       = $ident_config['siteurl'];
Nw::$site_name      = $ident_config['sitename'];
Nw::$site_slogan    = $ident_config['slogan'];
Nw::$site_lang      = $ident_config['sitelang'];
Nw::$id_devs        = (isset($ident_config['id_devs'])) ? $ident_config['id_devs'] : array();
Nw::$assets         = $ident_config['assets'];
Nw::$social         = (isset($ident_config['social'])) ? $ident_config['social'] : array();
Nw::$twitter        = (isset($ident_config['twitter'])) ? $ident_config['twitter'] : array();
Nw::$site_email     = (isset($ident_config['email'])) ? $ident_config['email'] : '';
Nw::$site_email_nor = (isset($ident_config['email_nor'])) ? $ident_config['email_nor'] : '';
Nw::$rpx_login      = (isset($ident_config['rpx_login'])) ? $ident_config['rpx_login'] : '';

// Connexion à la base de données
Nw::$DB = new Db($ident_config['host'], $ident_config['user'], $ident_config['pass'], $ident_config['base']);

// Destruction des identifiants de connexion à la bdd
unset($ident_config);

Nw::$pref = array(
    'nb_news_redac'         => 20,
    'nb_news_homepage'      => 10,
    'nb_cmts_page'          => 10,
    'nb_votes_valid_news'   => 10,
    'nb_votes_cmts_ptn'     => 5,
    
    'ppl_nb_news'           => 15,
    'ppl_nb_contribs'       => 20,
    'ppl_nb_comments'       => 20,
    
    //Admin
    'nb_logs_admin'         => 20,
    
    'long_intro_news'       => 500,
);

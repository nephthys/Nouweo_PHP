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

// La constante INC_COMMON n'est pas déclarée, on arrête le script
if (!defined('INC_COMMON'))
    exit;

//Redéfinition du chemin d'inclusion pour les librairies PEAR.
set_include_path(get_include_path().PATH_SEPARATOR.PATH_ROOT.'inc/res/');

// On démarre les sessions
session_start();
session_regenerate_id();

if (!isset($_SESSION['token']))
    $_SESSION['token'] = md5(uniqid(rand(), true));

/**
 *  On inclut les fichiers nécessaires au bon
 *  fonctionnement du site (tpl, mysql, functions, lang, etc.)
 */
function __autoload($classname)
{
    $classname = mb_strtolower($classname);
   
    if(substr($classname, 0, 2) != 'w_')
        require(PATH_ROOT.'inc/class/'.$classname.'.class.php');
}

/**
 *  Un peu de sécurité sur les superglobales $_POST, $_GET et $_COOKIE.
 */
if( get_magic_quotes_gpc() )
{
    function stripslashes_antimq($valeur)
    {
        if(is_array($valeur))
            return array_map('stripslashes_antimq',$valeur);
        else
            return stripslashes($valeur);
    }
    $_POST = array_map( 'stripslashes_antimq', $_POST );
    $_GET = array_map( 'stripslashes_antimq', $_GET );
    $_COOKIE = array_map( 'stripslashes_antimq', $_COOKIE );
}

include(PATH_ROOT.'inc/functions.php');
include(PATH_ROOT.'inc/_constantes.php');
include(PATH_ROOT.'inc/_config.php');

// Si la connexion MySQL a échoué
if (!Nw::$DB->is_connected())
    error(Nw::$lang['common']['mysqlerror_title'], Nw::$lang['common']['mysqlerror_content']);

if (!is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.categories.php'))
{
    inc_lib('admin/gen_cachefile_categories');
    gen_cachefile_categories();
}

if (!is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.hot_search.php'))
{
    inc_lib('admin/gen_cachefile_top_search');
    gen_cachefile_top_search(true);
}

if (!is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.nb_members.php'))
{
    inc_lib('admin/gen_cachefile_nb_members');
    gen_cachefile_nb_members();
}

if (is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.last_mod_file.php'))
    include(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.last_mod_file.php');
    
include(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.categories.php');
include(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.hot_search.php');
include(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.nb_members.php');


//On essaye de se connecte avec les cookies
if (!is_logged_in() && !empty($_COOKIE['nw_ident']) && !empty($_COOKIE['nw_pass']))
{
    //On vérifie que le compte existe
    if (Session::count_exit_cookies($_COOKIE['nw_ident'], $_COOKIE['nw_pass']) > 0) 
    {
        $_SESSION['logged'] = true;
        $_SESSION['ident_session'] = intval($_COOKIE['nw_ident']);
    }
}

// Le membre est connecté
if (is_logged_in())
{
    Nw::$dn_mbr = Session::recup_donnees_membre($_SESSION['ident_session']);
    
    if (Nw::$dn_mbr['u_group'] == 10)
        exit;
    
    // Si jamais le fichier de cache n'existe pas on le créé.
    $fcache = PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.Nw::$dn_mbr['u_group'].'.php';
    
    if (!is_file($fcache))
    {
        inc_lib('admin/refresh_cache_droits');
        refresh_cache_droits(Nw::$dn_mbr['u_group']);
    }
    
    include($fcache);
    Nw::$droits = $group_auth['g'.Nw::$dn_mbr['u_group']];
    
    // Mise à jour des données membre
    Session::maj_donnees_membre($_SESSION['ident_session']);
}

Nw::$tpl = new Talus_TPL(PATH_ROOT.'themes/tpl/', PATH_ROOT.Nw::$assets['dir_cache'].'tpl/');

// Mode pour les développeurs
if (DEV_MODE)
{
    error_reporting(E_ALL | E_STRICT);
    Nw::$dev_mode = 1;
}

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

final class Nw
{
    //Les variables qui concernent le site directement
    public static $prefix_table='';
    
    public static $site_url='';
    public static $site_lang='';
    public static $site_name='';
    public static $site_slogan='';
    public static $site_email='';
    public static $site_email_nor='';
    public static $is_prod=false;
    
    public static $lang=array();
    public static $pref=array();
    
    public static $cache_categories=array();
    public static $last_mod_file=array();
    public static $hot_search=array();
    public static $nbr_minutes_connected=10;
    public static $total_membres=0;
    
    public static $dev_mode=0;
    public static $id_devs=array();
    public static $assets=array();
    public static $twitter=array();
    public static $rqts=array();
    public static $basic_balises=array();
    public static $forms_size=array();
    public static $add_content = array();
    
    //Les variables qui concernent le membre
    public static $decalage_horaire='';
    public static $dn_mbr=array();
    public static $droits=array();
    

    public static $config = array();
    public static $tpl = null;
    public static $DB = null;
    public static $frame = null;
    
    public static $social=array();
    public static $rpx_login = array();
}

/* Constantes pour le raccourcissement des URLs */
define('URLS_MAX', 40); // -- Nbre maximum de charactères dans une url
define('URLS_SEPARATOR', '[...]'); // -- Séparateur entre le début et la fin de l'url si elle est trop longue.
define('URLS_NB_FIX', floor(( URLS_MAX - strlen(URLS_SEPARATOR) ) / 2)); // -- Calculé automatiquement, en fonction de URLS_MAX et de la longueur de URLS_SEPARATOR

/** Constantes pour la pagination */
define('AROUND_PAGE', 3);
define('PAGINATION_NONE', 0);
define('PAGINATION_ACTIVATED', 1);

/* Flags pour la pagination */
define('PAGINATION_PREV_NEXT', 2);
define('PAGINATION_FIRST_LAST', 4);
define('PAGINATION_ALL', PAGINATION_PREV_NEXT | PAGINATION_FIRST_LAST);

/** Constantes pour la génération des adresses mail */
define('MAIL_PLUS_WIDTH', 10);
define('MAIL_PLUS_HEIGHT', 10);
define('MAIL_POS_WIDTH', 9);
define('MAIL_POS_HEIGHT', 3.875);

/* Paramètres de fonctions */
define('DATETIME', 1);
define('DATE', 2);

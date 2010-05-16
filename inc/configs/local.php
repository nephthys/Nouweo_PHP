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

$ident_config = array(
	'host'		=> 'localhost',
	'user'		=> 'root',
	'pass'		=> 'root',
	'base'		=> 'nw_v2',
	'pref'		=> 'nw_',
	
	// Paramètres du site
	'sitelang'	=> 'fr',
	'sitename'	=> 'Nouweo',
	'slogan'	=> 'L\'actualité collaborative',
	'siteurl'	=> 'http://127.0.0.1:8888/NW/',
	
	'assets'	=> array(
		'dir_cache'		=> '../assets/cache/',
		'dir_upload'	=> '../assets/upload/',
		'dir_users'		=> '../assets/users/',
		'url_upload'	=> 'http://127.0.0.1:8888/assets/',
	),
	
	'id_devs'	=> array(1),
	'social'	=> array(
		'twitter'	=> 'http://twitter.com/nouweo',
		'facebook'	=> 'http://www.facebook.com/pages/Nouweo/97545342434',
	),
);

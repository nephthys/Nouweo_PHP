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

define('PATH_ROOT', '../');
define('INC_COMMON', true);
define('DEV_MODE', 0);
ob_start();

include(PATH_ROOT.'inc/_common.php');

header('Content-Type: text/xml'); 

echo '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';

foreach(glob(PATH_ROOT.'inc/res/sitemaps/'.Nw::$site_lang.'.*') as $sitemap)
{
    $sitemap_url = str_replace(PATH_ROOT, '', $sitemap);
    echo '
<sitemap>
    <loc>'.Nw::$site_url.$sitemap_url.'</loc>
    <lastmod>'.date('c', filemtime($sitemap)).'</lastmod>
</sitemap>
    ';
}

echo '
</sitemapindex>';

ob_end_flush();

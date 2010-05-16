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

function gen_cachefile_categories()
{   
    $categories_list = array();
    $content_file = '';
    
    $rqt = Nw::$DB->query('SELECT c_id, c_nom, c_rewrite, c_image FROM '.Nw::$prefix_table.'categories 
    ORDER BY c_id ASC LIMIT 15') OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    while ($donnees = $rqt->fetch_assoc()) 
    {
        $categories_list[] = ''.$donnees['c_id'].' => array(\''.$donnees['c_nom'].'\', \''.$donnees['c_rewrite'].'\', \'\')';
    }
    
    $content_file = (count($categories_list) > 0) ? implode($categories_list, ', '."\r\t") : '';
    
    $content_cachefile  = '<?php '."\r".'Nw::$cache_categories = array('."\r\t";
    $content_cachefile .= $content_file;
    $content_cachefile .= "\r".');'."\r".'?>';
    
    file_put_contents(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.categories.php', $content_cachefile);
}

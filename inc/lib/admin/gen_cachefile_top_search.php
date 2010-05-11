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

function gen_cachefile_top_search($force_gen=false)
{   
    $recherches_hot = array();
    $compares = array();
    $content_file = '';
    
    $rqt = Nw::$DB->query('SELECT l_mot_cle, COUNT(l_mot_cle) AS count_key FROM '.Nw::$prefix_table.'logs_recherche WHERE l_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY l_mot_cle ORDER BY count_key DESC LIMIT 10') OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    while ($donnees = $rqt->fetch_assoc()) 
    {
        $recherches_hot[] = '\''.$donnees['l_mot_cle'].'\'';
    }
    
    
    $compare_cachefile = array_diff(Nw::$hot_search, $recherches_hot);

    if (count($compare_cachefile) > 0 || $force_gen)
    {
        $content_file = implode($recherches_hot, ', '."\r\t");
        $force_gen = true;
    }
    
    $content_cachefile_search  = '<?php '."\r".'Nw::$hot_search = array( '."\r\t";
    $content_cachefile_search .= $content_file;
    $content_cachefile_search .= "\r".');'."\r".'?>';
    
    if ($force_gen)
        file_put_contents(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.hot_search.php', $content_cachefile_search);
}

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

function edit_infos_live($id)
{
    $id     = intval($id);
    $title  = (isset($_POST['w_live_title'])) ? $_POST['w_live_title'] : '';
    $open   = (isset($_POST['w_live_open'])) ? 'false' : 'true';
    
    $content_live = '<?php
$dn_widget['.$id.'] = array(
    \'title\'       => \''.addslashes($title).'\',
    \'open\'        => '.($open).',
);';
    
    file_put_contents(PATH_ROOT.Nw::$assets['dir_cache'].'widgets/data/'.Nw::$site_lang.'.w_live.'.intval($id).'.php', $content_live);
}

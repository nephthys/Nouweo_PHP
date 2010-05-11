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
 *       Transforme une adresse mail en GD.
 *       @param  integer id      ID Du membre
 *       @param  string  mail    mail du membre
 *       @return bool
 *       @access public
*/
function mail2gd($id, $mail)
{
    if (file_exists(PATH_ROOT.Nw::$assets['dir_users'].'mail.'.$id.'.png'))
        unlink(PATH_ROOT.Nw::$assets['dir_users'].'mail.'.$id.'.png');
    
    $img = imagecreate(strlen($mail)*11 + MAIL_PLUS_WIDTH, 18 + MAIL_PLUS_HEIGHT);
    $bg = imagecolorallocate($img, 255, 255, 255);
    $txt = imagecolorallocate($img, 0, 0, 0);
       
    imagestring($img, 5, MAIL_POS_WIDTH, MAIL_POS_HEIGHT, $mail, $txt);
    imagepng($img, PATH_ROOT.Nw::$assets['dir_users'].'mail.'.$id.'.png');
    
    chmod(PATH_ROOT.Nw::$assets['dir_users'].'mail.'.$id.'.png', 0777); 
}

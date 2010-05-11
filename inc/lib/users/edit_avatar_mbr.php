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

function edit_avatar_mbr()
{
    // Upload via le disque dur
    if (!empty($_FILES['file']['name']))
    {
        $file2up        = $_FILES['file']['tmp_name'];
        $name_file      = $_FILES['file']['name'];
        $taille_file    = filesize($file2up);
    }
    // Ou via l'url
    else
    {
        $file2up        = $_POST['url'];
        $name_file      = $file2up;
        $buffer         = '';
                                
        if( $fd = fopen( $file2up, 'r' ) ) 
        {
            while( !feof( $fd ) ) 
                $buffer .= fgets( $fd, 4096 );

            fclose( $fd );
        }
                                
        $taille_file    = strlen($buffer);
    }
    
    $upload_status = false;
    $ext_upload = strtolower( substr( strrchr( $name_file, '.' ), 1 ) );
    $nom_fichier = substr( time(), 0, -2 ) . mt_rand( 1, 999 );
    
    if (in_array($ext_upload, array('gif', 'png', 'jpg', 'jpeg')))
    {
        if (copy($file2up, PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.'.$nom_fichier.'.png'))
        {
            create_mini(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.'.$nom_fichier.'.png', PATH_ROOT.Nw::$assets['dir_users'].'pictos/100_'.$nom_fichier.'.png', $ext_upload, 100);
            create_mini(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.'.$nom_fichier.'.png', PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.3.'.$nom_fichier.'.png', $ext_upload, 50);
            create_mini(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.'.$nom_fichier.'.png', PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.2.'.$nom_fichier.'.png', $ext_upload, 20);
            
            recadrer_image(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.2.'.$nom_fichier.'.png', PATH_ROOT.Nw::$assets['dir_users'].'pictos/16_'.$nom_fichier.'.png', 18, 18);
            recadrer_image(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.3.'.$nom_fichier.'.png', PATH_ROOT.Nw::$assets['dir_users'].'pictos/45_'.$nom_fichier.'.png', 45, 45);
            
            @unlink(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.'.$nom_fichier.'.png');
            @unlink(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.2.'.$nom_fichier.'.png');
            @unlink(PATH_ROOT.Nw::$assets['dir_users'].'pictos/tmp.3.'.$nom_fichier.'.png');
            
            $upload_status = true;
        }
    }
    else
        redir(Nw::$lang['users']['avatar_false_ext'], false, 'users-62.html');
    
    if ($upload_status)
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members SET u_avatar = \''.$nom_fichier.'\' WHERE u_id = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);
}

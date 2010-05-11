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

/***
*   Génère un fichier cache pour les droits des groupes
*   @author Cam
*   @param integer  $id_group       ID du groupe
*   @param text $content            Contenu du fichier cache à créer
*   @return void
***/
function new_grp_auth_cache($id_group, $content)
{
    $file_config = fopen(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$id_group.'.php', 'w');
    fwrite($file_config, $content);
    fclose($file_config);
}

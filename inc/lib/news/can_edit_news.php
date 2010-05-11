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

function can_edit_news($auteur_news, $etat_news)
{
    $droit_edit = false;

    switch ($etat_news)
    {
        // Edition des news en ligne
        case 3:
            if(($auteur_news == Nw::$dn_mbr['u_id'] && Nw::$droits['can_edit_mynews_online']) || Nw::$droits['can_edit_news_online'])
            $droit_edit = true;
        break;
        // Edition des news en attente
        case 2:
            if(($auteur_news == Nw::$dn_mbr['u_id'] && Nw::$droits['can_edit_mynews_redac']) || Nw::$droits['can_edit_news_online'])
            $droit_edit = true;
        break;
        // Edition des news en rédaction
        case 1:
            if(($auteur_news == Nw::$dn_mbr['u_id'] && Nw::$droits['can_edit_mynews_redac']) || Nw::$droits['can_edit_news_redac'])
            $droit_edit = true;
        break;
        // Edition des news hors ligne
        case 0:
            if(($auteur_news == Nw::$dn_mbr['u_id'] && Nw::$droits['can_edit_mynews_redac']) || Nw::$droits['can_edit_news_online'])
            $droit_edit = true;
        break;
    }

    return $droit_edit;
}

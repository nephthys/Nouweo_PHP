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

function send_newsletter($email, $titre, $cont)
{
    $entetedate = date("D, j M Y H:i:s -0600"); // avec offset horaire
    $headers  = 'From: "'.Nw::$site_name.'" <'.Nw::$site_email_nor.'>'."\n"; 
    $headers .= "Cc: \n";
    $headers .= "Bcc: \n"; // Copies cachées
    $headers .= 'Return-Path: <'.Nw::$site_email_nor.'>'."\n"; 
    $headers .= 'MIME-Version: 1.0'."\n"; 
    $headers .= 'Content-Type: text/html; charset="utf-8"'."\n"; 
    $headers .= 'Content-Transfer-Encoding: 8bit'."\n"; 
    $headers .= "X-Mailer: PHP/" . phpversion() . "\n\n" ;

    if( mail( $email, $titre, $cont, $headers ) ) 
        return TRUE;
    else 
        return FALSE;
}

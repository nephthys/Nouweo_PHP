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

function email_contact($dest_email, $dest_pseudo, $dest_nom, $titre, $cont, $ip=0)
{
    Nw::$tpl->set(array(
        'SITE_NAME'     => Nw::$site_name,
        'SITE_URL'      => Nw::$site_url,
        'LANG'          => Nw::$lang,
        'TITRE'         => $titre,
        'PSEUDO'        => $dest_pseudo,
        'NOM'           => $dest_nom,
        'EMAIL'         => $dest_email,
        'IP'            => long2ip($ip),
        'CONTENT'       => $cont,
    ));
    
    $name_exp = (!empty($dest_nom)) ? $dest_nom : $dest_pseudo;
    $content_mail = Nw::$tpl->pparse('mail/contact.html');

    $mail = new PHPMailer(); // defaults to using php "mail()"
    
    $mail->AddReplyTo($dest_email, $name_exp);
    $mail->SetFrom(Nw::$site_email_nor, Nw::$site_name);
    $mail->AddAddress(Nw::$site_email);

    $mail->IsHTML(false);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $titre;
    $mail->Body = $content_mail;

    if (!$mail->Send())
        return FALSE;
    else 
        return TRUE;
}

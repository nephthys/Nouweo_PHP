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

class Page extends Core
{
    protected function main()
    {
        if (!is_logged_in()) {
            redir(Nw::$lang['common']['need_login'], false, 'users-10.html');
        }
        
        $this->set_title(Nw::$lang['users']['item_infos_profil']);
        $this->set_tpl('membres/options_profil.html');
        $this->add_css('code.css');
        $this->add_css('forms.css');
        $this->add_js('ajax.js');
        $this->add_js('write.js');
        $this->add_form('contenu');
        
        $this->set_filAriane(array(
            Nw::$lang['users']['mes_options_title']     => array('users-60.html'),
            Nw::$lang['users']['item_infos_profil']     => array('')
        ));
        
        if (isset($_POST['submit']))
        {
            inc_lib('users/edit_profile_mbr');
            edit_profile_mbr();
            
            redir(Nw::$lang['users']['redir_t_infos_profil'], true, 'users-60.html');
        }

        inc_lib('bbcode/unparse');
        
        $fuseaux_horaires = array(
            '-13:00:00'         => '[UTC - 12] Ile Baker',
            '-12:00:00'         => '[UTC - 11] Iles Midway, Samoa',
            '-11:00:00'         => '[UTC - 10] Hawaii, Iles Cook',
            '-10:30:00'         => '[UTC - 9:30] Iles Marquises',
            '-10:00:00'         => '[UTC - 9] Alaska, Iles Gambier',
            '-09:00:00'         => '[UTC - 8] Pacifique (Etats-unis et Canada), Tijuana',
            '-08:00:00'         => '[UTC - 7] Arizona, Chihuahua, La Paz, Montagnes Rocheuses',
            '-07:00:00'         => '[UTC - 6] Amérique Centrale',
            '-06:00:00'         => '[UTC - 5] Heure de l\'est (Etats-Unis, Canada), Bogota, Lima, Quito',
            '-05:00:00'         => '[UTC - 4] Heure atlantique (Canada), Caracas, La Paz, Santiago',
            '-04:30:00'         => '[UTC - 3:30] Terre Neuve',
            '-04:00:00'         => '[UTC - 3] Amazonie, Groenland central',
            '-03:00:00'         => '[UTC - 2] Fernando de Noronha, Géorgie du Sud &amp; Iles Sandwich',
            '-02:00:00'         => '[UTC - 1] Iles des Açores, Iles du Cap Vert, Groenland oriental',
            '-01:00:00'         => '[UTC] Europe de l\'Ouest, Heure de Greenwich, Dublin, Edimbourg, Lisbonne, Londres',
            '00:00:00'          => '[UTC + 1] Europe Centrale, Bruxelles, Copenhague, Madrid, Paris, Afrique de l\'Est',
            '01:00:00'          => '[UTC + 2] Europe de l\'Est, Bucarest, Helsinki, Kiev, Afrique Centrale, Jérusalem',
            '02:00:00'          => '[UTC + 3] Moscou, Afrique de l\'Est, Koweït, Riyad',
            '02:30:00'          => '[UTC + 3:30] Iran',
            '03:00:00'          => '[UTC + 4] Abu Dhabi, Samara, Seychelles',
            '03:30:00'          => '[UTC + 4:30] Afghanistan',
            '04:00:00'          => '[UTC + 5] Pakistan',
            '04:30:00'          => '[UTC + 5:30] Inde, Sri Lanka',
            '04:40:00'          => '[UTC + 5:45] Népal',
            '05:00:00'          => '[UTC + 6] Bangladesh, Bhutan, Novossibirsk',
            '05:30:00'          => '[UTC + 6:30] Iles Cocos, Birmanie',
            '06:00:00'          => '[UTC + 7] Indochine, Krasnoïarsk, Jakarta',
            '07:00:00'          => '[UTC + 8] Chine, Australie de l\'Ouest, Irkoutsk',
            '07:45:00'          => '[UTC + 8:45] Australie du Sud-ouest',
            '08:00:00'          => '[UTC + 9] Japon, Corée, Taïwan',
            '08:30:00'          => '[UTC + 9:30] Australie Centrale',
            '09:00:00'          => '[UTC + 10] Australie de l\'Est, Vladivostok',
            '09:30:00'          => '[UTC + 10:30] Ile de Lord Howe',
            '10:00:00'          => '[UTC + 11] Iles Salomon, Nouvelle Calédonie',
            '10:30:00'          => '[UTC + 11:30] Norfolk',
            '11:00:00'          => '[UTC + 12] Nouvelle-Zélande, Fidji, Kamchatka',
            '11:45:00'          => '[UTC + 12:45] Iles Chatham',
            '12:00:00'          => '[UTC + 13] Tonga, Iles Phoenix',
            '13:00:00'          => '[UTC + 14] Iles de la ligne'
        );
        
        
        Nw::$tpl->set(array(    
            'BAL_CHAMP'         => 'biographie',
            //'SIZE_FORM'           => mod_size_form('biographie'),
            'FUSEAUX'           => $fuseaux_horaires,
        ));
        
        // On affiche le template
        display_form(array(
            'decalage_horaire'  => (!empty(Nw::$dn_mbr['u_decalage'])) ? Nw::$dn_mbr['u_decalage'] : '00:00:00', 
            'date_naissance'    => Nw::$dn_mbr['date_naissance'],
            'localisation'      => Nw::$dn_mbr['u_localisation'],
            'biographie'        => unparse(Nw::$dn_mbr['u_bio']),
        ));
    }
}

/*  *EOF*   */

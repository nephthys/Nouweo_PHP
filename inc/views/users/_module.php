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

abstract class Module
{
    public static $vars_pg = array(
            0   => '0-list_membres',
            10  => '10-login',
            12  => '12-lost_pwd1',
            13  => '13-lost_pwd2',
            20  => '20-disconnect',
            30  => '30-register',
            32  => '32-activation',
            40  => '40-login_rpx',
            
            55 => '55-view_ips',
            56 => '56-list_ban_ip',
            57 => '57-ban_ip',
            
            60  => '60-options',
            61  => '61-options_profil',
            62  => '62-options_avatar',
            63  => '63-options_pass',
            64  => '64-options_chg_pseudo',
            65  => '65-options_rpx',
            
            200 => '200-invit',
    );
}

/*  *EOF*   */


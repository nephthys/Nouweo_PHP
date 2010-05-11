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
        0   => '0-admin',
        1   => '1-vars_lang',
        200 => '200-gestion_droit',
        299 => '299-gestion_grp',
        300 => '300-edit_grp',
        301 => '301-chg_mbr_grp',
        302 => '302-refresh_cache',
        310 => '310-edit_perms_grp',
    );
}

/*  *EOF*   */

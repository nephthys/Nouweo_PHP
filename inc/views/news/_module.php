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
        0   => '0-homepage',
        5   => '5-suivis',
        
        10  => '10-view_news',
        11  => '11-alert_news',
        12  => '12-solve_alert',
        13  => '13-view_alerts',
        15  => '15-cat_news',
        
        16  => '16-list_versions',
        17  => '17-restore_vrs',
        18  => '18-delete_vrs',
        
        20  => '20-logs_news',
        21  => '21-logs_admin',
        
        25  => '25-take_fav',
        26  => '26-vote_news',
        
        30  => '30-post_cmt',
        32  => '32-delete_cmt',
        34  => '34-vote_cmt',
        
        50  => '50-create_brouillon',
        55  => '55-propose_news',
        60  => '60-edit_news',
        65  => '65-delete_news',
        
        70  => '70-redaction',
        80  => '80-attente',
        
        90  => '90-list_tags',
        95  => '95-show_nw_actu',
    );
}

/*  *EOF*   */

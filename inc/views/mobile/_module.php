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
        // News
        0   => '0-homepage',
        1   => '1-search',
        2   => '2-une',
        4   => '4-view_categories',
        5   => '5-tags',
        6   => '6-list_news_cat',
        10  => '10-view_news',
        11  => '11-coms_news',
        70  => '70-news_redaction',
        80  => '80-vote_news',
        // Users (+100)
        110 => '110-login',
        120 => '120-disconnect',
    );
}

/*  *EOF*   */

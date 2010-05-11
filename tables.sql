-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 11 Mai 2010 à 20:59
-- Version du serveur: 5.0.51
-- Version de PHP: 5.2.6-1+lenny3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `nouweo`
--

-- --------------------------------------------------------

--
-- Structure de la table `nw_abonnes`
--

CREATE TABLE IF NOT EXISTS `nw_abonnes` (
  `a_id` mediumint(9) NOT NULL auto_increment,
  `a_id_membre` mediumint(9) NOT NULL,
  `a_email` varchar(40) NOT NULL,
  `a_date` datetime NOT NULL,
  `a_ip` int(11) unsigned NOT NULL,
  `a_token` varchar(32) NOT NULL,
  PRIMARY KEY  (`a_id`),
  KEY `a_id_membre` (`a_id_membre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_ban`
--

CREATE TABLE IF NOT EXISTS `nw_ban` (
  `b_id` int(8) NOT NULL auto_increment,
  `b_id_membre` int(8) NOT NULL,
  `b_id_modo` int(8) NOT NULL,
  `b_date` datetime NOT NULL,
  `b_end` datetime NOT NULL,
  `b_is_end` tinyint(1) NOT NULL,
  `b_motif` text character set latin1 NOT NULL,
  `b_old_group` tinyint(1) NOT NULL,
  PRIMARY KEY  (`b_id`),
  KEY `b_id_membre` (`b_id_membre`,`b_id_modo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_ban_ip`
--

CREATE TABLE IF NOT EXISTS `nw_ban_ip` (
  `b_ip` int(10) unsigned NOT NULL,
  `b_date` datetime NOT NULL,
  `b_motif` text character set latin1 NOT NULL,
  KEY `b_ip` (`b_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_categories`
--

CREATE TABLE IF NOT EXISTS `nw_categories` (
  `c_id` smallint(6) NOT NULL auto_increment,
  `c_nom` varchar(50) character set latin1 NOT NULL,
  `c_rewrite` varchar(30) NOT NULL,
  `c_nbr_news` tinyint(5) NOT NULL,
  `c_position` tinyint(10) NOT NULL,
  `c_etat` tinyint(1) NOT NULL,
  `c_image` varchar(30) character set latin1 NOT NULL,
  `c_desc` text character set latin1 NOT NULL,
  `c_couleur` varchar(10) NOT NULL,
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_coms_lu`
--

CREATE TABLE IF NOT EXISTS `nw_coms_lu` (
  `c_id_news` int(8) NOT NULL,
  `c_id_membre` int(8) NOT NULL,
  `c_last_com` int(8) NOT NULL,
  PRIMARY KEY  (`c_id_news`,`c_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_droits`
--

CREATE TABLE IF NOT EXISTS `nw_droits` (
  `droit_nom` varchar(50) character set latin1 NOT NULL,
  `droit_groupe` mediumint(9) NOT NULL,
  `droit_valeur` mediumint(9) NOT NULL,
  UNIQUE KEY `droit_nom` (`droit_nom`,`droit_groupe`),
  KEY `droit_groupe` (`droit_groupe`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `nw_droits`
--

INSERT INTO `nw_droits` (`droit_nom`, `droit_groupe`, `droit_valeur`) VALUES
('can_create_brouillon', 4, 1),
('quota_max_size_img', 4, 0),
('can_edit_mynews_redac', 4, 1),
('can_edit_news_redac', 4, 1),
('can_edit_mynews_online', 4, 1),
('can_edit_news_online', 4, 0),
('can_delete_mynews', 4, 1),
('can_delete_news', 4, 0),
('mod_news_status', 4, 0),
('can_vote', 4, 1),
('can_delete_my_news', 4, 1),
('can_change_version_my_news', 4, 1),
('can_change_version_all_news', 4, 0),
('can_delete_version', 4, 0),
('can_post_comment', 4, 1),
('can_edit_my_comments', 4, 1),
('can_edit_all_comments', 4, 0),
('edit_hidden_comments', 4, 0),
('can_del_my_comments', 4, 1),
('can_del_all_comments', 4, 0),
('can_manage_tags', 4, 0),
('can_see_admin', 4, 0),
('can_see_ip', 4, 0),
('manage_articles', 4, 0),
('can_create_brouillon', 1, 1),
('quota_max_size_img', 1, 0),
('can_edit_mynews_redac', 1, 1),
('can_edit_news_redac', 1, 1),
('can_edit_mynews_online', 1, 1),
('can_edit_news_online', 1, 1),
('can_delete_mynews', 1, 1),
('can_delete_news', 1, 1),
('mod_news_status', 1, 1),
('can_vote', 1, 1),
('can_delete_my_news', 1, 1),
('can_change_version_my_news', 1, 1),
('can_change_version_all_news', 1, 1),
('can_delete_version', 1, 1),
('can_post_comment', 1, 1),
('can_edit_my_comments', 1, 1),
('can_edit_all_comments', 1, 1),
('edit_hidden_comments', 1, 1),
('can_del_my_comments', 1, 1),
('can_del_all_comments', 1, 1),
('can_manage_tags', 1, 1),
('can_see_admin', 1, 1),
('can_see_ip', 1, 1),
('manage_articles', 1, 1),
('can_create_brouillon', 8, 1),
('quota_max_size_img', 8, 0),
('can_edit_mynews_redac', 8, 1),
('can_edit_news_redac', 8, 1),
('can_edit_mynews_online', 8, 1),
('can_edit_news_online', 8, 1),
('can_delete_mynews', 8, 1),
('can_delete_news', 8, 1),
('mod_news_status', 8, 1),
('can_vote', 8, 1),
('can_delete_my_news', 8, 1),
('can_change_version_my_news', 8, 1),
('can_change_version_all_news', 8, 1),
('can_delete_version', 8, 1),
('can_post_comment', 8, 1),
('can_edit_my_comments', 8, 1),
('can_edit_all_comments', 8, 1),
('edit_hidden_comments', 8, 1),
('can_del_my_comments', 8, 1),
('can_del_all_comments', 8, 1),
('can_manage_tags', 8, 1),
('can_see_admin', 8, 1),
('can_see_ip', 8, 0),
('manage_articles', 8, 0),
('manage_cats', 1, 1),
('solve_alertes', 1, 1),
('edit_vars_lang', 1, 1),
('ban_ip', 1, 1),
('ban_mbr', 1, 1),
('add_mbr', 1, 1),
('search_mail', 1, 1),
('valid_mbr', 1, 1),
('manage_groups', 1, 1),
('change_mbr_grp', 1, 1),
('refresh_cache_droits', 1, 1),
('manage_sdg', 1, 1),
('edit_vars_lang', 8, 0),
('ban_ip', 8, 0),
('ban_mbr', 8, 0),
('add_mbr', 8, 0),
('search_mail', 8, 0),
('valid_mbr', 8, 0),
('manage_groups', 8, 0),
('change_mbr_grp', 8, 0),
('refresh_cache_droits', 8, 0),
('manage_sdg', 8, 0),
('edit_vars_lang', 4, 0),
('ban_ip', 4, 0),
('ban_mbr', 4, 0),
('add_mbr', 4, 0),
('search_mail', 4, 0),
('valid_mbr', 4, 0),
('manage_groups', 4, 0),
('change_mbr_grp', 4, 0),
('refresh_cache_droits', 4, 0),
('manage_sdg', 4, 0),
('can_create_brouillon', 9, 0),
('quota_max_size_img', 9, 0),
('can_edit_mynews_redac', 9, 0),
('can_edit_news_redac', 9, 0),
('can_edit_mynews_online', 9, 0),
('can_edit_news_online', 9, 0),
('can_delete_mynews', 9, 0),
('can_delete_news', 9, 0),
('mod_news_status', 9, 0),
('can_vote', 9, 1),
('can_delete_my_news', 9, 0),
('can_change_version_my_news', 9, 0),
('can_change_version_all_news', 9, 0),
('can_delete_version', 9, 0),
('manage_cats', 9, 0),
('solve_alertes', 9, 0),
('can_post_comment', 9, 0),
('can_edit_my_comments', 9, 0),
('can_edit_all_comments', 9, 0),
('edit_hidden_comments', 9, 0),
('can_del_my_comments', 9, 0),
('can_del_all_comments', 9, 0),
('can_manage_tags', 9, 0),
('can_see_admin', 9, 0),
('edit_vars_lang', 9, 0),
('can_see_ip', 9, 0),
('ban_ip', 9, 0),
('ban_mbr', 9, 0),
('add_mbr', 9, 0),
('search_mail', 9, 0),
('valid_mbr', 9, 0),
('manage_groups', 9, 0),
('change_mbr_grp', 9, 0),
('refresh_cache_droits', 9, 0),
('manage_articles', 9, 0),
('manage_sdg', 9, 0),
('can_create_brouillon', 12, 1),
('quota_max_size_img', 12, 0),
('can_edit_mynews_redac', 12, 1),
('can_edit_news_redac', 12, 1),
('can_edit_mynews_online', 12, 1),
('can_edit_news_online', 12, 0),
('can_delete_mynews', 12, 1),
('can_delete_news', 12, 0),
('mod_news_status', 12, 0),
('can_vote', 12, 1),
('can_delete_my_news', 12, 1),
('can_change_version_my_news', 12, 1),
('can_change_version_all_news', 12, 0),
('can_delete_version', 12, 0),
('manage_cats', 12, 1),
('solve_alertes', 12, 0),
('can_post_comment', 12, 1),
('can_edit_my_comments', 12, 1),
('can_edit_all_comments', 12, 0),
('edit_hidden_comments', 12, 0),
('can_del_my_comments', 12, 1),
('can_del_all_comments', 12, 0),
('can_manage_tags', 12, 1),
('can_see_admin', 12, 0),
('edit_vars_lang', 12, 0),
('can_see_ip', 12, 0),
('ban_ip', 12, 0),
('ban_mbr', 12, 0),
('add_mbr', 12, 0),
('search_mail', 12, 0),
('valid_mbr', 12, 0),
('manage_groups', 12, 0),
('change_mbr_grp', 12, 0),
('refresh_cache_droits', 12, 0),
('manage_articles', 12, 0),
('manage_sdg', 12, 0),
('solve_alertes', 4, 0),
('can_create_brouillon', 13, 1),
('quota_max_size_img', 13, 0),
('can_edit_mynews_redac', 13, 1),
('can_edit_news_redac', 13, 1),
('can_edit_mynews_online', 13, 0),
('can_edit_news_online', 13, 0),
('can_delete_mynews', 13, 1),
('can_delete_news', 13, 0),
('mod_news_status', 13, 0),
('can_vote', 13, 1),
('can_delete_my_news', 13, 1),
('can_change_version_my_news', 13, 1),
('can_change_version_all_news', 13, 0),
('can_delete_version', 13, 0),
('manage_cats', 13, 0),
('solve_alertes', 13, 1),
('can_post_comment', 13, 1),
('can_edit_my_comments', 13, 1),
('can_edit_all_comments', 13, 1),
('edit_hidden_comments', 13, 1),
('can_del_my_comments', 13, 1),
('can_del_all_comments', 13, 1),
('can_manage_tags', 13, 0),
('can_see_admin', 13, 1),
('edit_vars_lang', 13, 0),
('can_see_ip', 13, 1),
('ban_ip', 13, 1),
('ban_mbr', 13, 1),
('add_mbr', 13, 1),
('search_mail', 13, 1),
('valid_mbr', 13, 1),
('manage_groups', 13, 0),
('change_mbr_grp', 13, 0),
('refresh_cache_droits', 13, 0),
('manage_articles', 13, 0),
('manage_sdg', 13, 0),
('manage_cats', 8, 0),
('manage_cats', 4, 0),
('view_histo_all_news', 1, 1),
('can_create_brouillon', 10, 0),
('quota_max_size_img', 10, 0),
('can_edit_mynews_redac', 10, 0),
('can_edit_news_redac', 10, 0),
('can_edit_mynews_online', 10, 0),
('can_edit_news_online', 10, 0),
('can_delete_mynews', 10, 0),
('can_delete_news', 10, 0),
('mod_news_status', 10, 0),
('can_vote', 10, 0),
('can_delete_my_news', 10, 0),
('can_change_version_my_news', 10, 0),
('can_change_version_all_news', 10, 0),
('can_delete_version', 10, 0),
('manage_cats', 10, 0),
('solve_alertes', 10, 0),
('can_post_comment', 10, 0),
('can_edit_my_comments', 10, 0),
('can_edit_all_comments', 10, 0),
('edit_hidden_comments', 10, 0),
('can_del_my_comments', 10, 0),
('can_del_all_comments', 10, 0),
('can_manage_tags', 10, 0),
('can_see_admin', 10, 0),
('edit_vars_lang', 10, 0),
('can_see_ip', 10, 0),
('ban_ip', 10, 0),
('ban_mbr', 10, 0),
('add_mbr', 10, 0),
('search_mail', 10, 0),
('valid_mbr', 10, 0),
('manage_groups', 10, 0),
('change_mbr_grp', 10, 0),
('refresh_cache_droits', 10, 0),
('manage_articles', 10, 0),
('manage_sdg', 10, 0),
('view_histo_all_news', 10, 0),
('view_histo_all_news', 13, 1),
('view_histo_all_news', 8, 1);

-- --------------------------------------------------------

--
-- Structure de la table `nw_extern_tracker`
--

CREATE TABLE IF NOT EXISTS `nw_extern_tracker` (
  `t_id` mediumint(9) NOT NULL auto_increment,
  `t_id_membre` mediumint(9) NOT NULL,
  `t_type` varchar(4) NOT NULL,
  `t_date` datetime NOT NULL,
  `t_ip` int(11) unsigned NOT NULL,
  `t_nb_clics` smallint(6) NOT NULL,
  `r_referer` varchar(200) NOT NULL,
  PRIMARY KEY  (`t_id`),
  UNIQUE KEY `t_type` (`t_type`,`t_ip`),
  KEY `t_id_membre` (`t_id_membre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_groups`
--

CREATE TABLE IF NOT EXISTS `nw_groups` (
  `g_id` int(6) NOT NULL auto_increment,
  `g_nom` varchar(50) character set latin1 NOT NULL,
  `g_titre` varchar(50) character set latin1 NOT NULL,
  `g_staff` tinyint(1) NOT NULL,
  `g_icone` varchar(20) NOT NULL,
  `g_couleur` tinyint(1) NOT NULL,
  PRIMARY KEY  (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_logs_recherche`
--

CREATE TABLE IF NOT EXISTS `nw_logs_recherche` (
  `l_id_membre` mediumint(9) NOT NULL,
  `l_date` datetime NOT NULL,
  `l_mot_cle` varchar(200) NOT NULL,
  `l_ip` int(10) unsigned NOT NULL,
  `l_nbr_results` smallint(6) NOT NULL,
  KEY `l_id_membre` (`l_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_members`
--

CREATE TABLE IF NOT EXISTS `nw_members` (
  `u_id` mediumint(9) NOT NULL auto_increment,
  `u_pseudo` varchar(50) character set latin1 NOT NULL,
  `u_alias` varchar(50) NOT NULL,
  `u_identifier` text NOT NULL,
  `u_password` varchar(40) character set latin1 NOT NULL,
  `u_email` varchar(40) character set latin1 NOT NULL,
  `u_group` tinyint(3) NOT NULL,
  `u_date_register` datetime NOT NULL,
  `u_last_visit` datetime NOT NULL,
  `u_active` tinyint(1) NOT NULL,
  `u_code_act` varchar(32) character set latin1 NOT NULL,
  `u_ident_unique` varchar(40) NOT NULL,
  `u_ip` int(10) unsigned NOT NULL,
  `u_avatar` varchar(300) character set latin1 NOT NULL,
  `u_decalage` varchar(9) character set latin1 NOT NULL,
  `u_bio` text character set latin1 NOT NULL,
  `u_bio_court` varchar(500) NOT NULL,
  `u_code_pub` varchar(20) character set latin1 NOT NULL,
  `u_date_naissance` date NOT NULL,
  `u_localisation` varchar(100) character set latin1 NOT NULL,
  `u_karma` mediumint(9) NOT NULL,
  PRIMARY KEY  (`u_id`),
  KEY `u_karma` (`u_karma`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_members_ip`
--

CREATE TABLE IF NOT EXISTS `nw_members_ip` (
  `ip_ip` int(10) unsigned NOT NULL,
  `ip_id_mbr` int(11) NOT NULL,
  `ip_date_begin` datetime NOT NULL,
  `ip_date_last` datetime NOT NULL,
  PRIMARY KEY  (`ip_ip`,`ip_id_mbr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_members_stats`
--

CREATE TABLE IF NOT EXISTS `nw_members_stats` (
  `s_id_membre` mediumint(9) NOT NULL,
  `s_nb_news` smallint(6) NOT NULL,
  `s_nb_contrib` smallint(6) NOT NULL,
  `s_nb_coms` smallint(6) NOT NULL,
  `s_nb_votes` smallint(6) NOT NULL,
  KEY `s_id` (`s_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news`
--

CREATE TABLE IF NOT EXISTS `nw_news` (
  `n_id` mediumint(9) NOT NULL auto_increment,
  `n_id_auteur` mediumint(9) NOT NULL,
  `n_id_cat` mediumint(9) NOT NULL,
  `n_id_image` mediumint(9) NOT NULL,
  `n_titre` varchar(200) character set latin1 NOT NULL,
  `n_date` datetime NOT NULL,
  `n_last_mod` datetime NOT NULL,
  `n_last_version` mediumint(9) NOT NULL,
  `n_vues` smallint(6) NOT NULL,
  `n_etat` tinyint(4) NOT NULL default '1',
  `n_private` tinyint(1) NOT NULL,
  `n_com_closed` tinyint(1) NOT NULL,
  `n_nbr_coms` smallint(6) NOT NULL,
  `n_last_com` mediumint(9) NOT NULL,
  `n_image` mediumint(9) NOT NULL,
  `n_nb_votes` smallint(6) NOT NULL,
  `n_nb_votes_neg` smallint(6) NOT NULL,
  `n_nb_versions` mediumint(9) NOT NULL,
  `n_resume` varchar(510) NOT NULL,
  `n_breve` tinyint(1) NOT NULL,
  `n_src_url` text NOT NULL,
  `n_src_nom` varchar(200) NOT NULL,
  `n_nb_src` smallint(6) NOT NULL,
  `n_miniurl` varchar(6) NOT NULL,
  PRIMARY KEY  (`n_id`),
  KEY `n_id_image` (`n_id_image`),
  FULLTEXT KEY `n_titre` (`n_titre`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_alerts`
--

CREATE TABLE IF NOT EXISTS `nw_news_alerts` (
  `a_id` int(8) NOT NULL auto_increment,
  `a_id_news` int(8) NOT NULL,
  `a_date` datetime NOT NULL,
  `a_auteur` int(8) NOT NULL,
  `a_admin` int(11) default NULL,
  `a_ip` int(10) unsigned NOT NULL,
  `a_texte` text character set latin1 NOT NULL,
  `a_motif` tinyint(1) NOT NULL,
  `a_solved` tinyint(1) NOT NULL,
  PRIMARY KEY  (`a_id`),
  KEY `e_id_news` (`a_id_news`),
  KEY `a_admin` (`a_admin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_commentaires`
--

CREATE TABLE IF NOT EXISTS `nw_news_commentaires` (
  `c_id` mediumint(9) NOT NULL auto_increment,
  `c_id_news` mediumint(9) NOT NULL,
  `c_id_membre` mediumint(9) NOT NULL,
  `c_texte` text character set latin1 NOT NULL,
  `c_date` datetime NOT NULL,
  `c_edit_membre` mediumint(9) NOT NULL,
  `c_edit_date` datetime NOT NULL,
  `c_ip` int(10) NOT NULL,
  `c_plussoie` smallint(6) NOT NULL,
  `c_masque` tinyint(1) NOT NULL,
  `c_masque_raison` varchar(150) NOT NULL,
  `c_masque_modo` mediumint(9) NOT NULL,
  PRIMARY KEY  (`c_id`),
  KEY `c_id_news` (`c_id_news`,`c_id_membre`,`c_edit_membre`),
  KEY `c_masque_modo` (`c_masque_modo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_edits`
--

CREATE TABLE IF NOT EXISTS `nw_news_edits` (
  `ed_id_membre` mediumint(9) NOT NULL,
  `ed_id_news` mediumint(9) NOT NULL,
  `ed_date` datetime NOT NULL,
  `ed_id_rev` mediumint(9) NOT NULL,
  `ed_done` tinyint(1) NOT NULL,
  UNIQUE KEY `ed_id_membre_2` (`ed_id_membre`,`ed_id_news`),
  KEY `ed_id_membre` (`ed_id_membre`,`ed_id_news`,`ed_id_rev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_favs`
--

CREATE TABLE IF NOT EXISTS `nw_news_favs` (
  `f_id_membre` mediumint(9) NOT NULL,
  `f_id_news` mediumint(9) NOT NULL,
  KEY `f_id_membre` (`f_id_membre`,`f_id_news`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_flags`
--

CREATE TABLE IF NOT EXISTS `nw_news_flags` (
  `f_id_news` mediumint(9) NOT NULL,
  `f_id_membre` mediumint(9) NOT NULL,
  `f_type` tinyint(5) NOT NULL,
  KEY `f_id_news` (`f_id_news`,`f_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_images`
--

CREATE TABLE IF NOT EXISTS `nw_news_images` (
  `i_id` mediumint(9) NOT NULL auto_increment,
  `i_id_news` mediumint(9) NOT NULL,
  `i_nom` varchar(11) NOT NULL,
  `i_date` datetime NOT NULL,
  `i_ordre` smallint(6) NOT NULL,
  PRIMARY KEY  (`i_id`),
  KEY `i_id_news` (`i_id_news`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_logs`
--

CREATE TABLE IF NOT EXISTS `nw_news_logs` (
  `l_id_news` mediumint(9) NOT NULL,
  `l_id_membre` mediumint(9) NOT NULL,
  `l_titre` varchar(200) NOT NULL,
  `l_action` smallint(6) NOT NULL,
  `l_texte` text NOT NULL,
  `l_date` datetime NOT NULL,
  `l_ip` int(10) unsigned NOT NULL,
  KEY `l_id_news` (`l_id_news`,`l_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_lus`
--

CREATE TABLE IF NOT EXISTS `nw_news_lus` (
  `r_id_news` mediumint(9) NOT NULL,
  `r_id_membre` mediumint(9) NOT NULL,
  `r_type` tinyint(3) NOT NULL,
  `r_date` datetime NOT NULL,
  KEY `r_id_news` (`r_id_news`,`r_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_src`
--

CREATE TABLE IF NOT EXISTS `nw_news_src` (
  `src_id_news` mediumint(9) NOT NULL,
  `src_media` varchar(50) NOT NULL,
  `src_url` varchar(255) NOT NULL,
  `src_order` smallint(6) NOT NULL,
  KEY `src_id_news` (`src_id_news`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_versions`
--

CREATE TABLE IF NOT EXISTS `nw_news_versions` (
  `v_id` mediumint(9) NOT NULL auto_increment,
  `v_id_news` mediumint(9) NOT NULL,
  `v_id_membre` mediumint(9) NOT NULL,
  `v_texte` text character set latin1 NOT NULL,
  `v_date` datetime NOT NULL,
  `v_ip` int(10) unsigned NOT NULL,
  `v_raison` varchar(100) NOT NULL,
  `v_nb_mots` smallint(6) NOT NULL,
  `v_diff_mots` smallint(6) NOT NULL,
  `v_number` smallint(6) NOT NULL,
  `v_mineure` tinyint(1) NOT NULL,
  PRIMARY KEY  (`v_id`),
  KEY `v_id_news` (`v_id_news`,`v_id_membre`),
  FULLTEXT KEY `v_texte` (`v_texte`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_news_vote`
--

CREATE TABLE IF NOT EXISTS `nw_news_vote` (
  `v_id` int(8) NOT NULL auto_increment,
  `v_id_membre` int(8) NOT NULL,
  `v_ip` int(10) unsigned NOT NULL,
  `v_date` datetime NOT NULL,
  `v_id_news` int(8) NOT NULL,
  `v_etat` tinyint(3) NOT NULL,
  `v_type` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`v_id`),
  KEY `v_id_news` (`v_id_news`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_plussoies`
--

CREATE TABLE IF NOT EXISTS `nw_plussoies` (
  `p_id_com` mediumint(9) NOT NULL,
  `p_id_membre` mediumint(9) NOT NULL,
  `p_date` datetime NOT NULL,
  `p_ip` int(10) unsigned NOT NULL,
  KEY `p_id_com` (`p_id_com`,`p_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_sondages`
--

CREATE TABLE IF NOT EXISTS `nw_sondages` (
  `s_id` tinyint(5) NOT NULL auto_increment,
  `s_id_mbr` tinyint(5) NOT NULL default '0',
  `s_question` varchar(120) character set latin1 NOT NULL default '',
  `s_nbr_votes` tinyint(4) NOT NULL default '0',
  `s_debut` datetime NOT NULL default '0000-00-00 00:00:00',
  `s_fin` datetime NOT NULL default '0000-00-00 00:00:00',
  `s_votes_blanc` tinyint(4) NOT NULL default '0',
  `s_section` tinyint(3) NOT NULL,
  `s_etat` tinyint(1) NOT NULL,
  PRIMARY KEY  (`s_id`),
  KEY `s_id_mbr` (`s_id_mbr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_sondages_quest`
--

CREATE TABLE IF NOT EXISTS `nw_sondages_quest` (
  `q_id_rep` tinyint(5) NOT NULL auto_increment,
  `q_id_sond` tinyint(3) NOT NULL default '0',
  `q_nom` varchar(100) character set latin1 NOT NULL default '',
  `q_nbr_votes` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`q_id_rep`),
  KEY `q_id_sond` (`q_id_sond`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_sondages_votes`
--

CREATE TABLE IF NOT EXISTS `nw_sondages_votes` (
  `v_id_sond` tinyint(5) NOT NULL default '0',
  `v_id_rep` tinyint(5) NOT NULL default '0',
  `v_ip` int(10) unsigned NOT NULL,
  `v_id_mbr` tinyint(5) NOT NULL default '0',
  `v_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `v_blanc` tinyint(1) NOT NULL,
  KEY `v_id_sond` (`v_id_sond`,`v_id_rep`,`v_id_mbr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_tags`
--

CREATE TABLE IF NOT EXISTS `nw_tags` (
  `t_id_news` mediumint(9) NOT NULL,
  `t_tag` varchar(30) character set latin1 NOT NULL,
  `t_position` smallint(6) NOT NULL,
  KEY `t_id_news` (`t_id_news`),
  FULLTEXT KEY `t_tag` (`t_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `nw_w_live_parts`
--

CREATE TABLE IF NOT EXISTS `nw_w_live_parts` (
  `part_id_live` mediumint(9) NOT NULL,
  `part_id_membre` mediumint(9) NOT NULL,
  KEY `part_id_live` (`part_id_live`,`part_id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `nw_w_live_posts`
--

CREATE TABLE IF NOT EXISTS `nw_w_live_posts` (
  `post_id` mediumint(9) NOT NULL auto_increment,
  `post_id_membre` mediumint(9) NOT NULL,
  `post_id_live` mediumint(9) NOT NULL,
  `post_date` datetime NOT NULL,
  `post_contenu` text NOT NULL,
  `post_ip` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`post_id`),
  KEY `post_id_membre` (`post_id_membre`,`post_id_live`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

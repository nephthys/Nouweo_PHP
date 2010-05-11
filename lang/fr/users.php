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

Nw::$lang['users'] = array(
    'users'                 => 'Membres',
    
    /**
    *   Connexion / Inscription
    **/
    'title_connexion'       => 'Connexion',
    'fa_connexion'          => 'Connexion',
    'form_connexion'        => 'Entrez vos identifiants',
    'title_inscription'     => 'Inscription',
    'fa_inscription'        => 'Inscription',
    'form_inscription'      => 'Formulaire d\'inscription',
    
    'nickname'              => 'Nom d\'utilisateur',
    'nickname_mobile'       => 'Pseudo',
    'password'              => 'Mot de passe',
    'password_mobile'       => 'Pass',
    'repeat_password'       => 'Repéter le mot de passe',
    'email'                 => 'Adresse e-mail',
    
    'remember_me'           => 'Se souvenir de moi ? (Utilise les cookies)',
    'lost_password'             => 'J\'ai oublié mon mot de passe',
    '50_caract_nick'            => '50 caractères maximum',
    
    'mesure_antispam'       => 'Mesure anti-spam',
    'mesure_antispam_txt'   => 'Méthode afin d\'éviter l\'inscription de robots',
    'register_idents'       => 'Vos identifiants sur le site',
    'code_antispam'         => 'Recopier le code',
    
    'login_submit'          => 'Se connecter',
    'register_submit'       => 'S\'inscrire',
    
    'welcome_user'          => 'Bienvenue %s, vous êtes à présent connecté.',
    
    'not_active'            => 'Votre compte n\'est pas actif. Vous ne pouvez pas vous connecter.',
    'account_no_exist'      => 'Votre pseudo ou votre mot de passe est incorrect.',
    'champ_obligatoire'     => 'Tous les champs sont obligatoires.',
    
    'accept_rules'          => 'En cochant cette case, 
    j\'accepte les <a href="help-rules.html">règles de 
    %s</a>',
    'email_already_used'    => 'Cette adresse email est déjà utilisée.',
    'format_email_false'    => 'Le format de l\'adresse email est incorrect.',
    'wrong_antispam'        => 'Le code anti-spam est mauvais.',
    'sames_password'        => 'Les mots de passe doivent être identiques.',
    'nickname_used'         => 'Ce pseudo est déjà utilisé',
    'confirm_inscription'   => '[%s] Confirmez votre inscription',
    'accept_rules_msg'      => 'Vous n\'avez pas accepté les règles.',
    
    'mail_confirm_insc'     => 'Bonjour %s,<br /><br />
        Vous recevez ce message car vous vous êtes inscrit sur le site <a href="%s">%s</a>, il faut
        désormais activer votre compte afin de pouvoir l\'utiliser.<br /><br />
        Pour ce faire, il suffit de cliquer <a href="%s">ce lien</a> ou de copier/coller cette URL dans la barre d\'adresse de votre navigateur : 
        <a href="%s">%s</a>',
    'success_register'      => 'Vous êtes bien inscrit, vous allez recevoir un email dans quelques minutes afin d\'activer votre compte.',
    
    'disconnect_msg'        => 'Vous êtes bien déconnecté. A bientôt !',
    
    'title_mail_lost_pwd'   => '[%s] Mot de passe oublié',
    'mail_oubli_pwd'        => 'Bonjour %s,<br /><br />
        Vous avez fait une demande suite à l\'oubli de votre mot de passe. Pour redéfinir celui-ci, suivez 
        <a href="%s">ce lien</a> ou copier/coller le lien suivant dans votre barre
        d\'adresse: <a href="%s">%s</a>',
    'send_mail_lost'        => 'Un mail vient de vous être envoyé avec les instructions à suivre.',
    'email_aucun_mbr'       => 'Cette adresse e-mail n\'appartient à aucun de nos membres.',
    'legend_form_lp'        => 'Oubli de votre mot de passe',
    'title_lost_pwd'        => 'Mot de passe oublié',
    'txt_lost_pwd'          => 'Saisissez votre adresse e-mail',
    'txt_lost_pwd_inc'      => 'Indiquez l\'adresse email que vous avez choisi lors de l\'inscription. Un message 
    vous sera envoyé à cette même adresse afin de choisir un nouveau mot de passe.',
    
    'redef_mdp_echoue'      => 'La redéfinition de mot de passe a échouée.',
    'new_redef_pwd'         => 'Votre mot de passe a bien été redéfini. Vous pouvez à présent vous connecter avec.',
    'title_redef_pass'      => 'Redéfinition de votre mot de passe',
    'legend_redef_pass'     => 'Changement de votre mot de passe',
    
    'new_password'          => 'Nouveau mot de passe',
    'repeat_new_password'   => 'Répéter le nouveau mot de passe',
    
    'compte_valide'         => 'Votre compte vient d\'être validé, vous pouvez dès à présent vous connecter avec.', 
    'compte_valid_error'    => 'Erreur fatale dans la validation du compte.',
    
    'txt_register_rpx'      => 'Vous êtes en train de vous inscrire avec votre compte <strong>%s</strong> &mdash; %s. ',
    'end_inscription'       => 'Terminer l\'inscription',
    'title_register_rpx'    => 'Inscription via %s',
    'need_def_email'        => 'Renseignez une adresse email valide pour terminer l\'inscription',
    'need_def_nick'         => 'Le pseudo <strong>%s</strong> est déjà utilisé, choississez-en un autre',
    'inscrit_rpx_no_valid'  => 'Votre compte %s &mdash; %s a bien été créé et vous êtes désormais connecté.',
    'inscrit_rpx_with_val'  => 'Votre compte %s &mdash; %s a bien été créé, vous allez recevoir un email dans quelques minutes afin d\'activer votre compte.',
    'fast_login_register'   => 'Connexion ou inscription <span class="tpetit">recommandée</span>',
    'txt_fast_signup'       => 'Vous pouvez utiliser <a href="users-10.html">l\'inscription rapide</a> (quelques secondes) via des services externes tels que Google, Twitter ou encore Facebook. C\'est facile et rapide, profitez-en !
    <a href="help-faq.html#fast_signup"><strong>En savoir plus</strong></a>.',
    
    'login_rpx_title'       => 'Connexion ou inscription rapide',
    
    /**
    *   Liste des membres
    **/
    'members_section'       => 'Membres',
    'list_members'          => 'Liste des membres',
    'all_members'           => 'Tous les membres',
    
    'avatar'                => 'Avatar',
    'pseudo'                => 'Pseudo du membre',
    'date_inscription'      => 'Date d\'inscription',
    'last_visit'            => 'Dernière visite',
    'activite_site'         => 'Activité sur le site',
    'localisation'          => 'Localisation',
    'no_local'              => 'Non spécifiée',
    'nombre_actu'           => '%d actualité',
    'nombre_actus'          => '%d actualités',
    'nombre_contrib'        => '%d contribution',
    'nombre_contribs'       => '%d contributions',
    'nombre_com'            => '%d commentaire',
    'nombre_coms'           => '%d commentaires',
    
    'last_inscrits'         => 'Derniers inscrits',
    'opt_tri'               => 'Options de tri',
    'type_tri'              => 'Tri par',
    'recherche_mbr'         => 'Rechercher un membre',
    'by_group'              => 'Par groupe',
    'by_pseudo'             => 'Par pseudo',
    'by_local'              => 'Par localisation',
    'submit_search_users'   => 'Rechercher',
    
    'all_groups'            => 'Tous les groupes',
    'order_aff'             => 'Ordre d\'affichage',
    'ordre_asc_desc'        => 'Ordre',
    
    'tri_order'             => array(
        0       => 'Date d\'inscription',
        1       => 'Dernière visite',
        2       => 'Pseudo',
        3       => 'Activité sur le site',
    ),
    
    'array_asc_desc'        => array(
        'asc'   => 'Croissant',
        'desc'  => 'Décroissant',
    ),
    
    'mbr_dont_exist'        => 'Ce membre n\'existe pas.',
    'read_more_bio'         => 'Afficher toute la biographie',
    
    /**
    *   Administration
    **/
    'add_mbr'               => 'Inscrire un membre',
    'ban_ip'                => 'Bannir une adresse IP',
    'ban_ip_list'           => 'Liste des adresses IP bannies',
    'check_ip'              => 'Analyser une adresse IP',
    'ban_mbr'               => 'Bannir un membre',
    'search_mail'           => 'Rechercher une adresse mail',
    'mbr_non_valides'       => 'Afficher les comptes en cours de validation',

    'ip_to_analyze'         => 'Adresse IP à analyser :',
    'mbr_ip_found'          => 'Voici la liste des membres trouvés avec l\'IP suivante : ',
    'ban_this_ip'           => 'Bannir cette IP',
    'last_ip_known'         => 'Dernière IP connue',

    'error_cant_see_ip'     => 'Vous n\'avez pas le droit de voir les adresses IP.',
    'error_cant_ban_ip'     => 'Vous n\'avez pas le droit de bannir des adresses IP.',
    'confirm_ban_ip'        => 'L\'adresse IP a bien été bannie.',
    'confirm_deban_ip'      => 'L\'adresse IP a bien été débannie.',
    
    
    /**
    *   Mes options
    **/
    'bio'                   => 'Biographie',
    'my_bio'                => 'Ma biographie',
    
    'decalage_horaire'      => 'Décalage horaire',
    'date_naissance'        => 'Date de naissance',
    
    
    'mes_options_title'     => 'Mes options',
    
    'item_infos_profil'     => 'Informations personnelles',
    'item_avatar'           => 'Avatar',
    'item_pseudo'           => 'Changement de pseudo',
    'item_mdp'              => 'Mot de passe',
    'item_rpx'              => 'Connexion rapide',
    
    'chgt_avatar'           => 'Changement d\'avatar',
    'avatar_byfile'         => 'Depuis votre disque dur...',
    'avatar_byurl'          => 'Depuis l\'URL...',
    
    'text_infos_profil'     => 'Changez toutes les informations concernant votre profil : âge, localisation, biographie, etc.',
    'text_avatar'           => 'Choississez une image qui apparaitra partout sur le site où vous postez.',
    'text_pseudo'           => 'Changez votre pseudo si vous jugez que l\'actuel ne vous convient pas. Attention toutefois à ne pas abuser de cette fonctionnalité.',
    'text_mdp'              => 'Changez facilement et rapidement le mot de passe de votre compte.',
    'text_rpx'              => 'Connectez-vous avec votre compte Google, Facebook, Twitter ou OpenID en quelques secondes !',
    
    'redir_t_infos_profil'  => 'Vos données personnelles ont bien été modifiées.',
    'redir_t_avatar'        => 'Votre avatar vient d\'être modifié.',
    'avatar_false_ext'      => 'Seules les extensions gif, jpg et png sont autorisées pour les avatars.',
    'redir_d_avatar'        => 'Votre avatar a bien été supprimé.',
    
    'delete_avatar'         => 'Supprimer mon avatar',
    
    'not_root_password'     => 'Le mot de passe que vous avez renseigné ne correspond pas à celui de votre compte.',
    'actuel_password'       => 'Votre mot de passe actuel',
    'chg_mdp'               => 'Changement de votre mot de passe',
    'mdp_change'            => 'Votre mot de passe vient d\'être modifié.',
    
    'now_logged_rpx'        => 'Votre compte utilise un compte <strong>%s</strong> pour la connexion',
    'remove_login_rpx'      => 'cliquez ici pour supprimer ce service à la connexion',
    'redir_d_rpx_login'     => 'L\'authentification via %s vient d\'être supprimée.',
    'add_login_rpx'         => 'Pour activer la connexion via Google, Twitter, Facebook ou OpenID, vous devez cliquer sur un de ces services ci-dessous.<br />Il est important
    de signaler que cela ne supprime pas votre mot de passe actuel, vous pourrez donc utiliser le formulaire de connexion normal ou le formulaire RPX.',
    'redir_d_rpx_add'       => 'L\'authentification via %s est désormais activée !',
    'redir_d_ident_exists'  => 'Ce compte %s est déjà utilisé par un autre membre.',
);
?>

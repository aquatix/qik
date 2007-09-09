<?php
/**
 * dictionary.php - Dictionary additions for Qik Admin module
 * v0.2.01 2007-09-09
 * Copyright 2005-2007 mbscholt at aquariusoft.org
 *
 * Qik is the legal property of its developer, Michiel Scholten
 * [mbscholt at aquariusoft.org]
 * Please refer to the COPYRIGHT file distributed with this source distribution.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ($skel['language'] == 'nl')
{
	$skel['dict']['admin_welcome'] = 'Welkom op de Admin pagina. Log in om door te gaan.';
	$skel['dict']['admin_home'] = 'overzicht';
	$skel['dict']['admin_login'] = 'log in';
	$skel['dict']['admin_pages'] = 'Pagina\'s';
	$skel['dict']['admin_galleries'] = 'Galerijen';
	$skel['dict']['admin_editpage'] = 'wijzig pagina';
	$skel['dict']['admin_editpage_explanation'] = 'Kies een pagina om er de teksten van aan te passen:';
	$skel['dict']['admin_editingpage'] = 'Bezig met wijzigen van pagina';
	$skel['dict']['admin_savedpage'] = 'Pagina opgeslagen';
	$skel['dict']['admin_editgallery'] = 'wijzig galerij';
	$skel['dict']['admin_editinggallery'] = 'Bezig met wijzigen van galerij';
	$skel['dict']['admin_editinggallery_explanation'] = 'Elke regel staat voor een foto in de galerij. Voor het =-teken staat de omschrijving, erna staat de bestandsnaam. Door een # voor de regel te zetten, zal de foto worden overgeslagen in het overzicht [oftewel worden genegeerd].';
	$skel['dict']['admin_savedgallery'] = 'Galerij opgeslagen';
	$skel['dict']['admin_loggedout'] = 'Uitgelogged';
	$skel['dict']['admin_backtologin'] = 'Ga terug naar de login page';
	$skel['dict']['admin_back2overview'] = 'Ga terug naar het overzicht';
	$skel['dict']['admin_username'] = 'gebruikersnaam';
	$skel['dict']['admin_password'] = 'wachtwoord';
	$skel['dict']['admin_save'] = 'opslaan';

	//$skel['dict'][''] = '';
} else
{
	/* default to en */
	$skel['dict']['admin_welcome'] = 'Welcome to the Admin page. Please log in to continue.';
	$skel['dict']['admin_home'] = 'overview';
	$skel['dict']['admin_login'] = 'log in';
	$skel['dict']['admin_pages'] = 'Pages';
	$skel['dict']['admin_galleries'] = 'Galleries';
	$skel['dict']['admin_editpage'] = 'edit page';
	$skel['dict']['admin_editpage_explanation'] = 'Choose a page to edit the text off:';
	$skel['dict']['admin_editingpage'] = 'Editing page';
	$skel['dict']['admin_savedpage'] = 'Page saved';
	$skel['dict']['admin_editgallery'] = 'edit gallery';
	$skel['dict']['admin_editinggallery'] = 'Editing gallery';
	$skel['dict']['admin_editinggallery_explanation'] = 'Each line contains the information for one picture in the gallery. To the left of the =-sign is the description, to the right of it the filename. By putting a # at the front of a line, the picture will be skipped in the overview [be ignored].';
	$skel['dict']['admin_savedgallery'] = 'Gallery saved';
	$skel['dict']['admin_loggedout'] = 'Logged out';
	$skel['dict']['admin_backtologin'] = 'Go back to the login page';
	$skel['dict']['admin_back2overview'] = 'Go back to the overview page';
	$skel['dict']['admin_username'] = 'username';
	$skel['dict']['admin_password'] = 'password';
	$skel['dict']['admin_save'] = 'save';

	//$skel['dict'][''] = '';
	//$skel['dict']['gallerykey_x_not_found'] = 'ERROR: gallery "%s" not found';
}
?>

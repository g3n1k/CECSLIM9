<?php
/**
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/* Circulation module submenu items */
// IP based access limitation
do_checkIP('smc');
do_checkIP('smc-circulation');

$menu[] = array('Header', __('Circulation'));
$menu[] = array(__('Start Transaction'), MWB.'circulation/index.php?action=start', __('Start Circulation Transaction Proccess'));
$menu[] = array(__('Quick Return'), MWB.'circulation/quick_return.php', __('Quick Return Collection'));
$menu[] = array(__('Loan Rules'), MWB.'circulation/loan_rules.php', __('View and Modify Circulation Loan Rules'));
$menu[] = array(__('Loan History'), MWB.'reporting/customs/loan_history.php', __('Loan History Overview'));
$menu[] = array(__('Overdued List'), MWB.'reporting/customs/overdued_list.php', __('View Members Having Overdues'));
$menu[] = array(__('Reservation'), MWB.'reporting/customs/reserve_list.php', __('Reservation'));

$menu[] = array('Header', __('Comments'));
$menu[] = array(__('Comments List'), MWB.'circulation/comment.php', __('List Comment'));
$menu[] = array(__('Email Spam'), MWB.'circulation/comment_spam_email.php', __('Email Spam'));
$menu[] = array(__('IP Spam'), MWB.'circulation/comment_spam_ip.php', __('IP Spam'));

$menu[] = array('Header', __('Activity Log'));
$menu[] = array(__('Activity Index'), MWB.'korupsi/al_index.php', __('List of Book activities'));
$menu[] = array(__('Activity Index Lokal'), MWB.'korupsi/al_index_lokal.php', __('List of Local Book activities'));
$menu[] = array(__('Activity Read'), MWB.'korupsi/al_new.php', __('Input Log Activity'));

$menu[] = array('Header', __('Activity Sarasehan'));
$menu[] = array(__('Index'), MWB.'korupsi/as_index.php', __('Show Existing Bibliographic Data'));
$menu[] = array(__('Add New Activity'), MWB.'korupsi/as_new.php', __('Add New Bibliographic Data/Catalog'));
$menu[] = array(__('Show on Landing'), MWB.'korupsi/as_displ.php', __('cek'));

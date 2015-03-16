<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This library includes the basic parts of enrol api.
 * It is available on each page.
 *
 * @package    core
 * @subpackage enrol
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** Course enrol instance enabled. (used in enrol->status) */
define('ENROL_INSTANCE_ENABLED', 0);

/** Course enrol instance disabled, user may enter course if other enrol instance enabled. (used in enrol->status)*/
define('ENROL_INSTANCE_DISABLED', 1);

/** User is active participant (used in user_enrolments->status)*/
define('ENROL_USER_ACTIVE', 0);

/** User participation in course is suspended (used in user_enrolments->status) */
define('ENROL_USER_SUSPENDED', 1);

/** Enrol info is cached for this number of seconds in require_login() */
define('ENROL_REQUIRE_LOGIN_CACHE_PERIOD', 1800);

/** When user disappears from external source, the enrolment is completely removed */
define('ENROL_EXT_REMOVED_UNENROL', 0);

/** When user disappears from external source, the enrolment is kept as is - one way sync */
define('ENROL_EXT_REMOVED_KEEP', 1);

/** enrol plugin feature describing requested restore type */
define('ENROL_RESTORE_TYPE', 'enrolrestore');
/** User custom backup/restore class  stored in backup/moodle2/ subdirectory */
define('ENROL_RESTORE_CLASS', 'class');
/** Restore all custom fields from enrol table without any changes and all user_enrolments records */
define('ENROL_RESTORE_EXACT', 'exact');
/** Restore enrol record like ENROL_RESTORE_EXACT, but no user enrolments */
define('ENROL_RESTORE_NOUSERS', 'nousers');

/**
 * When user disappears from external source, user enrolment is suspended, roles are kept as is.
 * In some cases user needs a role with some capability to be visible in UI - suc has in gradebook,
 * assignments, etc.
 */
define('ENROL_EXT_REMOVED_SUSPEND', 2);

/**
 * When user disappears from external source, the enrolment is suspended and roles assigned
 * by enrol instance are removed. Please note that user may "disappear" from gradebook and other areas.
 * */
define('ENROL_EXT_REMOVED_SUSPENDNOROLES', 3);

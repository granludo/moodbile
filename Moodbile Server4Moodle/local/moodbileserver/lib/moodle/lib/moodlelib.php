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

// BASE VERSION = 1.1522
/**
 * moodlelib.php - Moodle main library
 *
 * Main library file of miscellaneous general-purpose Moodle functions.
 * Other main libraries:
 *  - weblib.php      - functions that produce web output
 *  - datalib.php     - functions that access the database
 *
 * @package    core
 * @subpackage lib
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * PARAM_ALPHANUMEXT - expected numbers, letters only and _-.
 */
define('PARAM_ALPHANUMEXT', 'alphanumext');



/// Web Services ///

/**
 * VALUE_REQUIRED - if the parameter is not supplied, there is an error
 */
define('VALUE_REQUIRED', 1);

/**
 * VALUE_OPTIONAL - if the parameter is not supplied, then the param has no value
 */
define('VALUE_OPTIONAL', 2);

/**
 * VALUE_DEFAULT - if the parameter is not supplied, then the default value is used
 */
define('VALUE_DEFAULT', 0);

/**
 * NULL_NOT_ALLOWED - the parameter can not be set to null in the database
 */
define('NULL_NOT_ALLOWED', false);

/**
 * NULL_ALLOWED - the parameter can be set to null in the database
 */
define('NULL_ALLOWED', true);

/// Feature constants ///
// Used for plugin_supports() to report features that are, or are not, supported by a module.

/** True if module can provide a grade */
define('FEATURE_GRADE_HAS_GRADE', 'grade_has_grade');
/** True if module supports outcomes */
define('FEATURE_GRADE_OUTCOMES', 'outcomes');

/** True if module has code to track whether somebody viewed it */
define('FEATURE_COMPLETION_TRACKS_VIEWS', 'completion_tracks_views');
/** True if module has custom completion rules */
define('FEATURE_COMPLETION_HAS_RULES', 'completion_has_rules');

/** True if module has no 'view' page (like label) */
define('FEATURE_NO_VIEW_LINK', 'viewlink');
/** True if module supports outcomes */
define('FEATURE_IDNUMBER', 'idnumber');
/** True if module supports groups */
define('FEATURE_GROUPS', 'groups');
/** True if module supports groupings */
define('FEATURE_GROUPINGS', 'groupings');
/** True if module supports groupmembersonly */
define('FEATURE_GROUPMEMBERSONLY', 'groupmembersonly');

/** Type of module */
define('FEATURE_MOD_ARCHETYPE', 'mod_archetype');
/** True if module supports intro editor */
define('FEATURE_MOD_INTRO', 'mod_intro');
/** True if module has default completion */
define('FEATURE_MODEDIT_DEFAULT_COMPLETION', 'modedit_default_completion');

define('FEATURE_COMMENT', 'comment');

define('FEATURE_RATE', 'rate');
/** True if module supports backup/restore of moodle2 format */
define('FEATURE_BACKUP_MOODLE2', 'backup_moodle2');

/** Unspecified module archetype */
define('MOD_ARCHETYPE_OTHER', 0);
/** Resource-like type module */
define('MOD_ARCHETYPE_RESOURCE', 1);
/** Assignment module archetype */
define('MOD_ARCHETYPE_ASSIGNMENT', 2);

/**
 * Security token used for allowing access
 * from external application such as web services.
 * Scripts do not use any session, performance is relatively
 * low because we need to load access info in each request.
 * Scripts are executed in parallel.
 */
define('EXTERNAL_TOKEN_PERMANENT', 0);

/**
 * Security token used for allowing access
 * of embedded applications, the code is executed in the
 * active user session. Token is invalidated after user logs out.
 * Scripts are executed serially - normal session locking is used.
 */
define('EXTERNAL_TOKEN_EMBEDDED', 1);

/**
 * The home page should be the site home
 */
define('HOMEPAGE_SITE', 0);
/**
 * The home page should be the users my page
 */
define('HOMEPAGE_MY', 1);
/**
 * The home page can be chosen by the user
 */
define('HOMEPAGE_USER', 2);

/**
 * Hub directory url (should be moodle.org)
 */
define('HUB_HUBDIRECTORYURL', "http://hubdirectory.moodle.org");


/**
 * Moodle.org url (should be moodle.org)
 */
define('HUB_MOODLEORGHUBURL', "http://hub.moodle.org");

/**
 * Strict validation of parameter values, the values are only converted
 * to requested PHP type. Internally it is using clean_param, the values
 * before and after cleaning must be equal - otherwise
 * an invalid_parameter_exception is thrown.
 * Objects and classes are not accepted.
 *
 * @param mixed $param
 * @param int $type PARAM_ constant
 * @param bool $allownull are nulls valid value?
 * @param string $debuginfo optional debug information
 * @return mixed the $param value converted to PHP type or invalid_parameter_exception
 */
function validate_param($param, $type, $allownull=NULL_NOT_ALLOWED, $debuginfo='') {
    if (is_null($param)) {
        if ($allownull == NULL_ALLOWED) {
            return null;
        } else {
            throw new invalid_parameter_exception($debuginfo);
        }
    }
    if (is_array($param) or is_object($param)) {
        throw new invalid_parameter_exception($debuginfo);
    }

    $cleaned = clean_param($param, $type);
    if ((string)$param !== (string)$cleaned) {
        // conversion to string is usually lossless
        throw new invalid_parameter_exception($debuginfo);
    }

    return $cleaned;
}



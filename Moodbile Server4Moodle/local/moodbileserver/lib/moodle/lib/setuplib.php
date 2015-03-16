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

// BASE VERSION = 1.37
/**
 * These functions are required very early in the Moodle
 * setup process, before any of the main libraries are
 * loaded.
 *
 * @package    core
 * @subpackage lib
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Base Moodle Exception class
 *
 * Although this class is defined here, you cannot throw a moodle_exception until
 * after moodlelib.php has been included (which will happen very soon).
 *
 * @package    core
 * @subpackage lib
 * @copyright  2008 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moodle_exception extends Exception {
    public $errorcode;
    public $module;
    public $a;
    public $link;
    public $debuginfo;

    /**
     * Constructor
     * @param string $errorcode The name of the string from error.php to print
     * @param string $module name of module
     * @param string $link The url where the user will be prompted to continue. If no url is provided the user will be directed to the site index page.
     * @param object $a Extra words and phrases that might be required in the error string
     * @param string $debuginfo optional debugging information
     */
    function __construct($errorcode, $module='', $link='', $a=NULL, $debuginfo=null) {
        if (empty($module) || $module == 'moodle' || $module == 'core') {
            $module = 'error';
        }

        $this->errorcode = $errorcode;
        $this->module    = $module;
        $this->link      = $link;
        $this->a         = $a;
        $this->debuginfo = $debuginfo;

        $message = get_string($errorcode, $module, $a);
        if (substr($message, 0, 1) == '[') {
            $message = $module . '/' . $errorcode;
        }

        parent::__construct($message, 0);
    }
}

/**
 * Course/activity access exception.
 *
 * This exception is thrown from require_login()
 *
 * @package    core
 * @subpackage lib
 * @copyright  2010 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class require_login_exception extends moodle_exception {
    function __construct($debuginfo) {
        parent::__construct('requireloginerror', 'error', '', NULL, $debuginfo);
    }
}

/**
 * Web service parameter exception class
 *
 * This exception must be thrown to the web service client when a web service parameter is invalid
 * The error string is gotten from webservice.php
 */
class webservice_parameter_exception extends moodle_exception {
    /**
     * Constructor
     * @param string $errorcode The name of the string from webservice.php to print
     * @param string $a The name of the parameter
     */
    function __construct($errorcode=null, $a = '') {
        parent::__construct($errorcode, 'webservice', '', $a, null);
    }
}

/**
 * Exceptions indicating user does not have permissions to do something
 * and the execution can not continue.
 *
 * @package    core
 * @subpackage lib
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class required_capability_exception extends moodle_exception {
    function __construct($context, $capability, $errormessage, $stringfile) {
        $capabilityname = get_capability_string($capability);
        if ($context->contextlevel == CONTEXT_MODULE and preg_match('/:view$/', $capability)) {
            // we can not go to mod/xx/view.php because we most probably do not have cap to view it, let's go to course instead
            $paranetcontext = get_context_instance_by_id(get_parent_contextid($context));
            $link = get_context_url($paranetcontext);
        } else {
            $link = get_context_url($context);
        }
        parent::__construct($errormessage, $stringfile, $link, $capabilityname);
    }
}

/**
 * Exception indicating programming error, must be fixed by a programer. For example
 * a core API might throw this type of exception if a plugin calls it incorrectly.
 *
 * @package    core
 * @subpackage lib
 * @copyright  2008 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coding_exception extends moodle_exception {
    /**
     * Constructor
     * @param string $hint short description of problem
     * @param string $debuginfo detailed information how to fix problem
     */
    function __construct($hint, $debuginfo=null) {
        parent::__construct('codingerror', 'debug', '', $hint, $debuginfo);
    }
}

/**
 * Exception indicating malformed parameter problem.
 * This exception is not supposed to be thrown when processing
 * user submitted data in forms. It is more suitable
 * for WS and other low level stuff.
 *
 * @package    core
 * @subpackage lib
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class invalid_parameter_exception extends moodle_exception {
    /**
     * Constructor
     * @param string $debuginfo some detailed information
     */
    function __construct($debuginfo=null) {
        parent::__construct('invalidparameter', 'debug', '', null, $debuginfo);
    }
}

/**
 * Exception indicating malformed response problem.
 * This exception is not supposed to be thrown when processing
 * user submitted data in forms. It is more suitable
 * for WS and other low level stuff.
 */
class invalid_response_exception extends moodle_exception {
    /**
     * Constructor
     * @param string $debuginfo some detailed information
     */
    function __construct($debuginfo=null) {
        parent::__construct('invalidresponse', 'debug', '', null, $debuginfo);
    }
}

/**
 * An exception that indicates something really weird happened. For example,
 * if you do switch ($context->contextlevel), and have one case for each
 * CONTEXT_... constant. You might throw an invalid_state_exception in the
 * default case, to just in case something really weird is going on, and
 * $context->contextlevel is invalid - rather than ignoring this possibility.
 *
 * @package    core
 * @subpackage lib
 * @copyright  2009 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class invalid_state_exception extends moodle_exception {
    /**
     * Constructor
     * @param string $hint short description of problem
     * @param string $debuginfo optional more detailed information
     */
    function __construct($hint, $debuginfo=null) {
        parent::__construct('invalidstatedetected', 'debug', '', $hint, $debuginfo);
    }
}

/**
 * An exception that indicates incorrect permissions in $CFG->dataroot
 *
 * @package    core
 * @subpackage lib
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class invalid_dataroot_permissions extends moodle_exception {
    /**
     * Constructor
     * @param string $debuginfo optional more detailed information
     */
    function __construct($debuginfo = NULL) {
        parent::__construct('invaliddatarootpermissions', 'error', '', NULL, $debuginfo);
    }
}

/**
 * An exception that indicates that file can not be served
 *
 * @package    core
 * @subpackage lib
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class file_serving_exception extends moodle_exception {
    /**
     * Constructor
     * @param string $debuginfo optional more detailed information
     */
    function __construct($debuginfo = NULL) {
        parent::__construct('cannotservefile', 'error', '', NULL, $debuginfo);
    }
}

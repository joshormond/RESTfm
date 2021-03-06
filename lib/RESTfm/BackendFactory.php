<?php
/**
 * RESTfm - FileMaker RESTful Web Service
 *
 * @copyright
 *  Copyright (c) 2011-2017 Goya Pty Ltd.
 *
 * @license
 *  Licensed under The MIT License. For full copyright and license information,
 *  please see the LICENSE file distributed with this package.
 *  Redistributions of files must retain the above copyright notice.
 *
 * @link
 *  http://restfm.com
 *
 * @author
 *  Gavin Stewart
 */

namespace RESTfm;

/**
 * (Database) Backend Factory - instantiates appropriate database backend.
 */
class BackendFactory {

    /*
     * Possible backend types.
     */
    const   BACKEND_FILEMAKER = "FileMaker",
            BACKEND_PDO = "Pdo";

    /**
     * Instantiate and return the appropriate backend object.
     *
     * @param Request $request
     *  Originating request containing credentials for backend authentication.
     *
     * @param string $database
     *  Database name.
     *
     * @throws ResponseException
     *  When no appropriate backend found.
     *
     * @return BackendAbstract
     */
    public static function make (Request $request, $database = NULL) {
        // FileMaker is the default, but $database may map to a PDO backend.
        $type = self::BACKEND_FILEMAKER;
        if ($database !== NULL && Config::checkVar('databasePDOMap', $database)) {
            $type = self::BACKEND_PDO;
        }

        $backendClassName = 'RESTfm\\Backend' . $type . '\\' . 'Backend';

        $restfmCredentials = $request->getCredentials();

        if ($type === self::BACKEND_PDO) {
            $backendObject = new $backendClassName(
                            Config::getVar('databasePDOMap', $database),
                            $restfmCredentials->getUsername(),
                            $restfmCredentials->getPassword()
                        );
        } else {    # Default to FileMaker
            $backendObject = new $backendClassName(
                            Config::getVar('database', 'hostspec'),
                            $restfmCredentials->getUsername(),
                            $restfmCredentials->getPassword()
                        );
        }

        return $backendObject;
    }

};

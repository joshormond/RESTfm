<?php
/**
 * RESTfm - FileMaker RESTful Web Service
 *
 * @copyright
 *  Copyright (c) 2011-2016 Goya Pty Ltd.
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

/**
 * RESTfmMessageInterface
 *
 * This message interface provides access to the request/response data sent
 * between formats (web input/output) and backends (database input/output).
 *
 * In general:
 *   Request: import format -> RESTfmMessage -> backend
 *   Response: backend -> RESTfmMessage -> export format
 *
 * In practice, responses are created from raised exceptions as well.
 *
 * Not every request will create a RESTfmMessage as some requests contain
 * no actual data.
 *
 * Every response will contain data and so will create a RESTfmMessage.
 */
interface RESTfmMessageInterface {

    // --- Access methods for managing data in rows. --- //

    /**
     * Set an 'info' key/value pair.
     *
     * @param string $key
     * @param string $val
     */
    public function setInfo ($key, $val);

    /**
     * @param string $key
     * @return string $val
     */
    public function getInfo ($key);

    /**
     * @return array [ <key> => <val>, ... ]
     */
    public function getInfos ();

    /**
     * Set a 'metaField' fieldName/row pair.
     *
     * @param string $fieldName
     * @param RESTfmMessageRow $metaField
     */
    public function setMetaField ($fieldName, RESTfmMessageRow $metaField);

    /**
     * @param string $fieldName
     *
     * @return RESTfmMessageRow
     */
    public function getMetaField ($fieldName);

    /**
     * @return integer
     */
    public function getMetaFieldCount ();

    /**
     * @return array [ <fieldName> => <RESTfmMessageRow>, ...]
     */
    public function getMetaFields ();

    /**
     * Add a 'multistatus' object (row).
     *
     * @param RESTfmMessageMultistatusInterface $multistatus
     */
    public function addMultistatus (RESTfmMessageMultistatusInterface $multistatus);

    /**
     * @return array [ <RESTfmMessageMultistatusInterface>, ... ]
     */
    public function getMultistatus ();

    /**
     * Set a 'nav' name/href pair.
     *
     * @param string name
     * @param string href
     */
    public function setNav ($name, $href);

    /**
     * @param string name
     *
     * @return string href
     */
    public function getNav ($name);

    /**
     * @return array [ <name> => <href>, ... ]
     */
    public function getNavs ();

    /**
     * Add a 'data+meta' record object (row plus meta data).
     *
     * @param RESTfmMessageRecordInterface $record
     */
    public function addRecord (RESTfmMessageRecordInterface $record);

    /**
     * @return integer
     */
    public function getRecordCount ();

    /**
     * @return array [ <RESTfmMessageRecordInterface>, ... ]
     */
    public function getRecords ();

    /**
     * Return a single record identified by $recordId
     *
     * @param string $recordId
     *
     * @return RESTfmMessageRecordInterface or NULL if $recordId does not exist.
     */
    public function getRecordByRecordId ($recordId);


    // --- Access methods for managing data in sections. --- //

    /**
     * @return array of strings of available section names.
     *      Section names are: meta, data, info, metaField, multistatus, nav
     */
    public function getSectionNames ();

    /**
     * @param string $sectionName
     *
     * @return RESTfmMessageSectionInterface
     */
    public function getSection ($sectionName);

    /**
     * @param string $sectionName section name.
     * @param array of section data.
     *  With section data in the form of:
     *    1 dimensional:
     *    array('key' => 'val', ...)
     *   OR
     *    2 dimensional:
     *    array(
     *      array('key' => 'val', ...),
     *      ...
     *    ))
     */
    public function setSection ($sectionName, $sectionData);

    /**
     * Export all sections as a single associative array.
     *
     * @return array of all sections and data.
     *  With section(s) in the mixed form(s) of:
     *    1 dimensional:
     *    array('sectionNameX' => array('key' => 'val', ...))
     *    2 dimensional:
     *    array('sectionNameY' => array(
     *                              array('key' => 'val', ...),
     *                              ...
     *                           ))
     */
    public function exportArray ();

    /**
     * Import sections and associated data from the provided array.
     *
     * @param associative array $array of section(s) and data.
     *  With section(s) in the mixed form(s) of:
     *    1 dimensional:
     *    array('sectionNameX' => array('key' => 'val', ...))
     *    2 dimensional:
     *    array('sectionNameY' => array(
     *                              array('key' => 'val', ...),
     *                              ...
     *                           ))
     */
    public function importArray ($array);

    /**
     * Make a human readable string of all sections and data.
     *
     * @return string
     */
    public function __toString ();
};

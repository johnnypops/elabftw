<?php
/********************************************************************************
*                                                                               *
*   Copyright 2012 Nicolas CARPi (nicolas.carpi@gmail.com)                      *
*   http://www.elabftw.net/                                                     *
*                                                                               *
********************************************************************************/

/********************************************************************************
*  This file is part of eLabFTW.                                                *
*                                                                               *
*    eLabFTW is free software: you can redistribute it and/or modify            *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabFTW is distributed in the hope that it will be useful,                 *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabFTW.  If not, see <http://www.gnu.org/licenses/>.   *
*                                                                               *
********************************************************************************/
namespace Elabftw\Elabftw;

use Exception;

class Update
{
    public $version;
    const URL = 'https://get.elabftw.net/updates.ini';
    // ///////////////////////////////
    // UPDATE THIS AFTER RELEASING
    const INSTALLED_VERSION = '1.1.4';
    // ///////////////////////////////

    /*
     * Constructor will get what is the latest version available from URL
     *
     */
    public function __construct()
    {
        $this->getUpdatesIni();
        if (!$this->validateVersion()) {
            throw new Exception('Error getting latest version information from server!');
        }
    }

    /*
     * Return the latest version of elabftw
     * Will fetch updates.ini file from elabftw.net
     *
     * @return string|bool|null latest version or false if error
     */
    private function getUpdatesIni()
    {
        $ini = curlDownload(self::URL);
        // convert ini into array. The `true` is for process_sections: to get multidimensionnal array.
        $versions = parse_ini_string($ini, true);
        // get the latest version (first item in array, an array itself with url and checksum)
        $this->version = array_keys($versions)[0];
    }

    /*
     * Check if the version string actually looks like a version
     *
     * @return bool true if version match
     */
    private function validateVersion()
    {
        return preg_match('/[0-99]+\.[0-99]+\.[0-99]+.*/', $this->version);
    }

    /*
     * Return true if there is a new version out there
     *
     * @return bool
     */
    public function availableUpdate()
    {
        return self::INSTALLED_VERSION != $this->version;
    }

    /*
     * Return the latest version string
     *
     * @return string 1.1.4
     */
    public function getLatestVersion()
    {
        return $this->version;
    }

    /*
     * Return the latest version of elabftw using GitHub API
     * This function is unused but let's keep it.
     *
     * @return string|bool latest version or false if error
    private function getLatestVersionFromGitHub()
    {
        $url = 'https://api.github.com/repos/elabftw/elabftw/releases/latest';
        $res = curlDownload($url);
        $latest_arr = json_decode($res, true);
        return $latest_arr['tag_name'];
    }
     */
}
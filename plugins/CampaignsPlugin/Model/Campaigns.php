<?php
/**
 * CampaignsPlugin for phplist.
 *
 * This file is a part of CampaignsPlugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2014-2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 *
 * @link      http://forums.phplist.com/
 */

/**
 * This class holds the properties entered in the form.
 */
class CampaignsPlugin_Model_Campaigns extends CommonPlugin_Model
{
    /*
     *    Private variables
     */
    private $dao;
    private $owner;

    /*
     *    Inherited protected variables
     */
    protected $properties = array(
        'campaignID' => null,
        'type' => 'sent',
        'redirect' => null,
    );
    protected $persist = array(
        'type' => '',
    );
    /*
     *    Public variables
     */

    /*
     *    Private methods
     */
    private function typeToStatus()
    {
        switch ($this->type) {
        case 'active':
            return array('inprocess', 'submitted', 'suspended');
            break;
        case 'sent':
            return array('sent');
            break;
        default:
            return array($this->type);
        }
    }

    /*
     *    Public methods
     */
    public function __construct($db)
    {
        $this->dao = new CampaignsPlugin_DAO_Campaign($db);
        parent::__construct('CampaignsPlugin');
        $access = accessLevel('messages');
        $this->owner = ($access == 'owner') ? $_SESSION['logindetails']['id'] : null;
    }

    public function campaigns($start, $limit)
    {
        return $this->dao->campaigns($this->owner, $this->typeToStatus(), $start, $limit);
    }

    public function totalCampaigns()
    {
        return $this->dao->totalCampaigns($this->owner, $this->typeToStatus());
    }
}

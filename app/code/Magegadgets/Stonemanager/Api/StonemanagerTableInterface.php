<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Api;

/**
 * Interface CartInterface
 * @package Magegadgets\Stonemanager\Api\Data
 * @api
 */
interface StonemanagerTableInterface
{
    /**
     * Get cash on delivery fee
     *
     * @param double $amount
     * @param string $country
     * @return double
     */
    public function getFee($amount, $country);

    /**
     * Get table as array
     *
     * @return array
     */
    public function getTableAsArray();

    /**
     * Get table as CSV
     *
     * @return string
     */
    public function getTableAsCsv();

    /**
     * Save from file
     *
     * @param string $fileName
     * @return int
     */
    public function saveFromFile($fileName);

    /**
     * Get number of rows
     * @return int
     */
    public function getRowsCount();
}

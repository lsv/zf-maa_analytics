<?php
/**
 * Analytics for Zend Framework
 * 
 * @category Zend_Framework
 * @package  Maa_Analytics
 * @author   Martin Aarhof <martin.aarhof@gmail.com>
 * @license  http://creativecommons.org/licenses/by/3.0/ Creative Commons Attribution 3.0 Unported License.
 * @version  Version 0.1a
 */

/**
 * Maa_Analytics_Abstract
 *
 * @category Zend_Framework
 * @package  Maa_Analytics
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @license  http://creativecommons.org/licenses/by/3.0/ Creative Commons Attribution 3.0 Unported License.
 */
abstract class Maa_Analytics_Ecommerce_Abstract
{
    
    /**
     * Total amount of order
     * @var float
     */
    protected $totalamount = 0;
    
    /**
     * Calculated amount of order (items price * qty + tax + shipping)
     * @var float
     */
    protected $calcsum = 0;

    /**
     * Add info data to our array
     * @param int $id
     * @param string $data
     * @return Maa_Analytics_Ecommerce_Abstract 
     */
    protected function add($id, $data)
    {
        $this->_data['info'][$id] = $data;
        return $this;
    }
    
    /**
     * Get data from the data array
     * @param int $key
     * @return string 
     */
    protected function getData($key)
    {
        return $this->_data['info'][$key];
    }
    
    /**
     * Setting our calculated sum
     * @param float $amount
     * @param int $qty
     * @return Maa_Analytics_Ecommerce_Abstract 
     */
    protected function setSum($amount, $qty = 1)
    {
        $this->calcsum += ((float)$amount * (int)$qty);
        return $this;
    }
    
    /**
     * Function to check if the calculated sum = total amount
     * @return bool
     * @throws Exception
     */
    public function checkSum()
    {
        if ($this->calcsum == $this->totalamount)
            return true;
        
        user_error('The calculated sum is not as totalsum - missing "' . ($this->totalamount-$this->calcsum) . '"', E_USER_NOTICE);
        return false;
        throw new Exception('The calculated sum is not as totalsum - missing "' . ($this->totalamount-$this->calcsum) . '"');
            
    }
    
    /**
     * Build our info
     * @return string
     */
    public function __toString()
    {
        ksort($this->_data['info']);
        return "'" . implode("', '", array_map(function($a) { return (string)$a; }, $this->_data['info'])) . "'";
    }
    
    /**
     * Get the items to our order
     * @return array
     */
    public function getItems()
    {
        $out = array();
        foreach($this->_data['items'] AS $item) {
            $out[] = $item;
        }
        return $out;
    }
    
}
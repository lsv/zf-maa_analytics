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
 * Maa_Analytics_Item to create our items for our Maa_Analytics_Ecommerce
 *
 * @category Zend_Framework
 * @package  Maa_Analytics
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @license  http://creativecommons.org/licenses/by/3.0/ Creative Commons Attribution 3.0 Unported License.
 */
class Maa_Analytics_Ecommerce_Item
    extends Maa_Analytics_Ecommerce_Abstract
{
    
    /**
     * Our data - looks odd, but the codes should come in right order for correct data to analytics
     * @var array
     */
    protected $_data = array(
        'info' => array(
                    0 => null,
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                )
    );
    
    /**
     * Build our item
     * @param float $unitprice
     * @param int $qty
     * @param array $options - array('Sku' => '2', 'Productname' => 'Sweater', 'Category' => 'Yellow') for "fast creating items"
     * @throws Exception
     */
    public function __construct($unitprice, $qty, array $options = null)
    {
        $this->add(4, $unitprice)->add(5, $qty);
        if (is_array($options)) {
            foreach($options AS $key => $value) {
                $key = 'set' . ucfirst($key);
                if (method_exists($this, $key)) {
                    $this->{$key}($value);
                } else {
                    throw new Exception('method "' . $key . '" does not exists');
                }
            }
        }
    }

    /**
     * Set SKU / code
     * @param string $sku
     * @return Maa_Analytics_Ecommerce_Item 
     */
    public function setSku($sku)
    {
        return $this->add(1, $sku);
    }
    
    /**
     * Set product name
     * @param string $name
     * @return Maa_Analytics_Ecommerce_Item
     */
    public function setProductname($name)
    {
        return $this->add(2, $name);
    }
    
    /**
     * Set category or variation
     * @param string $category
     * @return Maa_Analytics_Ecommerce_Item
     */
    public function setCategory($category)
    {
        return $this->add(3, $category);
    }
    
}
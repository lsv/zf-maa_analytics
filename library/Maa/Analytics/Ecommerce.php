<?php

class Maa_Analytics_Ecommerce
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
                    6 => null,
                    7 => null,
                ),
        'items' => array()
    );

    /**
     * Start our order info
     * @param string $orderId
     * @param float $totalamount
     * @return Maa_Analytics_Ecommerce 
     */
    public function __construct($orderId, $totalamount)
    {
        $this->totalamount = (float)$totalamount;
        return $this->add(0, $orderId)
            ->add(2, $totalamount);
    }
    
    /**
     * Set affiliation or store name
     * @param string $store
     * @return Maa_Analytics_Ecommerce
     */
    public function setStorename($store)
    {
        return $this->add(1, $store);
    }
    
    /**
     * Set tax
     * @param float $amount
     * @return Maa_Analytics_Ecommerce
     */
    public function setTax($amount)
    {
        $this->setSum($amount);
        return $this->add(3, $amount);
    }
    
    /**
     * Set shipping
     * @param float $amount
     * @return Maa_Analytics_Ecommerce
     */
    public function setShipping($amount)
    {
        $this->setSum($amount);
        return $this->add(4, $amount);
    }
    
    /**
     * Set city
     * @param string $city
     * @return Maa_Analytics_Ecommerce
     */
    public function setCity($city)
    {
        return $this->add(5, $city);
    }
    
    /**
     * Set state or province
     * @param string $state
     * @return Maa_Analytics_Ecommerce
     */
    public function setState($state)
    {
        return $this->add(6, $state);
    }
    
    /**
     * Set country
     * @param string $country
     * @return Maa_Analytics_Ecommerce
     */
    public function setCountry($country)
    {
        return $this->add(7, $country);
    }
    
    /**
     * Add item to our order
     * @param Maa_Analytics_Ecommerce_Item $item
     * @return Maa_Analytics_Ecommerce 
     */
    public function addItem(Maa_Analytics_Ecommerce_Item $item)
    {
        $this->_data['items'][] = $item->add(0, $this->getData(0));
        $this->setSum($item->getData(4), $item->getData(5));
        return $this;
    }
    
    /**
     * Add items to our order
     * @param Maa_Analytics_Ecommerce_Item $item, @param Maa_Analytics_Ecommerce_Item $item, ...
     * @return Maa_Analytics_Ecommerce 
     */
    public function addItems()
    {
        if (func_num_args() > 0) {
            foreach(func_get_args() AS $arg) {
                if ($arg instanceof Maa_Analytics_Ecommerce_Item) {
                    $this->addItem($arg);
                } else {
                    throw new Exception('"' . $arg . '" is not a instance of Maa_Analytics_Ecommerce_Item');
                }
            }
        }
        return $this;
    }
    
}
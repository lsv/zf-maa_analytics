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
 * Maa_Analytics is a class to create analytics javascript code
 * 
 * An example of the config and how to load it
 * <code>
 * $c = new Maa_Analytics;
 * // Now our application.ini is loaded
 * $c->loadConfig(
 *   new Zend_Config_Ini($this->baseUrl() . '../application/configs/test.ini', 'production')
 * );
 * // Here we load another config file
 * $c->loadConfig(
 *   new Zend_Config_Ini($this->baseUrl() . '../application/configs/test1.ini', 'production')
 *   new Zend_Config_Ini($this->baseUrl() . '../application/configs/test2.ini', 'production')
 * );
 * // And here we load 2 more
 * </code>
 * 
 * And to get the javascript code just simple print it
 * <code>
 * echo $c;
 * </code>
 * 
 * @see _examples/application.ini.sample for all configuration to the application.ini file
 *
 * @category Zend_Framework
 * @package  Maa_Analytics
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @license  http://creativecommons.org/licenses/by/3.0/ Creative Commons Attribution 3.0 Unported License.
 */
class Maa_Analytics
{
    
    /**
     * Placeholder for our configs
     * @var Zend_Config
     */
    protected $config = array();
    
    /**
     * Will be used at another time - for multiple accounts
     * @var array
     */
    protected $accountCode = array();
    
    /**
     * Our google code push - needs to be here, 
     * the different code should be in right order
     * @var array 
     */
    protected $pushArray = array(
        // Account
        '_setAccount' => null,
        // Browser
        '_setClientInfo' => null,
        '_setAllowHash' => null,
        '_setDetectFlash' => null,
        '_setDetectTitle' => null,
        // Campaign
        '_setAllowAnchor' => null,
        '_setCampContentKey' => null,
        '_setCampMediumKey' => null,
        '_setCampNameKey' => null,
        '_setCampNOKey' => null,
        '_setCampSourceKey' => null,
        '_setCampTermKey' => null,
        '_setCampaignTrack' => null,
        '_setCampaignCookieTimeout' => null,
        '_setReferrerOverride' => null,
        // Cross Domain
        '_setDomainName' => null,
        '_setAllowLinker' => null,
        // Session Timeout 
        // http://code.google.com/intl/da/apis/analytics/docs/tracking/asyncMigrationExamples.html#SessionTimeout
        // Sources 
        // http://code.google.com/intl/da/apis/analytics/docs/tracking/asyncMigrationExamples.html#SearchEngines
        // Tracking Limited to a Sub-Directory
        // http://code.google.com/intl/da/apis/analytics/docs/tracking/asyncMigrationExamples.html#SetCookiePath
        // Using a Local Server
        // http://code.google.com/intl/da/apis/analytics/docs/tracking/asyncMigrationExamples.html#UsingALocalServer
        // Tracker
        '_trackPageview' => '',
        // Economic
        '_addTrans' => null,
        '_addItem' => null,
        '_trackTrans' => null
        
    );

    /**
     * Our constructor
     * Will maybe be removed by time, and replaced by a getInstance()
     */
    public function __construct() {
        
    }
    
    /**
     * Building and outputting our Analytics code,
     * and loading all methods starting with _set
     * @return string
     */
    public function __toString()
    {
        $methods = get_class_methods($this);
        foreach($methods AS $method) {
            if (substr($method, 0, 4) == '_set') {
                $this->{$method}();
            }
        }
        
        $out = array();
        foreach($this->pushArray AS $key => $value) {
            if ($value === null) continue;
            
            switch ($value) {
                case is_array($value):
                    foreach($value AS $v) {
                        $out[] = "_gaq.push(['" . $key . "', '" . $v . "']);";
                    }
                    break;
                case '':
                    $out[] = "_gaq.push(['" . $key . "']);";
                    break;
                default:
                    $out[] = "_gaq.push(['" . $key . "', '" . $value . "']);";
                    break;
            }
            
        }
        
        $out = "var _gaq = _gaq || [];\n" . implode("\n", $out) . "\n";
        $out .= "\n(function() {\n\t";
        $out .= "var ga = document.createElement('script');";
        $out .= "ga.type = 'text/javascript'; ga.async = true;\n\t";
        $out .= "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n\t";
        $out .= "var s = document.getElementsByTagName('script')[0];";
        $out .= "s.parentNode.insertBefore(ga, s);\n";
        $out .= "})();";
        return $out;
        
    }
    
    /**
     * Method to add a order to the push
     * @param Maa_Analytics_Ecommerce $order
     * @return Maa_Analytics
     */
    public function addOrder(Maa_Analytics_Ecommerce $order)
    {
        $c = $this->getConfig();
        if (isset($c->checkTransSum) && $c->checkTransSum) {
            if (! $order->checkSum()) {
                return false;
            }
        }
        
        $this->add('addTrans', $order->__toString());
        foreach($order->getItems() AS $item) {
            $this->add('addItem', $item->__toString(), true);
        }
        
        $this->add('trackTrans', '');
        return $this;
        
    }
    
    /**
     * Autoloading from __toString() function
     * @return Maa_Analytics
     */
    private function _setCampaignTrack()
    {
        $options = array(
            'setCampaignTrack' => 'false',
        );
        
        return $this->addOptions($options);
        
    }
    
    /**
     * Autoloading from __toString() function
     * @return Maa_Analytics
     */
    private function _setCrossDomain()
    {
        $options = array(
            'setDomainName' => null,
            'setAllowLinker' => null
        );
        return $this->addOptions($options);
    }

    /**
     * Autoloading from __toString() function
     * @return Maa_Analytics
     */
    private function _setCampaign()
    {
        $options = array(
            'setAllowAnchor' => null,
            'setCampContentKey' => null,
            'setCampMediumKey' => null,
            'setCampNameKey' => null,
            'setCampNOKey' => null,
            'setCampSourceKey' => null,
            'setCampTermKey' => null,
            'setCampaignTrack' => null,
            'setCampaignCookieTimeout' => null,
            'setReferrerOverride' => null
        );
        
        return $this->addOptions($options);
    }

    /**
     * Autoloading from __toString() function
     * @return Maa_Analytics
     */
    private function _setBrowserDetection()
    {
        $options = array(
            'setClientInfo' => 'false',
            'setAllowHash' => 'false',
            'setDetectFlash' => 'false',
            'setDetectTitle' => 'false'
        );        
        return $this->addOptions($options, 'browserdetection');
    }
    
    /**
     * Autoloading from __toString() function
     * @return Maa_Analytics
     */
    private function _setAccountCode()
    {
        $config = $this->getConfig();
        if (isset($config->code)) {
            $codes = $config->code;
            if (is_string($codes)) $codes = array($codes);
            if ($codes instanceof Zend_Config) {
                $codes = $codes->toArray();
            }
            foreach($codes AS $code) {
                $this->add('setAccount', $code);
            }
        }
        return $this;
    }
    
    /**
     * Here we can load other Zend_Configs
     * Please be aware that the LAST added will overwrite
     * if the config was added in another config
     * @param Zend_Config, Zend_Config, ...
     * @return Maa_Analytics 
     * @throws Exception
     */
    public function loadConfigs()
    {
        if (func_num_args() > 0) {
            foreach(func_get_args() AS $arg) {
                if ($arg instanceof Zend_Config) {
                    $this->loadConfig($arg);
                } else {
                    throw new Exception('"' . $arg . '" is not an instance of Zend_Config');
                }
            }
        }
        return $this;
    }
    
    /**
     * Here we can load one other Zend_Config
     * Please be aware that the LAST added will overwrite
     * if the config was added in another config
     * @param Zend_Config
     * @return Maa_Analytics 
     */
    public function loadConfig(Zend_Config $config)
    {
        $this->config[] = $config;
        return $this;
    }

    /**
     * Our config loader - which merges the config files
     * @return boolean/Zend_Config
     */
    private function getConfig()
    {
        $c = Zend_Registry::get('config');
        foreach($this->config AS $config) {
            $c->merge($config);
        }

        if (isset($c->maa, $c->maa->analytics))
            return $c->maa->analytics;
        return false;
    }

    /**
     *
     * @param string/array $options
     * @param string/false $checkField
     * @return Maa_Analytics 
     */
    private function addOptions($options, $checkField = false)
    {
        if (!is_array($options)) $options = array($options);
        $config = $this->getConfig();
        
        foreach($options AS $option => $value) {
            if ($value === null) $value = $config->{$option};
            if ($checkField === false) $checkField = $config->{$option};
            
            if (isset($checkField) && $checkField && $checkField != '' ) {
                $this->add($option, $value);
            }
        }
        return $this;
    }
    
    /**
     * Push the data to the push data
     * @param string $key
     * @param string $value
     * @param bool $valuesAsArray insert values as array
     * @return Maa_Analytics
     * @throws Exception
     */
    private function add($key, $value, $valuesAsArray = false)
    {
        if (strpos($key, '_') !== 0) $key = '_' . $key;
        if (array_key_exists($key, $this->pushArray)) {
            if ($valuesAsArray) {
                $this->pushArray[$key][] = $value;
            } else {
                $this->pushArray[$key] = $value;
            }
            return $this;
        }
        
        throw new Exception('"' . $key . '" is not added yet');
    }
    
}
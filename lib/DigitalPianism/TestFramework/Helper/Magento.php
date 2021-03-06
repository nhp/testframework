<?php

class DigitalPianism_TestFramework_Helper_Magento
{
    private function patchMagentoAutoloader()
    {
        $mageErrorHandler = set_error_handler(function () {
            return false;
        });
        set_error_handler(function ($errno, $errstr, $errfile) use ($mageErrorHandler) {
            if (substr($errfile, -19) === 'Varien/Autoload.php') {
                return null;
            }
            return is_callable($mageErrorHandler) ?
                call_user_func_array($mageErrorHandler, func_get_args()) :
                false;
        });
    }

    public static function bootstrap()
    {
        require __DIR__ . '/../../../../app/Mage.php';
        self::patchMagentoAutoloader();
        self::init();
    }

    public function reset()
    {
        Mage::reset();
        self::init();
    }
    
    public static function init()
    {
        Mage::app('', 'store', ['config_model'  =>  DigitalPianism_TestFramework_Model_Config::class]);
        Mage::setIsDeveloperMode(true);
        self::patchMagentoAutoloader();
        $_SESSION = [];
    }
}

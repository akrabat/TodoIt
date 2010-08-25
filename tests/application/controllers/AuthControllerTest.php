<?php

// Call IndexControllerTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "AuthControllerTest::main");
}

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @group Controllers
 */
class AuthControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite(get_class($this));
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }
    
    public function setUp()
    {
        //include dirname(__FILE__) . '/../../scripts/loadTestDb.php';

        $application = new Zend_Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/configs/application.ini'
        );
//        $application->bootstrap();
//        $this->_frontController = $application->getBootstrap()->getResource('frontcontroller');

        $this->bootstrap = $application;
        return parent::setUp();
    }

    public function tearDown()
    {
        /* Tear Down Routine */
    }

    public function testHomePageShouldRedirectToAuthPage()
    {
        $this->dispatch('/');
//        LDBG($this->_response->getHeaders());
//        LDBG($this->_response->isRedirect(), '$this->_response->isRedirect()');exit;
        $this->assertRedirectRegex('#auth#');
    }
}


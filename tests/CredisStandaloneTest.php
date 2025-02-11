<?php

require_once dirname(__FILE__).'/CredisTest.php';

class CredisStandaloneTest extends CredisTest
{
    protected $useStandalone = TRUE;

  public function testPersistentConnectionsOnStandAloneTcpConnection()
  {
      $this->credis->close();
      $this->credis = new Credis_Client('tcp://'.$this->redisConfig[0]['host'] . ':' . $this->redisConfig[0]['port'] . '/persistent');
      $this->credis->forceStandalone();
      $this->credis->set('key','value');
      $this->assertEquals('value',$this->credis->get('key'));
  }

    public function testPersistentvsNonPersistent() {$this->assertTrue(true);}

    public function testStandAloneArgumentsExtra()
    {
        $this->assertTrue($this->credis->hMSet('hash', array('field1' => 'value1', 'field2' => 'value2'), 'field3', 'value3'));
        $this->assertEquals(array('field1' => 'value1', 'field2' => 'value2', 'field3' =>'value3'), $this->credis->hMGet('hash', array('field1','field2','field3')));
    }

    public function testStandAloneMultiPipelineThrowsException()
    {
        $this->setExpectedExceptionShim('CredisException','A pipeline is already in use and only one pipeline is supported.');
        $this->credis->pipeline()->pipeline();
    }
}

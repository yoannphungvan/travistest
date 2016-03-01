<?php

class MyApiTester extends ApiTester {
  public function jsonGET($relUrl) {
    $this->haveHttpHeader('Accept', 'application/json');
    $this->sendGET(URL . $relUrl);
    $this->seeResponseIsJson();
  }
};

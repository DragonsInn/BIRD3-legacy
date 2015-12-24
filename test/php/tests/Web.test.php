<?php

describe("Web", function(){
    it("should open the front page.", function(){
        $this->client->request("GET","/");
        $response = $this->client->getResponse();
        expect($response->getStatusCode())->toBeInteger();
    });
    it("should open / with status 200.", function(){
        $this->client->request("GET","/");
        $response = $this->client->getResponse();
        expect($response->getStatusCode())->toEqual(200);        
    });
});

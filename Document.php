<?php

namespace Route69;

/**
 *
 * @author Ryan Naddy <untuned20@gmail.com>
 * @name Document.php
 * @ver
 */
class Document{

    protected
            $body = null,
            $post = null;

    public function __construct(){
        $entityBody = file_get_contents('php://input');
        if($this->isJson($entityBody)){
            $this->body = json_decode($entityBody);
        }else{
            $this->body = $entityBody;
        }
        $this->post = $_POST;
    }

    /**
     * Gets the documents post data
     * @return array
     */
    public function getPost(){
        return $this->post;
    }

    /**
     * Gets the documents body
     * If the body is in a json format, return the decoded json
     * Otherwise return the body as a string
     * @return mixed
     */
    public function getBody(){
        return $this->body;
    }

    /**
     * Tests to see if the body is json
     * @param string $string
     * @return boolean
     */
    protected function isJson($string){
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}

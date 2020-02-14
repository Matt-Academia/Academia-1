<?php

namespace Tuiter\Services;

class UserService {

    private $collection = Array();
    

    public function __construct(array $collection){
        $this->collection = $collection;
    }
    
    public function register(string $userId, string $name, string $password) {
        $changeId=md5($userId);
        $numero=0;
        for($i=0;$i<strlen($changeId);$i++){
            $numero+=ord($changeId[$i]);
        }
        $db = $numero % count($this->collection);
        $user = $this->getUser($userId);
        if($user instanceof \Tuiter\Models\UserNull){
            $usuarios= array();
            $usuarios['userId']= $userId;
            $usuarios['name']= $name;
            $usuarios['password']=$password;
            $this->collection[$db]->insertOne($usuarios);
            
            return true;
        } else {
            return false;
        }
    }
    public function getUser($userId){
        $changeId=md5($userId);
        $numero=0;
        for($i=0;$i<strlen($changeId);$i++){
            $numero+=ord($changeId[$i]);
        }
        $db = $numero % count($this->collection);    
        
        $cursor= $this->collection[$db]->findOne(['userId'=> $userId]);
        if (is_null($cursor)){
            $user = new \Tuiter\Models\UserNull('','','');
            return $user;
        }
        $user = new \Tuiter\Models\User($cursor['userId'],$cursor['name'], $cursor['password']);
        return $user;
    }
}

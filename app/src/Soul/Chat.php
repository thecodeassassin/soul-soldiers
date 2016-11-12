<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 *  @package Kernel
 */

namespace Soul;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Soul\Util;

class Chat implements MessageComponentInterface {
    
    
    protected $cache = null;
    protected $users = [];
    protected $userList = [];
    protected $tokens = [];
    protected $storedMessages = [];
    protected $floodStorage = [];
    protected $banned = [];
    
    public function __construct() 
    {
        date_default_timezone_set('Europe/Amsterdam');
        $this->cache = new \Memcached();
        $this->cache->addServer('localhost',11211);
        $this->connections = new \SplObjectStorage;
    }
    
    public function onOpen(ConnectionInterface $conn) 
    {
        
        // we use tokens to validate user input to prevent message forgery
        $token = uniqid();
        echo "A new user connected, assigned token: $token, {$conn->resourceId}\n";
        $this->tokens[$conn->resourceId] = $token;
        
        $conn->send(json_encode(['userToken' => $this->tokens[$conn->resourceId]]));
        
        $this->connections->attach($conn);
        
        if (count($this->storedMessages) > 0) {
            $conn->send(json_encode(['stored_messages' => $this->getStoredMessages()]));
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {
        // echo sprintf('Connection %d sending message "%s"' . "\n", $from->resourceId, $msg);

        if (Util::isJson($msg)) {
            $msg = json_decode($msg);
            
        
            if (isset($msg->users)) {
                $this->updateUsers();
            }
            elseif (isset($msg->event) && $msg->event == 'ping') {
                echo sprintf("Ping received from %s\n", $msg->author);
                
                  if (array_key_exists($msg->author, $this->banned)) {
                      $banTime = $this->banned[$msg->author];
                            
                      if (time() > $banTime) {
                        // lift the ban
                        unset($this->banned[$msg->author]);
                        
                        $this->sendSystemMessage(
                            'Je kunt weer berichten sturen :)',
                            'alert',
                            null,
                            $from
                        );
                      }
                  }
            }
            elseif (isset($msg->event) && isset($msg->token) && $this->tokens[$from->resourceId] === $msg->token) {
                
                if ($msg->event == 'join') {
                    $this->users[$from->resourceId] = $msg->user;
                    
                    echo sprintf("User %s joined\n", $msg->user->nickname);
                    
                    if (!in_array($msg->user->nickname, $this->userList)) {
                         $this->sendSystemMessage(sprintf('%s zit nu in de chat', $msg->user->nickname), 'notify', $from);   
                    }
                } 
                
                $this->updateUsers();
            } elseif (isset($msg->message) && isset($msg->token) && $this->tokens[$from->resourceId] === $msg->token) {
                
                if (array_key_exists($msg->author, $this->banned)) {
                    
                    // reset the users ban
                    $this->banned[$msg->author] = time() + 20;
                    $this->sendSystemMessage(
                        'We houden niet van spam, wacht nogmaals 20 seconden met het versturen van een bericht.',
                        'warning',
                        null,
                        $from
                    );
                
                }
                
                if ($this->floodDetected($msg->author, $msg->time)) {
                    
                    $this->sendSystemMessage(
                        'Je stuurt teveel berichten, wacht 20 seconden met het sturen van je volgende bericht.',
                        'warning',
                        null,
                        $from
                    );
                } else {
                    if (!array_key_exists($msg->author, $this->banned)) {
                        $messageString = json_encode($msg);
                        $messageHash = sha1($messageString);
                        
                        // strip all tags except links
                        $msg->message = strip_tags($msg->message, 'a');
                        
                        // store the message to load when opening the window 
                        $this->cache->set($messageHash, $msg, 300); // store messages for up to one hour
                        $this->storedMessages[] = $messageHash;
                        
                        // send a regular chat message
                        $this->sendMessageToUsers($msg);
                    }
                }
            }
            
        }
        
    }

    public function onClose(ConnectionInterface $conn) 
    {
        
        echo sprintf("User %s left\n", $this->users[$conn->resourceId]->nickname);
        
        // remove the user 
        unset($this->users[$conn->resourceId]);
        unset($this->token[$conn->resourceId]);
        
        // remove the connection
        $this->connections->detach($conn);
        
        // update everybody's user list
        $this->updateUsers();
        
    }
    
    protected function sendMessageToUsers($msg, $from = null, $to = null)
    {
        
        if ($to !== null) {
             $to->send(json_encode($msg));
        } else {
            foreach ($this->connections as $client) {
                
                if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send(json_encode($msg));
                }
            }
        }
    }
    
    protected function updateUsers()
    {
        $users = [];
        $this->userList = [];
        
        foreach ($this->users as $user) {
            if (!in_array($user->nickname, $this->userList)) {
                $users[] = $user;
            }
            $this->userList[] = $user->nickname;
        }
        
         $this->sendMessageToUsers(['users' => $users], null);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo sprintf("An error occurred: %s", $e->getMessage());
    }    
    
    /**
    * @return array 
    */
    protected function getStoredMessages() 
    {
        $messages = [];
        foreach ($this->storedMessages as $k => $messageHash) {
            if ($message = $this->cache->get($messageHash)) {
                $messages[] = $message;
            } else {
                // remove the stored message if it expired
                unset($this->storedMessages[$k]);
            }
        }
        
        return $messages;
    }
    
    protected function sendSystemMessage($message, $type = 'success', $from = null, $to = null) 
    {
        $this->sendMessageToUsers(['message' => $message, 'author' => 'system', 'type' => $type], $from, $to);
    }
    
    /**
     * 
     * Flood detector method, detects flooding by comparing the last send messages and their volume 
     * 
    */
    protected function floodDetected($author, $time)
    {
        $timeMargin = date('H:i:s', time() - 4); // do not allow more than 3 messages every 5 seconds
        $floodMessages = [];   
        $storedMessages = $this->getStoredMessages();
        
        // create a flood storage entry for the user
        if (!array_key_exists($author, $floodMessages)) {
            $floodMessages[$author] = 0;   
        }
        
        foreach ($storedMessages as $message) {
            if ($message->time >= $timeMargin && $message->author == $author) {
                $floodMessages[$author] += 1;
            }
        }
        
        if ($floodMessages[$author] > 2) {
            echo sprintf('%s was banned', $author);
            $this->banned[$author] = time() + 20;
            return true;
        }
        
        return false;
    }
}
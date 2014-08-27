<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Model\StaticModel\DataList;


use Soul\Model\Event;
use Soul\Model\User;

class EventUsers implements ListInterface
{
    /**
     * @return array
     */
    public function getData()
    {
        $eventUsers = [];
        $event = Event::getCurrent();
        $entries = $event->entries;

        if (count($entries) > 0) {
            foreach ($entries as $entry) {
                $user = $entry->user;
                $userArr = $user->toArray();
                $userArr['Betaald'] = $event->hasPayed($user->userId);
                $userArr['Betaald voor buffet'] = $event->hasPayedForBuffet($user->userId);
                $userArr['isActive'] = (bool)$user->isActive;
                $userArr['Type gebruiker'] = (string)$user->getUserType();
                $userArr['Status'] = (string)$user->getUserState();

                unset($userArr['password']);
                unset($userArr['confirmKey']);

                $eventUsers[] = $userArr;
            }
        }

        return $eventUsers;
    }
}
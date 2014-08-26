<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Model\StaticModel\DataList;


use Soul\Model\Event;
use Soul\Model\User;

class PayedUsers implements ListInterface
{
    /**
     * @return array
     */
    public function getData()
    {
        $payedUsers = [];
        $event = Event::getCurrent();
        $users = $event->getPayedUsers();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $userArr = $user->toArray();
                $userArr['Betaald'] = $event->hasPayed($user->userId);
                $userArr['Betaald voor buffet'] = $event->hasPayedForBuffet($user->userId);
                $userArr['isActive'] = (bool)$user->isActive;
                $userArr['Type gebruiker'] = (string)$user->getUserType();
                $userArr['Status'] = (string)$user->getUserState();

                $payedUsers[] = $userArr;
            }
        }

        return $payedUsers;
    }
}
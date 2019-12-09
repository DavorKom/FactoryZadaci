<?php 

class UserObserver
{
    public function creating(User $user)
    {
        die('creating');
    }

    public function updating(User $user)
    {
        die('updating');
    }

    public function updated(User $user)
    {
        die('updated');
    }
}
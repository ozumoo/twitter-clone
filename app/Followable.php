<?php

namespace App;

trait Followable
{
  public function follow(User $user)
  {
      return $this->follows()->save($user) ;
  }

  public function unfollow(User $user)
  {
      return $this->follows()->detach ($user) ;
  }

  public function toggleFollow(User $user)
  {
    if ($this->following($user)) {
      return $this->unfollow($user);
    }

    return $this->follow($user);
  }

  public function following(User $user)
  {
    // when you call a collection , imagine you have  a 30000 recored , you will call them all and them check ! 
    // so what is better to use the relation itself and extract it 
    return $this->follows()
      ->where('following_user_id' , $user->id)
      ->exists();
  }

  public function follows()
  {
      return $this->belongsToMany(User::class , 'follows' , 'user_id' , 'following_user_id');
  }
}
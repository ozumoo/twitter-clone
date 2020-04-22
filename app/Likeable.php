<?php 

namespace App;
use Illuminate\Database\Eloquent\Builder;

trait Likeable {

	public function scopeWithLikes(Builder $query)
	{
		$query->leftJoinSub(
			'select tweet_id , sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id',
			'likes',
			'likes.tweet_id',
			'tweets.id'
		);
	}
	
	public function isLikedBy(User $user , $liked = true)
	{
		return (bool) $user->likes->where('tweet_id' , $this->id)->where('liked', $liked)->count();
	}

	public function isDisLikedBy(User $user)
	{
		return $this->isLikedBy($user , false);
	}

	public function like($user = null , $liked = true)
	{
		$this->likes()->updateOrCreate([
			'user_id' => $user ? $user->id : auth()->id()
		], [
			'liked' => $liked
		]);
	}

	public function dislike($user = null)
	{
		return $this->like($user,false);
	}

	public function likes()
	{
	    return $this->hasMany(Like::class)->where('liked' ,  true);
	}

	public function dislikes()
	{
	    return $this->hasMany(Like::class)->where('liked' ,  false);
	}
}
<div class="border border-gray-300 rounded-lg">
    @forelse ($tweets as $tweet)
        @include('_tweet')
    @empty
      <div class="p-4">No tweets yet</div>
    @endforelse

    {{ $tweets->links()}}
</div>
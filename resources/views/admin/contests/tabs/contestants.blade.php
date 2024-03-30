<tab-item title="Contestants">
  <ul>
    <li class="p-4">
      @buttonLink(['href' => route('admin.contests.contestants.create', ['contest' => $contest->id])])
        Create a New Contestant
      @endbuttonLink
    </li>
    @forelse ($contest->contestants as $contestant)
      <li class="flex flex-wrap p-4 border-t lg:flex-no-wrap">
        <a href="{{ route('admin.contests.contestants.edit', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
          class="flex flex-wrap flex-grow lg:flex-no-wrap lg:pr-4">
          <img src="{{ $contestant->avatar_url }}"
            class="flex-none object-cover object-center w-64 h-64 mx-auto border rounded-full lg:w-32 lg:h-32 lg:mx-0">
          <div class="self-center flex-grow px-0 mt-4 lg:pl-4 lg:mt-0">
            <div class="font-bold">
              # {{ $contestant->order . ' - ' . $contestant->name }}
            </div>
            <div class="mt-2 italic">
              {{ $contestant->alias }}
            </div>
          </div>
        </a>
        <div class="flex-none w-full mt-4 md:w-auto lg:pl-4 lg:mt-0">
          <form method="post"
            action="{{ route('admin.contests.contestants.destroy', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
            class="inline-block">
            @csrf
            @method('DELETE')
            @button(['type' => 'submit', 'color' => 'danger']) Delete @endbutton
          </form>
        </div>
      </li>
    @empty
      <li class="p-4 border-t">No available Contestant(s).</li>
    @endforelse
  </ul>
</tab-item>

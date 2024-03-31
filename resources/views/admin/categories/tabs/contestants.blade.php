<tab-item title="Contestants">
  <ul>
    @forelse ($category->contestants as $key => $contestant)
      <li class="p-4 {{ $key ? 'border-t' : '' }}">
        <div class="flex">
          <div class="flex-none">
            <img src="{{ $contestant->avatar_url }}"
              class="object-cover object-center w-32 h-32 mx-auto border rounded-full">
          </div>
          <div class="self-center flex-grow px-4">
            <div class="font-bold">
              # {{ $contestant->order . ' - ' . $contestant->name }}
            </div>
            <div class="mt-2 italic">
              {{ $contestant->description }}
            </div>
            <div class="inline-block px-2 py-1 mt-2 font-normal text-blue-100 bg-blue-500 rounded">Added</div>
          </div>
          <div class="flex-shrink whitespace-no-wrap">
            <form method="post"
              action="{{ route('admin.contests.categories.category-contestants.destroy', ['contest' => $contest->id, 'category' => $category->id, 'categoryContestant' => $contestant->id]) }}"
              class="inline-block btn">
              @csrf
              @method('DELETE')
              @button(['type' => 'submit', 'color' => 'danger']) Remove @endbutton
            </form>
          </div>
        </div>
      </li>
    @empty
      <li class="p-4">
        No added contestants.
      </li>
    @endforelse

    @foreach ($removedContestants as $contestant)
      <li class="p-4 border-t">
        <div class="flex">
          <div class="flex-none">
            <img src="{{ $contestant->avatar_url }}"
              class="object-cover object-center w-32 h-32 mx-auto border rounded-full">
          </div>
          <div class="self-center flex-grow px-4">
            <div class="mb-4 font-bold">
              <div>
                # {{ $contestant->number . ' - ' . $contestant->name }}
              </div>
              <div class="mt-2">
                <span class="inline-block px-2 py-1 font-normal text-red-100 bg-red-500 rounded">Removed</span>
              </div>
            </div>
            <div class="italic">
              {{ $contestant->description }}
            </div>
          </div>
          <div class="flex-shrink whitespace-no-wrap">
            <form method="post"
              action="{{ route('admin.contests.categories.category-contestants.store', ['contest' => $contest->id, 'category' => $category->id]) }}"
              class="inline-block btn">
              @csrf
              <input type="hidden"
                name="contestant_id"
                value="{{ $contestant->id }}">
              @button(['type' => 'submit']) Add @endbutton
            </form>
          </div>
        </div>
      </li>
    @endforeach
  </ul>
</tab-item>

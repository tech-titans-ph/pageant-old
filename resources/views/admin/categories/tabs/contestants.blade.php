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
              {{ $contestant->alias }}
            </div>
            <div class="inline-block px-2 py-1 mt-2 font-normal text-blue-100 bg-blue-500 rounded">Added</div>
          </div>
          <div class="flex items-center flex-shrink space-x-2 whitespace-no-wrap">
            <form method="post"
              action="{{ route('admin.contests.contestants.move.up', ['contest' => $contest->id, 'category' => $category->id, 'contestant' => $contestant->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Up @endbutton
            </form>

            <form method="post"
              action="{{ route('admin.contests.contestants.move.down', ['contest' => $contest->id, 'category' => $category->id, 'contestant' => $contestant->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Down @endbutton
            </form>
            <form method="post"
              action="{{ route('admin.contests.categories.contestants.destroy', ['contest' => $contest->id, 'category' => $category->id, 'contestant' => $contestant->id]) }}"
              class="inline-block btn @if ($contestant->pivot->scores()->count()) remove-score-confirmation-form @endif">
              @csrf
              @method('DELETE')

              <input type="hidden"
                name="column"
                value="category_contestant_id" />
              <input type="hidden"
                name="value"
                value="{{ $contestant->pivot->id }}" />
              <input type="hidden"
                name="auth_password" />


              @if ($errors->{"category_contestant_id_{$contestant->pivot->id}"}->any())
                <div class="mb-1 text-sm italic text-red-500">{{ $errors->{"category_contestant_id_{$contestant->pivot->id}"}->first() }}</div>
              @endif

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
                # {{ $contestant->order . ' - ' . $contestant->name }}
              </div>
              <div class="mt-2">
                <span class="inline-block px-2 py-1 font-normal text-red-100 bg-red-500 rounded">Removed</span>
              </div>
            </div>
            <div class="italic">
              {{ $contestant->alias }}
            </div>
          </div>
          <div class="flex-shrink whitespace-no-wrap">
            <form method="post"
              action="{{ route('admin.contests.categories.contestants.store', ['contest' => $contest->id, 'category' => $category->id]) }}"
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

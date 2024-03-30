<tab-item title="Judges">
  <ul>
    <li class="p-4">
      <form method="post"
        action="{{ route('admin.contests.judges.store', ['contest' => $contest->id, 'activeTab' => 'Judges']) }}"
        class="flex flex-wrap items-start lg:flex-no-wrap">
        @csrf
        @formField(['error' => 'name', 'class' => 'block flex-grow lg:mr-4 mb-6 lg:mb-0'])
        <div>
          <input-picker api="{{ route('admin.judges.index') }}"
            hidden-name="user_id"
            display-name="name"
            hidden-property="id"
            display-property="name"
            hidden-value="{{ old('user_id') ?? '' }}"
            display-value="{{ old('name') ?? '' }}"
            placeholder="Enter name of judge..."></input-picker>

          @error('user_id')
            <div class="mt-4 text-xs italic text-red-500">{{ $message }}</div>
          @enderror
        </div>
        @endformField

        @button(['type' => 'submit', 'class' => 'flex-none'])
        Add Judge
        @endbutton
      </form>
    </li>
    @forelse ($contest->judges as $judge)
      <li class="p-4 border-t">
        <div class="flex flex-col items-start lg:flex-row lg:items-center">
          <a href="{{ route('admin.contests.judges.edit', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
            class="flex-grow font-bold lg:pr-2">
            {{ $judge->name }}
          </a>
          <div class="flex justify-between flex-none w-full mt-4 whitespace-no-wrap lg:w-auto lg:mt-0">
            @buttonLink(['href' => route('admin.contests.judges.login', ['contest' => $contest->id, 'judge' => $judge->id]), 'class' => 'mr-2'])
              Login
            @endbuttonLink
            <form method="post"
              action="{{ route('admin.contests.judges.destroy', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
              class="inline-block btn">
              @csrf
              @method('DELETE')
              @button(['type' => 'submit', 'color' => 'danger'])
              Remove
              @endbutton
            </form>
          </div>
        </div>
      </li>
    @empty
      <li class="p-4 border-t">No available Judge(s).</li>
    @endforelse
  </ul>
</tab-item>

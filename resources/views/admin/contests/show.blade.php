@extends('layouts.admin')
@section('content')
  @breadcrumb(['links' => [['url' => route('admin.contests.index'), 'title' => 'Contests']], 'class' => 'mb-10'])
    @pageHeader()
      Contest Details
    @endpageHeader
  @endbreadcrumb

  @if (session('success'))
    <div class="flex">
      @alert()
      {{ session('success') }}
      @endalert
    </div>
  @endif
  @if (session('error'))
    <div class="flex">
      @alert(['type' => 'error'])
      {{ session('error') }}
      @endalert
    </div>
  @endif
  <div class="flex">
    <div class="w-full 3xl:w-3/4">
      @card()
        <div class="flex flex-wrap mb-4 lg:flex-no-wrap">
          <img src="{{ $contest->logo_url }}"
            class="flex-none object-contain object-center w-64 h-64 mx-auto border rounded lg:mr-4">
          <div class="self-center flex-grow mt-4 space-y-2 lg:mt-0">
            <div class="text-lg font-bold">{{ $contest->name }}</div>
            <div class="italic font-medium">{{ $contest->description }}</div>
            <div>{{ $contest->scoring_system_label }} Scoring System</div>
          </div>
          <div class="self-start order-first w-full pl-2 mb-4 text-right whitespace-no-wrap lg:w-auto lg:flex-none lg:order-none lg:mb-0">
            @buttonLink(['href' => route('admin.contests.edit', ['contest' => $contest->id])])
              Edit
            @endbuttonLink
            <form method="post"
              action="{{ route('admin.contests.destroy', ['contest' => $contest->id]) }}"
              class="inline-block">
              @csrf
              @method('DELETE')
              @button(['type' => 'submit', 'color' => 'danger']) Delete @endbutton
            </form>
          </div>
        </div>

        @include('admin.contests.tabs')
      @endcard
    </div>
  </div>
@endsection

@include('admin.categories.form-script')

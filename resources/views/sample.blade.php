@extends('layouts.admin')

@section('content')
<div class="pt-8">
    <h1 class="mb-4">Sample Heading</h1>

    <div class="bg-white rounded border p-8">
        @for($i=0; $i < 100; $i++)
            <div class="bg-red-200 h-8 mb-2"></div>
        @endfor
    </div>
</div>
@endsection

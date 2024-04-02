@extends('layouts.app')

@section('content')
  <div class="container mx-auto mt-6">
    <div class="flex flex-wrap justify-center">
      <div class="w-full max-w-sm">
        <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

          <div class="px-6 py-3 mb-0 font-semibold text-gray-700 bg-gray-200">
            {{ __('Login') }}
          </div>

          <form class="w-full p-6"
            method="POST"
            action="{{ route('login') }}">
            @csrf

            <div class="flex flex-wrap mb-6">
              <label for="username"
                class="block mb-2 text-sm font-bold text-gray-700">
                {{ __('User Name') }}:
              </label>

              <input id="username"
                type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('username') ? ' border-red-500' : '' }}"
                name="username"
                value="{{ old('username') }}"
                required
                autofocus>

              @if ($errors->has('username'))
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $errors->first('username') }}
                </p>
              @endif
            </div>

            <div class="flex flex-wrap mb-6">
              <label for="password"
                class="block mb-2 text-sm font-bold text-gray-700">
                {{ __('Password') }}:
              </label>

              <input id="password"
                type="password"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('password') ? ' border-red-500' : '' }}"
                name="password"
                required>

              @if ($errors->has('password'))
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $errors->first('password') }}
                </p>
              @endif
            </div>

            <div class="flex mb-6">
              <input type="checkbox"
                name="remember"
                id="remember"
                {{ old('remember') ? 'checked' : '' }}>

              <label class="ml-3 text-sm text-gray-700"
                for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>

            <div class="flex flex-wrap items-center">
              <button type="submit"
                class="px-4 py-2 font-bold text-gray-100 bg-green-500 rounded-full hover:bg-green-700 focus:outline-none focus:shadow-outline">
                {{ __('Login') }}
              </button>

              @if (Route::has('password.request'))
                <a class="ml-auto text-sm text-green-500 no-underline whitespace-no-wrap hover:text-green-700"
                  href="{{ route('password.request') }}">
                  {{ __('Forgot Your Password?') }}
                </a>
              @endif
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

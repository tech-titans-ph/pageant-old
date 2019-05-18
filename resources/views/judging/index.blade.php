@extends('layouts.mobile')

@section('content')
<div class="mx-auto pt-12 pb-12">
    <div class="border-b p-4 text-center leading-normal">
        <h2 class="text-lg font-bold">Miss Universe 2019</h2>
        <p class="italic font-thin text-sm">Judge: Jeffrey Naval</p>
    </div>
    <div class="md:rounded md:shadow-md mx-auto">
        <div class="md:flex justify-center md:justify-between border-t">
            <div class="w-full md:w-1/2">
                <img src="https://source.unsplash.com/38GIMU7P5tE/500x500/"
                class="w-full object-contain" alt="Contestant">
            </div>
            <div class="md:w-auto flex md:static p-4 justify-between items-center">
                <a href="#" class="border rounded-full flex justify-center items-center no-underline block h-12 w-12 hover:bg-gray-200">
                    <svg class="feather feather-chevron-left sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <div class="flex items-center justify-center md:px-8">
                    <div class="flex items-center justify-center bg-green-600 rounded-full text-white mr-4 font-bold w-12 h-12">#20</div>
                    <div>
                        <div class="font-bold">Kayson Dawn</div>
                        <div class="font-thin">Philippines</div>
                    </div>
                </div>
                <a href="#" class="border rounded-full flex justify-center items-center no-underline block h-12 w-12 hover:bg-gray-200">
                    <svg class="feather feather-chevron-right sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-reactid="276"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>

        <div class="border-t py-6 px-6">
            <div class="flex">
                <h1 class="text-lg font-bold flex-1 text-gray-700 mb-4">Face</h1>
                <div class="flex-1 text-right text-gray-700 text-sm">
                <span class="font-bold text-xl">38</span>/40
                </div>
            </div>
            <input type="range" min="0" max="40" value="38" class="w-full appearance-none bg-gray-400 h-2">
        </div>

        <div class="border-t py-6 px-6">
            <div class="flex">
                <h1 class="text-lg font-bold flex-1 text-gray-700 mb-4">Body Proportion</h1>
                <div class="flex-1 text-right text-gray-700 text-sm">
                <span class="font-bold text-xl">29</span>/30
                </div>
            </div>
            <input type="range" min="0" max="40" value="38" class="w-full appearance-none bg-gray-400 h-2">
        </div>

        <div class="border-t py-6 px-6">
            <div class="flex">
                <h1 class="text-xl font-bold flex-1 text-gray-700 mb-4">Personality</h1>
                <div class="flex-1 text-right text-gray-700">
                <span class="font-bold text-xl">19</span>/20
                </div>
            </div>
            <input type="range" min="0" max="40" value="38" class="w-full appearance-none bg-gray-400 h-2">
        </div>
        
        <div class="border-t py-6 px-6">
            <div class="flex">
                <h1 class="text-xl font-bold flex-1 text-gray-700 mb-4">Inteligence</h1>
                <div class="flex-1 text-right text-gray-700">
                <span class="font-bold text-xl">9.9</span>/10
                </div>
            </div>
            <input type="range" min="0" max="40" value="38" class="w-full appearance-none bg-gray-400 h-2">
        </div>
    </div>
</div>

<div class="fixed bottom-0 h-12 border-t bg-white w-full flex justify-center items-center">
    <div class="px-2">
        <div class="font-thin text-lg">Score: <span class="font-semibold">96.9/100</span></div>
    </div>
    <div class="px-2">
        <button class="flex items-center items-around shadow bg-green-600 rounded-full py-1 px-1 text-white">
            <span class="pl-4">Submit</span>
            <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        </button>
    </div>
</div>
@endsection
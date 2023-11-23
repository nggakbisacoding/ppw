@extends('layouts.main')

@section('content')
    <div class="p-8 lg:px-32 mx-auto">
        <div class="py-8">
            <p class="text-center text-3xl font-semibold">Tap Card to <span class="text-emerald-600">Edit</span> or <span class="text-red-600">Delete</span></p>
        </div>
        @if (session('success'))
        <div id="success-alert" class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium">Success!</span>{{ session('success') }}
            </div>
          </div>
        @endif
        @if (session('deleted'))
        <div id="deleted-alert" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium">Deleted!</span>{{ session('deleted') }}
            </div>
          </div>
        @endif
    </div>
    <div class="px-8 lg:px-32 justify-center md:justify-start flex flex-wrap gap-4">
        <div class="w-full">
            <a href="{{ route('post.create') }}">
                <button type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Create Post</button></a>
        </div>
        @foreach ($posts as $post)
        <a href="{{ route('post.edit', $post->id) }}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            @if ($post->photo)
            <img class="object-cover w-full rounded-t-lg h-48 md:h-auto md:w-36 md:rounded-none md:rounded-l-lg" src="{{ asset('storage/img/square/'. $post->photo) }}" alt="">
            @else
            <p class="px-8">Tidak ada photo</p>
            @endif
            
            <div class="flex flex-col justify-between p-4 leading-normal">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $post->title }}</h5>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $post->body }}</p>
            </div>
        </a>
        @endforeach

    </div>
    <div class="px-8 pt-8 lg:px-32">

        {{ $posts->links() }}
    </div>


    <script>
    // Add this script at the end of your HTML body or in a separate script file

    // Function to hide the success and deleted alerts after 3 seconds
    function hideAlerts() {
        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            var deletedAlert = document.getElementById('deleted-alert');

            if (successAlert) {
                successAlert.style.display = 'none';
            }

            if (deletedAlert) {
                deletedAlert.style.display = 'none';
            }
        }, 3000); // 3000 milliseconds (3 seconds)
    }

    // Call the function to hide alerts when the page loads
    window.onload = hideAlerts;
</script>
@endsection
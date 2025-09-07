<x-layouts.app>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold">Careers</h1>
        <div>
            @foreach ($jobPosts as $jobPost)
                <div class="bg-white shadow-md rounded-lg p-6 mb-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-gray-800">{{ $jobPost->title }}</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            {{ $jobPost->status === 'Open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $jobPost->status }}
                        </span>
                    </div>
                    <div class="mb-3 text-gray-600">
                        {{ $jobPost->description }}
                    </div>
                    <div class="flex flex-wrap gap-4 mb-3">
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="font-semibold mr-1">Type:</span> {{ $jobPost->employment_type }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="font-semibold mr-1">Location:</span> {{ $jobPost->location }}
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 mb-3">
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="font-semibold mr-1">Salary:</span> 
                            ${{ number_format($jobPost->salary_min) }} - ${{ number_format($jobPost->salary_max) }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="font-semibold mr-1">Posted:</span> {{ \Carbon\Carbon::parse($jobPost->posted_at)->format('M d, Y') }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="font-semibold mr-1">Expires:</span> {{ \Carbon\Carbon::parse($jobPost->expires_at)->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('apply', $jobPost) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-150">
                            Apply Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
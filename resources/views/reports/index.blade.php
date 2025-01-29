<x-site.layout>
    <div class="container">
        @include('reports.header')

        <div class="flex justify-between">
            <h2 class="text-2xl text-gray-800 font-semibold">Report</h2>
            <a href="{{ route('app') }}"><i class="fas fa-arrow-left"></i> Go to Application</a>
        </div>
    </div>
</x-site.layout>

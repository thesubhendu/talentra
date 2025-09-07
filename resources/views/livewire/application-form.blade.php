<div>
    <h1 class="text-2xl font-bold text-center mb-6">Application Form for {{ $jobPost->title }}</h1>
    <form wire:submit="create" class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-8 space-y-6">
            {{ $this->form }}
        <div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-150">
                Submit
            </button>
        </div>
    </form>
</div>

<div>
    <h1>Application Form</h1>
    <form wire:submit="submit" class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-8 space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="tel" id="phone" wire:model="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
        </div>
        <div>
            <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-1">Cover Letter</label>
            <textarea id="cover_letter" wire:model="cover_letter" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
        </div>
        <div>
            <label for="resume" class="block text-sm font-medium text-gray-700 mb-1">Resume</label>
            <input type="file" id="resume" wire:model="resume" class="mt-1 block w-full text-gray-700" />
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-150">
                Submit
            </button>
        </div>
    </form>
</div>

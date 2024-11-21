<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
        <select name="title" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            @foreach(['Arc.', 'Bldr.', 'Dr.', 'Engr.', 'Mr.', 'Mrs.', 'Ms.', 'Pharm.', 'Prof.', 'Pst.', 'Rev.'] as $title)
            <option value="{{ $title }}" {{ old('title') == $title ? 'selected' : '' }}>{{ $title }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
        <input type="text" name="surname" value="{{ old('surname') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required  style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
        <input type="text" name="firstname" value="{{ old('firstname') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Other Name</label>
        <input type="text" name="othername" value="{{ old('othername') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
        <input type="date" name="dob" value="{{ old('dob') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
        <input type="text" name="nationality" value="{{ old('nationality', 'Nigerian') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required      style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>
</div>

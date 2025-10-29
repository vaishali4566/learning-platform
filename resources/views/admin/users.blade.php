@extends('layouts.admin.index')

@section('title', 'User Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] px-6 py-10 text-[#E6EDF7]">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold tracking-wide">User Management</h2>
            <button class="bg-gradient-to-r from-[#0071BC] to-[#00C2FF] hover:opacity-90 text-white font-medium px-4 py-2 rounded-md shadow-md transition">
                + Add User
            </button>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto bg-[#121A2E]/60 border border-[#1E2A45] rounded-lg shadow-lg">
            <table class="w-full text-left">
                <thead class="bg-[#1B2540]/80 border-b border-[#24304F]">
                    <tr>
                        <th class="px-6 py-3 text-sm font-medium">#</th>
                        <th class="px-6 py-3 text-sm font-medium">Profile</th>
                        <th class="px-6 py-3 text-sm font-medium">Name</th>
                        <th class="px-6 py-3 text-sm font-medium">Email</th>
                        <th class="px-6 py-3 text-sm font-medium">Country</th>
                        <th class="px-6 py-3 text-sm font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTable" class="divide-y divide-[#1E2A45]/50">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Loading users...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.getElementById("userTable");

    try {
        // ✅ Correct backend route
        const response = await fetch("{{ route('admin.users.fetch') }}");
        const data = await response.json();

        if (data.status === 'success' && Array.isArray(data.users) && data.users.length > 0) {
            tbody.innerHTML = data.users.map((user, index) => `
                <tr class="hover:bg-[#18223C]/60 transition">
                    <td class="px-6 py-3 text-sm text-gray-300">${index + 1}</td>
                    <td class="px-6 py-3">
                        <img src="${user.profile_image ? '/storage/' + user.profile_image : '/default-avatar.png'}"
                             class="w-10 h-10 rounded-full object-cover border border-[#2C3A63]"
                             alt="${user.name}">
                    </td>
                    <td class="px-6 py-3 text-sm font-medium">${user.name}</td>
                    <td class="px-6 py-3 text-sm text-gray-400">${user.email}</td>
                    <td class="px-6 py-3 text-sm">${user.country ?? '-'}</td>
                    <td class="px-6 py-3 text-right">
                        <button class="px-3 py-1 text-sm rounded-md bg-[#2E3B63] hover:bg-[#0071BC] transition">Edit</button>
                        <button class="px-3 py-1 text-sm rounded-md bg-red-600/70 hover:bg-red-700 transition">Delete</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">No users found</td></tr>`;
        }

    } catch (error) {
        console.error("Error fetching users:", error);
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-red-500">Error loading users</td></tr>`;
    }
});
</script>
@endsection

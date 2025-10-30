@extends('layouts.admin.index')

@section('title', 'User Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] px-6 py-10 text-[#E6EDF7]">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold tracking-wide">User Management</h2>
            <button id="openAddUserModal"
                class="bg-gradient-to-r from-[#0071BC] to-[#00C2FF] hover:opacity-90 text-white font-medium px-4 py-2 rounded-md shadow-md transition">
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

<!-- 🟢 Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
    <div class="bg-[#1B2540] border border-[#26304D] rounded-lg p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-semibold mb-4 text-[#E6EDF7]">Edit User</h3>
        <form id="editUserForm" class="space-y-4">
            <input type="hidden" id="editUserId">

            <div>
                <label class="block text-sm mb-1">Name</label>
                <input type="text" id="editName"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" id="editEmail"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Country</label>
                <input type="text" id="editCountry"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" id="closeEditModal"
                        class="px-4 py-2 bg-gray-600/70 hover:bg-gray-700 rounded-md">Cancel</button>
                <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-[#0071BC] to-[#00C2FF] hover:opacity-90 rounded-md text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- 🟢 Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
    <div class="bg-[#1B2540] border border-[#26304D] rounded-lg p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-semibold mb-4 text-[#E6EDF7]">Add New User</h3>
        <form id="addUserForm" class="space-y-4">

            <div>
                <label class="block text-sm mb-1">Name</label>
                <input type="text" id="addName"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" id="addEmail"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" id="addPassword"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Country</label>
                <input type="text" id="addCountry"
                       class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" id="closeAddModal"
                        class="px-4 py-2 bg-gray-600/70 hover:bg-gray-700 rounded-md">Cancel</button>
                <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-[#0071BC] to-[#00C2FF] hover:opacity-90 rounded-md text-white">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- 🔔 Toast Notification -->
<div id="toast"
     class="hidden fixed bottom-6 right-6 bg-[#d5d7de] text-[#171717] px-5 py-3 rounded-md shadow-lg border-l-4 border-[#00C2FF] transition-all duration-300 transform translate-y-3 opacity-0 z-[9999]">
    <span id="toastMessage"></span>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.getElementById("userTable");
    const editModal = document.getElementById("editUserModal");
    const addModal = document.getElementById("addUserModal");
    const openAddModalBtn = document.getElementById("openAddUserModal");
    const closeAddModal = document.getElementById("closeAddModal");
    const closeEditModal = document.getElementById("closeEditModal");
    const editForm = document.getElementById("editUserForm");
    const addForm = document.getElementById("addUserForm");

    const toast = document.getElementById("toast");
    const toastMessage = document.getElementById("toastMessage");

    // 🟢 Toast Function
    function showToast(message, type = 'info') {
        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'opacity-0', 'translate-y-3');
        toast.classList.remove('border-[#00C2FF]', 'border-green-400', 'border-red-500');

        toast.classList.add(type === 'success' ? 'border-green-400' :
                            type === 'error' ? 'border-red-500' : 'border-[#00C2FF]');

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-3');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 3000);
    }

    // 🟢 Fetch Users
    const fetchUsers = async () => {
        try {
            const response = await fetch(`{{ route('admin.users.fetch') }}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.status === 'success') {
                tbody.innerHTML = data.users.map((user, i) => `
                    <tr class="hover:bg-[#18223C]/60 transition">
                        <td class="px-6 py-3 text-sm">${i + 1}</td>
                        <td class="px-6 py-3">
                            <img src="${user.profile_image ? '/storage/' + user.profile_image : '/default-avatar.png'}"
                                 class="w-10 h-10 rounded-full object-cover border border-[#2C3A63]" alt="${user.name}">
                        </td>
                        <td class="px-6 py-3 text-sm font-medium">${user.name}</td>
                        <td class="px-6 py-3 text-sm">${user.email}</td>
                        <td class="px-6 py-3 text-sm">${user.country ?? '-'}</td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <button data-id="${user.id}" data-name="${user.name}" data-email="${user.email}" data-country="${user.country ?? ''}"
                                class="editBtn px-3 py-1 text-sm bg-[#2E3B63] hover:bg-[#0071BC] rounded-md">Edit</button>
                            <button data-id="${user.id}" class="deleteBtn px-3 py-1 text-sm bg-red-600/70 hover:bg-red-700 rounded-md">Delete</button>
                        </td>
                    </tr>`).join('');
            } else {
                showToast("Failed to load users", "error");
            }
        } catch (err) {
            console.error(err);
            showToast("Error loading users", "error");
        }
    };

    // 🟢 Add User
    openAddModalBtn.onclick = () => addModal.classList.remove("hidden");
    closeAddModal.onclick = () => addModal.classList.add("hidden");

    addForm.onsubmit = async e => {
        e.preventDefault();
        const payload = {
            name: addName.value,
            email: addEmail.value,
            password: addPassword.value,
            country: addCountry.value,
            _token: '{{ csrf_token() }}'
        };

        const res = await fetch(`/admin/users/add`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (data.status === "success") {
            showToast("User added successfully", "success");
            addModal.classList.add("hidden");
            addForm.reset();
            fetchUsers();
        } else {
            showToast(data.message || "Failed to add user", "error");
        }
    };

    // 🟢 Edit User
    document.addEventListener('click', e => {
        if (e.target.classList.contains('editBtn')) {
            const btn = e.target.dataset;
            editUserId.value = btn.id;
            editName.value = btn.name;
            editEmail.value = btn.email;
            editCountry.value = btn.country;
            editModal.classList.remove('hidden');
        }
    });

    closeEditModal.onclick = () => editModal.classList.add('hidden');

    editForm.onsubmit = async e => {
        e.preventDefault();
        const id = editUserId.value;
        const payload = {
            name: editName.value,
            email: editEmail.value,
            country: editCountry.value,
            _token: '{{ csrf_token() }}'
        };

        const res = await fetch(`/admin/users/update/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (data.status === 'success') {
            showToast("User updated successfully", "success");
            editModal.classList.add('hidden');
            fetchUsers();
        } else {
            showToast("Failed to update user", "error");
        }
    };

    // 🟢 Delete User
    document.addEventListener("click", async e => {
        if (e.target.classList.contains("deleteBtn")) {
            const id = e.target.dataset.id;
            if (!confirm("Are you sure you want to delete this user?")) return;

            try {
                const res = await fetch(`/admin/users/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (data.status === 'success') {
                    showToast("User deleted successfully", "success");
                    fetchUsers();
                } else {
                    showToast("Failed to delete user", "error");
                }
            } catch {
                showToast("Something went wrong", "error");
            }
        }
    });

    fetchUsers();
});
</script>
@endsection

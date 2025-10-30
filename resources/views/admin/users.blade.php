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

<!-- 🔔 Toast -->
<div id="toast"
     class="hidden fixed bottom-6 right-6 bg-[#121A2E] text-white px-5 py-3 rounded-lg shadow-lg border-l-4 border-[#00C2FF] transition transform duration-300">
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

    function showToast(message, type = 'info') {
        toastMessage.textContent = message;
        toast.classList.remove('hidden');
        toast.classList.remove('border-[#00C2FF]', 'border-green-400', 'border-red-500');

        if (type === 'success') toast.classList.add('border-green-400');
        else if (type === 'error') toast.classList.add('border-red-500');
        else toast.classList.add('border-[#00C2FF]');

        setTimeout(() => toast.classList.add('opacity-0', 'translate-y-2'), 3000);
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('opacity-0', 'translate-y-2');
        }, 3500);
    }

    // 🟢 Fetch Users
    const fetchUsers = async () => {
        try {
            const response = await fetch("{{ route('admin.users.fetch') }}");
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
                        <td class="px-6 py-3 text-right">
                            <button data-id="${user.id}" data-name="${user.name}" data-email="${user.email}" data-country="${user.country ?? ''}"
                                class="editBtn px-3 py-1 text-sm bg-[#2E3B63] hover:bg-[#0071BC] rounded-md">Edit</button>
                        </td>
                    </tr>`).join('');
            }
        } catch {
            showToast("Error loading users", "error");
        }
    };

    // 🟢 Open Add Modal
    openAddModalBtn.addEventListener("click", () => addModal.classList.remove("hidden"));
    closeAddModal.addEventListener("click", () => addModal.classList.add("hidden"));

    // 🟢 Add User Submit
    addForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const payload = {
            name: document.getElementById("addName").value,
            email: document.getElementById("addEmail").value,
            password: document.getElementById("addPassword").value,
            country: document.getElementById("addCountry").value,
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
    });

    // 🟢 Open Edit Modal
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('editBtn')) {
            const btn = e.target;
            document.getElementById('editUserId').value = btn.dataset.id;
            document.getElementById('editName').value = btn.dataset.name;
            document.getElementById('editEmail').value = btn.dataset.email;
            document.getElementById('editCountry').value = btn.dataset.country;
            editModal.classList.remove('hidden');
        }
    });

    closeEditModal.addEventListener('click', () => editModal.classList.add('hidden'));

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('editUserId').value;
        const payload = {
            name: document.getElementById('editName').value,
            email: document.getElementById('editEmail').value,
            country: document.getElementById('editCountry').value,
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
    });

    fetchUsers();
});
</script>
@endsection

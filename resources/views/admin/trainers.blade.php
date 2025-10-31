@extends('layouts.admin.index')

@section('title', 'Trainer Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] px-6 py-10 text-[#E6EDF7]">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold tracking-wide">Trainer Management</h2>
            <button id="openAddTrainerModal"
                class="bg-gradient-to-r from-[#0071BC] to-[#00C2FF] hover:opacity-90 text-white font-medium px-4 py-2 rounded-md shadow-md transition">
                + Add Trainer
            </button>
        </div>

        <!-- Trainers Table -->
        <div class="overflow-x-auto bg-[#121A2E]/60 border border-[#1E2A45] rounded-lg shadow-lg">
            <table class="w-full text-left">
                <thead class="bg-[#1B2540]/80 border-b border-[#24304F]">
                    <tr>
                        <th class="px-6 py-3 text-sm font-medium">#</th>
                        <th class="px-6 py-3 text-sm font-medium">Name</th>
                        <th class="px-6 py-3 text-sm font-medium">Email</th>
                        <th class="px-6 py-3 text-sm font-medium">Qualification</th>
                        <th class="px-6 py-3 text-sm font-medium">Specialization</th>
                        <th class="px-6 py-3 text-sm font-medium">Country</th>
                        <th class="px-6 py-3 text-sm font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="trainerTable" class="divide-y divide-[#1E2A45]/50">
                    <tr><td colspan="7" class="text-center py-4 text-gray-500">Loading trainers...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 🟢 Edit Trainer Modal -->
<div id="editTrainerModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
    <div class="bg-[#1B2540] border border-[#26304D] rounded-lg p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-semibold mb-4 text-[#E6EDF7]">Edit Trainer</h3>
        <form id="editTrainerForm" class="space-y-4">
            <input type="hidden" id="editTrainerId" name="id">

            <div>
                <label class="block text-sm mb-1">Name</label>
                <input type="text" id="editName" name="name" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" id="editEmail" name="email" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Qualification</label>
                <input type="text" id="editQualification" name="qualification" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]">
            </div>

            <div>
                <label class="block text-sm mb-1">Specialization</label>
                <input type="text" id="editSpecialization" name="specialization" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]">
            </div>

            <div>
                <label class="block text-sm mb-1">Country</label>
                <input type="text" id="editCountry" name="country" 
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

<!-- 🟢 Add Trainer Modal -->
<div id="addTrainerModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
    <div class="bg-[#1B2540] border border-[#26304D] rounded-lg p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-semibold mb-4 text-[#E6EDF7]">Add New Trainer</h3>
        <form id="addTrainerForm" class="space-y-4">
            <div>
                <label class="block text-sm mb-1">Name</label>
                <input type="text" id="addName" name="name" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" id="addEmail" name="email" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" id="addPassword" name="password" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]" required>
            </div>

            <div>
                <label class="block text-sm mb-1">Qualification</label>
                <input type="text" id="addQualification" name="qualification" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]">
            </div>

            <div>
                <label class="block text-sm mb-1">Specialization</label>
                <input type="text" id="addSpecialization" name="specialization" 
                    class="w-full bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7]">
            </div>

            <div>
                <label class="block text-sm mb-1">Country</label>
                <input type="text" id="addCountry" name="country" 
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
     class="hidden fixed bottom-6 right-6 bg-[#d5d7de] text-[#171717] px-5 py-3 rounded-md shadow-lg border-l-4 border-[#0099ff] transition transform duration-300">
    <span id="toastMessage"></span>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.getElementById("trainerTable");
    const editModal = document.getElementById("editTrainerModal");
    const addModal = document.getElementById("addTrainerModal");
    const openAddModalBtn = document.getElementById("openAddTrainerModal");
    const closeAddModal = document.getElementById("closeAddModal");
    const closeEditModal = document.getElementById("closeEditModal");
    const editForm = document.getElementById("editTrainerForm");
    const addForm = document.getElementById("addTrainerForm");
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

    async function fetchTrainers() {
        try {
            const res = await fetch(`{{ route('admin.trainers.fetch') }}`, { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            if (data.status === 'success') {
                tbody.innerHTML = data.trainers.length
                    ? data.trainers.map((t, i) => `
                        <tr class="hover:bg-[#18223C]/60 transition">
                            <td class="px-6 py-3 text-sm">${i + 1}</td>
                            <td class="px-6 py-3 text-sm font-medium">${t.name}</td>
                            <td class="px-6 py-3 text-sm">${t.email}</td>
                            <td class="px-6 py-3 text-sm">${t.qualification ?? '-'}</td>
                            <td class="px-6 py-3 text-sm">${t.specialization ?? '-'}</td>
                            <td class="px-6 py-3 text-sm">${t.country ?? '-'}</td>
                            <td class="px-6 py-3 text-right space-x-2">
                                <button data-id="${t.id}" data-name="${t.name}" data-email="${t.email}" 
                                    data-qualification="${t.qualification ?? ''}" 
                                    data-specialization="${t.specialization ?? ''}" 
                                    data-country="${t.country ?? ''}"
                                    class="editBtn px-3 py-1 text-sm bg-[#2E3B63] hover:bg-[#0071BC] rounded-md">Edit</button>
                                <button data-id="${t.id}" 
                                    class="deleteBtn px-3 py-1 text-sm bg-red-600/70 hover:bg-red-700 rounded-md">Delete</button>
                            </td>
                        </tr>
                    `).join('')
                    : `<tr><td colspan="7" class="text-center py-4 text-gray-500">No trainers found</td></tr>`;
            }
        } catch {
            showToast("Error loading trainers", "error");
        }
    }

    openAddModalBtn.addEventListener("click", () => addModal.classList.remove("hidden"));
    closeAddModal.addEventListener("click", () => addModal.classList.add("hidden"));
    closeEditModal.addEventListener("click", () => editModal.classList.add("hidden"));

    addForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(addForm);
        formData.append('_token', '{{ csrf_token() }}');
        const res = await fetch(`{{ route('admin.trainers.add') }}`, { method: 'POST', headers: { 'Accept': 'application/json' }, body: formData });
        const data = await res.json().catch(() => null);
        if (res.ok && data?.status === 'success') {
            showToast('Trainer added successfully', 'success');
            addModal.classList.add('hidden');
            addForm.reset();
            fetchTrainers();
        } else showToast('Failed to add trainer', 'error');
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('editBtn')) {
            const t = e.target.dataset;
            document.getElementById('editTrainerId').value = t.id;
            document.getElementById('editName').value = t.name;
            document.getElementById('editEmail').value = t.email;
            document.getElementById('editQualification').value = t.qualification;
            document.getElementById('editSpecialization').value = t.specialization;
            document.getElementById('editCountry').value = t.country;
            editModal.classList.remove('hidden');
        }
    });

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('editTrainerId').value;
        const formData = new FormData(editForm);
        formData.append('_token', '{{ csrf_token() }}');
        const res = await fetch(`/admin/trainers/update/${id}`, { method: 'POST', headers: { 'Accept': 'application/json' }, body: formData });
        const data = await res.json().catch(() => null);
        if (res.ok && data?.status === 'success') {
            showToast('Trainer updated successfully', 'success');
            editModal.classList.add('hidden');
            fetchTrainers();
        } else showToast('Failed to update trainer', 'error');
    });

    document.addEventListener('click', async (e) => {
        if (e.target.classList.contains('deleteBtn')) {
            if (!confirm('Delete this trainer?')) return;
            const id = e.target.dataset.id;
            const res = await fetch(`/admin/trainers/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json().catch(() => null);
            if (res.ok && data?.status === 'success') {
                showToast('Trainer deleted successfully', 'success');
                fetchTrainers();
            } else showToast('Failed to delete trainer', 'error');
        }
    });

    fetchTrainers();
});
</script>
@endsection

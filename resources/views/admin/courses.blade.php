@extends('layouts.admin.index')

@section('title', 'Course Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] px-6 py-10 text-[#E6EDF7] relative">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold tracking-wide">Course Management</h2>
        </div>

        <!-- Toast Notification -->
        <div id="toast"
            class="hidden fixed top-6 right-6 bg-[#1B2540] border border-[#24304F] text-[#E6EDF7] px-5 py-3 rounded-lg shadow-lg transition-opacity duration-500 opacity-0 z-50">
        </div>

        <!-- Confirm Modal -->
        <div id="confirmModal"
            class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-[#121A2E] border border-[#2C3A63] rounded-xl p-6 w-full max-w-sm shadow-lg text-center">
                <h3 id="confirmTitle" class="text-lg font-semibold mb-3">Confirm Action</h3>
                <p id="confirmMessage" class="text-gray-300 mb-5">Are you sure?</p>
                <div class="flex justify-center gap-4">
                    <button id="confirmYes"
                        class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg text-sm font-medium text-white transition">Yes</button>
                    <button id="confirmNo"
                        class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-medium text-white transition">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Courses Table -->
        <div class="overflow-x-auto bg-[#121A2E]/60 border border-[#1E2A45] rounded-lg shadow-lg">
            <table class="w-full text-left">
                <thead class="bg-[#1B2540]/80 border-b border-[#24304F]">
                    <tr>
                        <th class="px-6 py-3 text-sm font-medium">#</th>
                        <th class="px-6 py-3 text-sm font-medium">Thumbnail</th>
                        <th class="px-6 py-3 text-sm font-medium">Title</th>
                        <th class="px-6 py-3 text-sm font-medium">Trainer</th>
                        <th class="px-6 py-3 text-sm font-medium">Price</th>
                        <th class="px-6 py-3 text-sm font-medium">Duration</th>
                        <th class="px-6 py-3 text-sm font-medium">Difficulty</th>
                        <th class="px-6 py-3 text-sm font-medium">Status</th>
                        <th class="px-6 py-3 text-sm font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="courseTable" class="divide-y divide-[#1E2A45]/50">
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Loading courses...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.getElementById("courseTable");
    const toast = document.getElementById("toast");
    const confirmModal = document.getElementById("confirmModal");
    const confirmYes = document.getElementById("confirmYes");
    const confirmNo = document.getElementById("confirmNo");
    const confirmTitle = document.getElementById("confirmTitle");
    const confirmMessage = document.getElementById("confirmMessage");

    // ✅ Toast notification
    function showToast(message, type = "info") {
        toast.textContent = message;
        toast.className = `hidden fixed bottom-6 right-6 bg-[#d5d7de] text-[#171717] px-5 py-3 rounded-md shadow-lg border-l-4 border-[#0099ff] transition transform duration-300`;

        toast.classList.remove("hidden");
        setTimeout(() => toast.classList.add("opacity-100"), 100);
        setTimeout(() => {
            toast.classList.remove("opacity-100");
            setTimeout(() => toast.classList.add("hidden"), 500);
        }, 2500);
    }

    // ✅ Custom confirm modal
    function confirmAction(title, message) {
        return new Promise((resolve) => {
            confirmTitle.textContent = title;
            confirmMessage.textContent = message;
            confirmModal.classList.remove("hidden");

            confirmYes.onclick = () => {
                confirmModal.classList.add("hidden");
                resolve(true);
            };
            confirmNo.onclick = () => {
                confirmModal.classList.add("hidden");
                resolve(false);
            };
        });
    }

    // 🟢 Fetch courses
    async function fetchCourses() {
        try {
            const response = await fetch(`{{ route('admin.courses.fetch') }}`);
            const data = await response.json();

            if (data.status === 'success' && Array.isArray(data.courses) && data.courses.length > 0) {
                tbody.innerHTML = data.courses.map((course, index) => `
                    <tr class="hover:bg-[#18223C]/60 transition">
                        <td class="px-6 py-3 text-sm text-gray-300">${index + 1}</td>
                        <td class="px-6 py-3">
                            <img src="${course.thumbnail ? '/storage/' + course.thumbnail : '/default-course.png'}"
                                class="w-12 h-12 rounded-lg object-cover border border-[#2C3A63]" alt="${course.title}">
                        </td>
                        <td class="px-6 py-3 text-sm font-medium">${course.title}</td>
                        <td class="px-6 py-3 text-sm text-gray-400">${course.trainer?.name ?? '-'}</td>
                        <td class="px-6 py-3 text-sm">${course.price ? '₹' + course.price : 'Free'}</td>
                        <td class="px-6 py-3 text-sm">${course.duration ?? '-'}</td>
                        <td class="px-6 py-3 text-sm">${course.difficulty ?? '-'}</td>
                        <td class="px-6 py-3 text-sm">
                            <span class="px-2 py-1 text-xs rounded-md ${
                                course.status === 'approved'
                                    ? 'bg-green-600/50 text-green-200'
                                    : course.status === 'rejected'
                                    ? 'bg-red-600/50 text-red-200'
                                    : 'bg-yellow-600/50 text-yellow-200'
                            }">${course.status ?? 'pending'}</span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <button data-id="${course.id}" data-status="approved" 
                                class="approveBtn px-3 py-1 text-sm rounded-md bg-[#288a42] hover:bg-green-700 transition">Approve</button>
                            <button data-id="${course.id}" data-status="rejected" 
                                class="rejectBtn px-3 py-1 text-sm rounded-md bg-yellow-600 hover:bg-yellow-700 transition">Reject</button>
                            <button data-id="${course.id}" 
                                class="deleteBtn px-3 py-1 text-sm rounded-md bg-red-600 hover:bg-red-700 transition">Delete</button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-gray-500">No courses found</td></tr>`;
            }
        } catch (error) {
            console.error("Error fetching courses:", error);
            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-red-500">Error loading courses</td></tr>`;
        }
    }

    // 🟢 Approve / Reject
    document.addEventListener("click", async (e) => {
        if (e.target.classList.contains("approveBtn") || e.target.classList.contains("rejectBtn")) {
            const id = e.target.dataset.id;
            const status = e.target.dataset.status;
            const confirmed = await confirmAction(
                "Confirm Course Status",
                `Are you sure you want to ${status} this course?`
            );
            if (!confirmed) return;

            try {
                const response = await fetch(`/admin/courses/courses/update-status/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ status }),
                });

                const data = await response.json();
                if (data.success) {
                    showToast(`Course ${status} successfully!`, "success");
                    fetchCourses();
                } else {
                    showToast("Failed to update course status!", "error");
                }
            } catch (err) {
                console.error("Error updating status:", err);
                showToast("Something went wrong!", "error");
            }
        }
    });

    // 🟢 Delete
    document.addEventListener("click", async (e) => {
        if (e.target.classList.contains("deleteBtn")) {
            const id = e.target.dataset.id;
            const confirmed = await confirmAction("Confirm Delete", "Are you sure you want to delete this course?");
            if (!confirmed) return;

            try {
                const response = await fetch(`/admin/courses/courses/${id}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                });

                const data = await response.json();
                if (data.success) {
                    showToast("Course deleted successfully!", "success");
                    fetchCourses();
                } else {
                    showToast("Failed to delete course!", "error");
                }
            } catch (err) {
                console.error("Error deleting course:", err);
                showToast("Something went wrong!", "error");
            }
        }
    });

    // Initial fetch
    fetchCourses();
});
</script>
@endsection

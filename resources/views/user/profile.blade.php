@extends('layouts.user.index')

@section('content')
<div class="relative h-full flex justify-center px-6 py-10 overflow-hidden bg-gray-50
    dark:bg-gradient-to-br dark:from-[#0B1120] dark:via-[#0E162B] dark:to-[#0B1A2E]">

    <div class="w-full max-w-6xl mx-auto bg-white dark:bg-[#1C2541]/95  text-gray-800 dark:text-gray-300 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 lg:pr-8 py-8 flex flex-col lg:flex-row gap-10">
        <!-- LEFT SIDEBAR NAVIGATION     -->
        <div class="lg:w-64 lg:border-r border-gray-200 dark:border-gray-700 px-6">

            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-8 pl-2 lg:pl-5">Account Settings</h2>

            <div class="flex lg:flex-col gap-4 pl-2 lg:gap-0 flex-wrap">
                <button class="sidebar-tab-btn active lg:px-3 lg:py-2 flex gap-2 items-center lg:hover:bg-gray-100 dark:lg:hover:bg-[#161c33]"
                        data-tab="profile">
                    <i data-lucide="circle-user-round" class="w-5 h-5"></i>
                    My Profile
                </button>

                <button class="sidebar-tab-btn lg:px-3 lg:py-2 flex gap-2 items-center lg:hover:bg-gray-100 dark:lg:hover:bg-[#161c33]"
                        data-tab="security">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    Security
                </button>

                <button class="sidebar-tab-btn text-red-600 delete-btn dark:text-red-400 lg:px-3 lg:py-2 flex gap-2 items-center lg:hover:bg-gray-100 dark:lg:hover:bg-[#161c33]"
                        data-tab="delete">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                    Delete Account
                </button>
            </div>
        </div>

        <!-- RIGHT CONTENT AREA -->
        <div class="flex-1 w-full px-8 lg:px-0">

            <!-- TAB 1: MY PROFILE -->
            <div class="tab-section shown" id="tab-profile">

                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">My Profile</h3>

                <!-- === 1. PROFILE OVERVIEW BOX === -->
                <div class="box">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-wrap items-start gap-5">
                            <div class="relative group">
                                <img
                                    id="profilePreview"
                                    src="{{ $user->profile_image
                                        ? asset('storage/'.$user->profile_image)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                                    class="w-16 h-16 md:w-24 md:h-24 rounded-full object-cover border"
                                />

                                <!-- Overlay -->
                                <label
                                    for="profile_image"
                                    class="absolute inset-0 bg-black/50 flex items-center justify-center
                                        text-white text-xs font-medium rounded-full opacity-0
                                        group-hover:opacity-100 cursor-pointer transition">
                                    <i data-lucide="camera"></i>
                                </label>

                                <input
                                    type="file"
                                    name="profile_image"
                                    id="profile_image"
                                    class="hidden"
                                    accept="image/*"
                                    form="profileForm"
                                />
                            </div>

                            <div class="mt-2">
                                <p class="text-xl capitalize font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $user->email ?? '' }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1 italic tracking-wide">{{ $user->country ?? '—' }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- === 2. PERSONAL INFORMATION BOX === -->
                <div class="box mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="section-title font-semibold text-lg">Personal Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                        <div>
                            <label class="field-label">First Name</label>
                            <input 
                                type="text" 
                                name="first_name"
                                value="{{ $user->name ?? 'Michael' }}"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"

                            />
                        </div>

                        <div>
                            <label class="field-label">Last Name</label>
                            <input 
                                type="text" 
                                name="last_name"
                                value="{{ $user->last_name ?? 'Rodriguez' }}"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            />
                        </div>

                        <div>
                            <label class="field-label">Email Address</label>
                            <input 
                                type="email" 
                                name="email"
                                value="{{ $user->email }}"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            />
                        </div>

                        <div>
                            <label class="field-label">Phone</label>
                            <input 
                                type="text" 
                                name="phone"
                                value="{{ $user->phone ?? '(213) 555-1234' }}"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <label class="field-label">Bio</label>
                            <textarea 
                                name="bio"
                                rows="3"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            >{{ $user->bio ?? 'Product Designer' }}</textarea>
                        </div>

                    </div>
                </div>

                <!-- === 3. ADDRESS BOX === -->
                <div class="box mt-8 mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="section-title font-semibold text-lg">Address</h4>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 text-sm">

                        <div>
                            <label class="field-label">Country</label>
                            <input 
                                type="text" 
                                name="country"
                                value="{{ $user->country ?? 'United States' }}"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            />
                        </div>

                        <div>
                            <label class="field-label">City / State</label>
                            <input 
                                type="text" 
                                name="city"
                                value="{{ $user->city ?? 'California, USA' }}"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            />
                        </div>

                        <div>
                            <label class="field-label">Postal Code</label>
                            <input 
                                type="text" 
                                name="postal_code"
                                value="ERT 62574"
                                class="input-field block border border-gray-300 dark:border-gray-700 dark:text-gray-400 focus:text-gray-800 focus:dark:text-gray-200 rounded-lg outline-0 bg-gray-50 dark:bg-[#0E1625]/70 focus:bg-transparent focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60 w-full px-3 py-2 mt-1 text-gray-500"
                            />
                        </div>

                    </div>
                </div>

            </div>

            <!-- ================================================= -->
            <!-- TAB 2: SECURITY                                   -->
            <!-- ================================================= -->
            <div class="tab-section hidden" id="tab-security">

                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Security</h3>

                <div class="box mb-8">
                    <h4 class="section-title mb-0.5">Change Password</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Reset your password here.</p>

                    <form>
                        <div class="space-y-4">
                            <input type="password" placeholder="Old Password" class="input-box outline-0 focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60">
                            <input type="password" placeholder="New Password" class="input-box outline-0 focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60">
                            <input type="password" placeholder="Confirm Password" class="input-box outline-0 focus:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60">
                        </div>

                        <button class="btn-primary w-fit mt-5">Update Password</button>
                    </form>
                </div>

            </div>

            <!-- ================================================= -->
            <!-- TAB 3: DELETE ACCOUNT                             -->
            <!-- ================================================= -->
            <div class="tab-section hidden" id="tab-delete">

                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Delete Account</h3>

                <div class="box border-red-300 dark:border-red-600 mb-8">
                    <p class="text-red-600 dark:text-red-400 font-medium mb-4">
                        Warning: Once deleted, your account cannot be recovered.
                    </p>

                    <button class="btn-danger">Delete My Account</button>
                </div>

            </div>

             @if(session('success'))
                <div class="bg-[#00C2FF]/10 text-[#00C2FF] p-3 rounded-md mb-4 text-center text-sm border border-[#00C2FF]/40 font-medium shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>


<!-- ======================= -->
<!-- CUSTOM STYLES           -->
<!-- ======================= -->
<style>
    .sidebar-tab-btn {
        text-align: left;
        /* padding: 0.375rem 1.5rem;  */
        border-radius: 0.5rem;          
        transition: background-color 0.2s ease;
    }

    .dark .sidebar-tab-btn {
        color: #d1d5db;                    
    }

    .dark .delete-btn {
        color: #f87171 !important;
    }

    /* Active State */
    .sidebar-tab-btn.active {
        color: #00C2FF !important;
        font-weight: 500;
    }

    /* -----------------------------
    GENERIC BOX STYLING
    ------------------------------ */
    .box {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;        
        border-radius: 0.75rem;           
        padding: 1.5rem;                  
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    }

    .dark .box {
        background-color: #161c33;         
        border-color: #374151;       
    }

    /* -----------------------------
    TITLES & LABELS
    ------------------------------ */
    .section-title {
        font-size: 1rem;                   
        font-weight: 600;                  
        color: #1f2937;                    
    }

    .dark .section-title {
        color: #f3f4f6;                    
    }

    .field-label {
        display: block;
        font-size: 0.75rem;                
        margin-bottom: 0.25rem;           
        color: #6b7280;                    
    }

    .dark .field-label {
        color: #9ca3af;                   
    }

    .field-value {
        font-weight: 500;
        color: #111827;                   
    }

    .dark .field-value {
        color: #f3f4f6;                   
    }

    /* -----------------------------
    INPUT BOXES
    ------------------------------ */
    .input-box {
        width: 100%;
        padding: 0.5rem 0.75rem;           
        font-size: 0.875rem;               
        border: 1px solid #d1d5db;         
        border-radius: 0.375rem;           
        background-color: #ffffff;
        color: #111827;
    }

    .dark .input-box {
        background-color: #0e1625b3;         
        border-color: #4b5563;             
        color: #f3f4f6;                    
    }

    /* -----------------------------
    BUTTONS
    ------------------------------ */
    .btn-primary {
        padding: 0.5rem 1rem;             
        background-color: #00C2FF;        
        color: #fff;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #00C2FFcc;         
    }

    .btn-danger {
        padding: 0.5rem 1rem;
        background-color: #dc2626;         
        color: #fff;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease;
    }

    .btn-danger:hover {
        background-color: #b91c1c;         
    }

    /* -----------------------------
    TAB TRANSITIONS
    ------------------------------ */
    .tab-section {
        opacity: 0;
        transform: translateY(5px);
        transition: all 0.2s ease;
    }

    .tab-section.shown {
        opacity: 1;
        transform: translateY(0);
    }

</style>

<!-- ======================= -->
<!-- TAB SWITCHING SCRIPT    -->
<!-- ======================= -->
<script>
    document.querySelectorAll(".sidebar-tab-btn").forEach(btn => {
        btn.addEventListener("click", () => {

            // Active state
            document.querySelectorAll(".sidebar-tab-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            // Hide all
            document.querySelectorAll(".tab-section").forEach(tab => {
                tab.classList.remove("shown");
                tab.classList.add("hidden");
            });

            // Show selected
            const id = "tab-" + btn.dataset.tab;
            const tab = document.getElementById(id);
            tab.classList.remove("hidden");
            setTimeout(() => tab.classList.add("shown"), 10);
        });
    });
</script>

@endsection

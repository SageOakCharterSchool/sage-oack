@extends('layouts.welcome')

@section('content')
    <div class="min-h-screen flex overflow-hidden">
        <div class="w-full md:w-1/2 flex flex-col p-8 md:p-12 lg:p-16 justify-between animate-fade-in bg-sage-600">
            <div class="space-y-6">
                <h1 class="text-3xl font-semibold text-white">Student Information Portal</h1>
                <p class="text-sage-100">Please sign in</p><button onclick="javascript:googleLogin()"
                    class="w-full flex items-center gap-3 bg-white rounded-full p-3 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="bg-white rounded-full p-1 flex items-center justify-center"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z">
                            </path>
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z">
                            </path>
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z">
                            </path>
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z">
                            </path>
                        </svg></div><span class="text-gray-600 flex-1 text-left">Sage Oak Google Sign On</span>
                </button>
                <div class="mt-8"><button onclick="javascript:showLogin()"
                        class="flex items-center gap-2 text-sage-100 hover:text-white transition-colors"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-lock h-4 w-4">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg><span id="altLoginText">Reveal alternative login options</span></button></div>
                <div id="alternateLogin" class="mt-4 space-y-4 animate-fade-in d-none">
                    <div class="space-y-2"><label for="email" class="text-sm font-medium text-white">Email</label><input
                            type="email" id="email"
                            class="w-full p-2 border border-sage-200 rounded-md focus:outline-none focus:ring-2 focus:ring-sage-500"
                            placeholder="your@email.com"></div>
                    <div class="space-y-2"><label for="password"
                            class="text-sm font-medium text-white">Password</label><input type="password" id="password"
                            class="w-full p-2 border border-sage-200 rounded-md focus:outline-none focus:ring-2 focus:ring-sage-500"
                            placeholder="••••••••"></div><button onclick="javascript:login()"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 px-4 py-2 w-full bg-sage-100 text-sage-800 hover:bg-white"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-log-in mr-2 h-4 w-4">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" x2="3" y1="12" y2="12"></line>
                        </svg>Sign In</button>
                </div>
            </div>
            <div class="text-sm text-sage-100 mt-8">
                <p>Powered by</p>
                <div class="flex items-center gap-2 mt-1"><span
                        class="font-semibold cursor-pointer hover:text-white transition-colors">Sage Oak
                        Innovation</span><span>©</span></div>
                <p class="mt-1">© Sage Oak {{date("Y")}} • <a href="#" class="hover:underline">Privacy Policy</a> &amp; <a
                        href="#" class="hover:underline">Terms of Service</a></p>
            </div>
        </div>
        <div class="hidden md:block md:w-1/2 bg-sage-100 relative overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center" style="padding:30px;"><img
                    src="/assets/images/sage-landing-logo.png" alt="Sage Oak Logo" class="max-w-3/4 max-h-3/4 object-contain">
            </div>
            <div class="absolute bottom-0 left-0 right-0"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
                    class="w-full">
                    <path fill="#f9fafb" fill-opacity="1"
                        d="M0,192L80,186.7C160,181,320,171,480,186.7C640,203,800,245,960,245.3C1120,245,1280,203,1360,181.3L1440,160L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
                    </path>
                </svg></div>
        </div>
    </div>
@endsection
@push('my_scripts')
    <script>
        function showLogin() {
            if ($("#alternateLogin").hasClass("d-none")) {
                $("#alternateLogin").removeClass("d-none");
                $("#altLoginText").html("Hide alternative login options");
            } else {
                $("#alternateLogin").addClass("d-none");
                $("#altLoginText").html("Reveal alternative login options");
            }
        }

        function login() {
            if (($("email").val() == "") || ($("password").val() == "")) {
                toastr.error("Please enter valid credentials!");
            } else {
                var formData = {
                    'email': $("#email").val(),
                    'password': $("#password").val(),
                    '_token': $("#csrf-token").val(),
                };
                $.ajax({
                    type: 'POST',
                    url: '/login-me-in',
                    data: formData,
                    dataType: "json",
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            toastr.success("Welcome to our system!");
                            window.location.href = "/admin/dashboard";
                        } else {
                            toastr.error("Please enter valid credentials!");
                        }
                    }
                });
            }
        }
        // function login() {
        //     window.location.href = "/login";
        // }

        function googleLogin() {
            window.location.href = "/google-auth/redirect";
        }
    </script>
@endpush

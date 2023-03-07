<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Restoration App</title>
    <link href="https://unpkg.com/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
            integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-xl mx-auto py-12" x-data="uploadForm()">
    <h1 class="text-3xl font-bold mb-4">Restore Photos</h1>
    <p class="text-gray-700 mb-6">Restore and enhance your old photos with our AI model.</p>

    <form class="bg-white rounded-lg shadow-lg p-6 mb-6 hidden">
        <h2 class="text-xl font-bold mb-4">Verify your email first</h2>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="email">
                Email Address
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="email" type="email" placeholder="you@example.com">
        </div>
        <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit">
            Send Token
        </button>
    </form>

    <form class="bg-white rounded-lg shadow-lg p-6 relative" @submit.prevent="submitForm">
        <div x-show="loading" class="absolute top-0 left-0 w-full h-full bg-white flex flex-col items-center justify-center">
            @include('components.spinner')
            <span x-show="uploadProgress < 100" x-text="uploadProgress + '%'"></span>
            <span x-show="uploadProgress == 100">
                Hold on, we are enhancing your photo...
            </span>
        </div>

        <h2 class="text-xl font-bold mb-4">Upload Your Photo</h2>
        <div class="mb-4 p-4 border border-red-200 bg-red-100 rounded" x-show="error" x-text="error"></div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="email">
                Email Address
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="email"
                type="email"
                placeholder="you@example.com"
                x-model="formData.email"
            >
            <div class="mb-4 text-red-900 rounded" x-show="formErrors['email']" x-text="formErrors['email'][0]"></div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="photo">
                Photo
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="photo"
                type="file"
                x-on:change="formData.file = Object.values($event.target.files)[0]"
            >
            <div class="mb-4 text-red-900 rounded" x-show="formErrors['file']" x-text="formErrors['file'][0]"></div>
        </div>
        <button
            :disabled="loading"
            class="text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit"
            :class="{ 'bg-gray-200': loading, 'bg-blue-500  hover:bg-blue-700': !loading }"
            @click.prevent="submitForm"
        >
            Restore and Enhance
        </button>
    </form>

    <div id="result" class="my-4 bg-white rounded-lg shadow-lg p-6" :class="{ 'hidden': !result}">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Restored Photo</h2>
            <a
                class="border p-2 border-blue-500 text-blue-500 font-bold rounded focus:outline-none focus:shadow-outline"
                :href="result"
                download
            >
                Download
            </a>
        </div>
        <img :src="result" alt="Restored Photo" class="mb-4">
    </div>

    <footer class="bg-gray-200 py-4 mt-8">
        <div class="max-w-xl mx-auto px-4">
            <p class="text-gray-600 text-sm text-center">
                &copy; 2023 Image Restoration App. Made with ❤️ by Ahmed Ash.
            </p>
        </div>
    </footer>
</div>
<script>
    function uploadForm() {
        return {
            loading: false,
            formData: {
                email: "",
                file: "",
            },

            result: null,
            error: null,
            formErrors: {},
            uploadProgress: 0,

            submitForm() {
                this.loading = true;
                const data = new FormData()
                Object.keys(this.formData).map((key, index) => {
                    data.append(key, this.formData[key])
                });

                axios.post('/upload', data, {
                    onUploadProgress: (event) => {
                        this.uploadProgress = Math.round((event.loaded / event.total) * 100);
                    }
                }).then(response => {
                    this.result = response.data.result;
                }).catch(error => {
                    this.error = error.response.data.message;
                    if (error.response.status === 422) {
                        this.formErrors = error.response.data.errors;
                    }
                }).finally(() => {
                    this.loading = false;
                })
            }
        }
    }
</script>
</body>
</html>

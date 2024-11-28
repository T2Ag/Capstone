<template>
   <Layout>
      <div class="p-5">
         <button @click="back" class="inline-flex items-center hover:text-gray-700">
            <i class="bi bi-arrow-left text-lg mr-2"></i>
         </button>

         <div class="mb-4">
            <p class="text-[30px] text-gray-600 font-semibold">USER PROFILE</p>
            <p class="text-gray-500 text-sm mt-1">
               Manage your account details and update your profile information below.
            </p>
         </div>
         <div class="px-15 py-3">

            <!-- Profile Update Section -->
            <div class="flex flex-col lg:flex-row bg-white p-5 rounded items-center justify-center">
               <div class="flex flex-col items-center px-4 w-full lg:w-auto text-center">

                  <!-- Success Message -->
                  <div v-if="successMessage" class="px-10 py-2 mb-4">
                     <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative">
                        {{ successMessage }}
                     </div>
                  </div>

                  <!-- Username Update -->
                  <div class="mb-3 lg:min-w-[20rem] w-full">
                     <label for="create-username" class="form-label">Username</label>
                     <input 
                        type="text" 
                        class="form-control w-full" 
                        id="create-username" 
                        name="username" 
                        v-model="form.username"
                        placeholder="Enter your desired username"
                     />
                     <p class="text-gray-500 text-sm mt-1">
                        Your username must be unique and can be a combination of letters, numbers, and underscores.
                     </p>
                     <span class="text-red-500">{{ form.errors.username }}</span>
                  </div>

                  <!-- Save Changes Button -->
                  <div class="mb-4">
                     <button 
                        @click="update" 
                        class="bg-green-400 text-white font-bold border p-2 border-gray-400 rounded hover:bg-green-500 hover:transition duration-200 ease-in-out"
                     >
                        <i class="bi bi-save me-2"></i>Save Changes
                     </button>
                  </div>


                  <Link 
                     :href="route('users.forgotPassword', { user })"
                     class="text-red-500 hover:underline text-sm"
                  >
                     Forgot Password?
                  </Link>
               </div>
            </div>
         </div>         
      </div>
      
   </Layout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

const props = defineProps({
   user: Object,
});

const successMessage = ref(''); // Reactive success message

const form = useForm({
   id: props.user.id,
   username: props.user.username,
});

// Update function
const update = () => {
   form.errors = {};

   form.put(route('users.updateUsername', form.id), {
      onError: (errors) => {
         form.errors = errors;
         successMessage.value = ''; 
      },
      onSuccess: () => {
         successMessage.value = 'Your profile has been successfully updated.';
      }
   });
};

const back = () =>
{
    window.history.back();
}
</script>

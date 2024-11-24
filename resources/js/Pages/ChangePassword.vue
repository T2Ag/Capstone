<template>
   <component :is="layout">
      <div class="px-2 py-2">
         <p class="text-[30px] text-gray-600">CHANGE PASSWORD</p>
         <!-- Add user info -->
         <p class="text-gray-500">Changing password for: {{ user.username }}</p>
      </div>
      
      <div class="px-10 py-3">
        <div class="flex bg-white p-5 rounded">
          <div class="flex flex-col items-center px-4 min-w-[20rem]">
            <!-- Success Message -->
            <div v-if="successMessage" class="px-10 py-2 w-full">
              <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative">
                {{ successMessage }}
              </div>
            </div>

            <!-- Current Password -->
            <div class="mb-3 w-full">
              <label for="current-password" class="form-label">Current Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="current-password" 
                v-model="form.current_password"
              />
              <span class="text-red-500">{{ form.errors.current_password }}</span>
            </div>

            <!-- New Password -->
            <div class="mb-3 w-full">
              <label for="new-password" class="form-label">New Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="new-password" 
                v-model="form.new_password"
              />
              <span class="text-red-500">{{ form.errors.new_password }}</span>
            </div>

            <!-- Verify Password -->
            <div class="mb-3 w-full">
              <label for="verify-password" class="form-label">Verify Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="verify-password" 
                v-model="form.verify_password"
              />
              <span class="text-red-500">{{ form.errors.verify_password }}</span>
            </div>

            <!-- Submit Button -->
            <div>
              <button 
                @click="update"
                class="bg-green-400 text-white font-bold border p-2 border-gray-400 rounded hover:bg-green-500 hover:transition duration-200 ease-in-out"
              >
                <i class="bi bi-save me-2"></i>Update Password
              </button>
            </div>
          </div>
        </div>
      </div>
   </component>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Layout from '../Layouts/Layout.vue'
import TrainerLayout from '../Layouts/TrainerLayout.vue'
import UserLayout from '../Layouts/UserLayout.vue'

const props = defineProps({
    user: Object
})

const successMessage = ref('')

const form = useForm({
   id: props.user.id,
   current_password: '',
   new_password: '',
   verify_password: '',
})

const update = () => {
    form.put(route('updatePassword', form.id), {
        onError: (errors) => {
            form.errors = errors
            successMessage.value = ''
        },
        onSuccess: () => {
            successMessage.value = 'Password successfully updated.'
            form.reset()
        }
    })
}

watch(successMessage, (newValue) => {
    if (newValue) {
        setTimeout(() => {
            successMessage.value = ''
        }, 2000)
    }
})

const layouts = {
    Layout,
    TrainerLayout,
    UserLayout
}

const layout = computed(() => {
    const userLayout = usePage().props.auth.user?.layout || 'Layout'
    return layouts[userLayout] || Layout
})
</script>
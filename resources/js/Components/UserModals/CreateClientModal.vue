<template>
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header flex justify-between">
          <h5 class="modal-title" id="createModalTitle">Create Client</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form @submit.prevent="submit">
          <div class="modal-body">

              <div class="row">
                
                <!-- First Name -->
                <div class="col-6 mb-3">
                  <label for="first_name" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" v-model="form.first_name">
                  <span class="text-red-500">{{ form.errors.first_name }}</span>
                </div>

                <!-- Last Name -->
                <div class="col-6 mb-3">
                  <label for="last_name" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" v-model="form.last_name">
                  <span class="text-red-500">{{ form.errors.last_name }}</span>
                </div>

                <!-- Middle Initial -->
                <div class="col-6 mb-3">
                  <label for="middle_initial" class="form-label">Middle Initial</label>
                  <input type="text" class="form-control" id="middle_initial" name="middle_initial" v-model="form.middle_initial">
                  <span class="text-red-500">{{ form.errors.middle_initial }}</span>
                </div>

                <!-- Gender -->
                <div class="col-6 mb-3">
                  <label for="gender" class="form-label">Gender</label>
                  <select class="form-control" id="gender" name="gender" v-model="form.gender">
                    <option value="" disabled>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                  <span class="text-red-500">{{ form.errors.gender }}</span>
                </div>

                <!-- Registration -->
                <div class="col-6 mb-3">
                  <label for="registration_id" class="form-label">Registration</label>
                  <select class="form-control" id="registration_id" name="registration_id" v-model="form.registration_id">
                    <option value="" disabled>Select Registration</option>
                    <option v-for="registration in registrations" :key="registration.id" :value="registration.id">
                      {{ registration.type }}
                    </option>
                  </select>
                  <span class="text-red-500">{{ form.errors.registration_id }}</span>
                </div>

                <!-- Payment Method -->
                <div class="col-6 mb-3">
                  <label for="payment_method_id" class="form-label">Payment Method</label>
                  <select class="form-control" name="payment_method_id" id="payment_method_id" v-model="form.payment_method_id">
                    <option value="" disabled>Select Payment Method</option>
                    <option v-for="payment_method in payment_methods" :key="payment_method.id" :value="payment_method.id">
                      {{ payment_method.type }}
                    </option>
                  </select>
                </div>

              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="createAsMember" v-model="isMemberChecked">
                <label class="form-check-label" for="createAsMember">
                  Create as Member
                </label>
              </div>

              <!-- Create As Memeber -->
              <div v-if="isMemberChecked">
                <div class="mb-3">
                  <!-- Buttons to toggle between User ID selection and Create User form -->
                  <button
                    type="button"
                    class="btn me-2"
                    :class="showUserIdSelection ? 'btn-primary text-white' : 'btn-outline-primary'"
                    @click="showUserIdSelection = true"
                  >
                    Select User
                  </button>
                  <button
                    type="button"
                    class="btn"
                    :class="!showUserIdSelection ? 'btn-primary text-white' : 'btn-outline-primary'"
                    @click="showUserIdSelection = false"
                  >
                    Create User
                  </button>
                </div>

                <!-- User ID -->
                <div v-if="showUserIdSelection" class="mb-3">
                  <div class="mb-3">
                    <label for="user_id" class="form-label">User ID</label>
                    <select class="form-control" id="user_id" name="user_id" v-model="form.user_id">
                      <option value="" disabled>Select User ID</option>
                      <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.username }}
                      </option>
                    </select>
                    <span class="text-red-500">{{ form.errors.user_id }}</span>
                  </div>
                </div>

                <!-- Create a user -->
                <div v-if="!showUserIdSelection" class="mb-3">
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" class="form-control" id="username" name="username" v-model="form.username">
                      <span class="text-red-500">{{ form.errors.username }}</span>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="password" name="password" v-model="form.password">
                      <span class="text-red-500">{{ form.errors.password }}</span>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="password_confirmation" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation">
                      <span class="text-red-500">{{ form.errors.password_confirmation }}</span>
                    </div>

                  </div>
                </div>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
            <button type="submit" class="btn btn-primary">Create User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  users: Array,
  registrations: Array,
  roles: Array,
  payment_methods: Array
});

const form = useForm({
  user_id: '',
  username: '',
  password: '',
  password_confirmation: '',
  role: '',

  first_name: '',
  last_name: '',
  middle_initial: '',
  gender: '',
  registration_id: '',
  payment_method_id:''
});

const showUserIdSelection = ref(true);
const isMemberChecked = ref(false);

const submit = () => {
  form.post(route('clients.store'), {
    onSuccess: () => {
      form.reset();
       const modalElement = document.querySelector('#createModal');
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
    },
    onError: (errors) => {
      errors.value = errors;
    },
  });
};
</script>

<template>
   <Layout>
      <div class="px-2 py-2">
         <p class="text-[30px] text-gray-600">CLIENTS LIST</p>
      </div>

      <div class="px-2 py-2">

         <div class="flex justify-end my-2">
            <button type="button" class="rounded text-white px-3 py-2 bg-red-700" data-bs-toggle="modal" data-bs-target="#createModal">
               Add Client
            </button>
         </div>

         <CreateClientModal :users="users" :registrations="registrations" :trainings="trainings" :payment_methods="payment_methods" :roles="roles"/>

         <div class="bg-white shadow-md rounded overflow-hidden p-3">

            <!-- Filter by Date -->
             <div class="flex justify-end pb-2">

               <div class="flex items-center align-middle pr-2">
                  <input type="checkbox" id="member_filter" v-model="form.member_filter" @change="filterClients" class="m-1">
                  <label for="member_filter" class="text-gray-500">Members</label>
               </div>

               <div class="text-center pr-2">
                  <label for="date_filter" class="text-gray-500">Year</label>

                  <select v-model="form.year_filter" @change="filterClients" class="form-select" name="year_filter">
                     <option value="all">All Years</option>
                     <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                  </select>

               </div>
               <div class="text-center pr-2">
                  <label for="month_filter" class="text-gray-500">Month</label>
                  <select v-model="form.month_filter" @change="filterClients" class="form-select" name="month_filter">
                     <option value="all">All Months</option>
                     <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
                  </select>
               </div>
               <div class="text-center">
                  <label for="registration_type" class="text-gray-500">Registration Type</label>
                  <select v-model="form.registration_type" @change="filterClients" class="form-select" name="registration_type">
                     <option value="all">All Types</option>
                     <option v-for="registration in registrations" :key="registration.id" :value="registration.type">
                           {{ registration.type }}
                     </option>
                  </select>
               </div>

             </div>



            <table class="w-full text-left text-gray-500 bg-white">
               <thead class="text-l text-700 uppercase bg-gray-100">
                  <tr class="text-center">
                     <th scope="col" class="lg:px-5 px-3 py-3">Client ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Name</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Registration Type</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Payment Type</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Membership</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="client in clients" :key="client.id" class="text-center ">
                     <td>{{ client.id }}</td>
                     <td>  {{ client.first_name }} {{ client.last_name }} </td>
                     <td> {{ client.registration.type }} </td>
                     <td> {{ client.payment_method.type }} </td>
                     <td> {{ formatDate(client.date) }}</td>
                     <td> {{ isMember(client) }} </td>

                     <td>

                        <Link :href="route('clients.view',  { client: client.id })" >
                           view
                        </Link>
         
                        <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(client)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                              <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                           </svg>  
                        </button>
                        
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>

      </div>

   </Layout>

   <!-- Delete User Modal -->
   <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="deleteModalTitle">Delete User</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               Are you sure you want to delete clients # - {{ deleteForm.id }} - {{ deleteForm.last_name }}, {{ deleteForm.first_name }} {{ deleteForm.middle_initial }}?
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="button" class="btn btn-danger" @click="deleteUser">Delete</button>
            </div>
         </div>
      </div>
   </div>
</template>

<script setup>

import Layout from '@/Layouts/Layout.vue';
import CreateClientModal from '@/Components/UserModals/CreateClientModal.vue'
import { useForm, router } from '@inertiajs/vue3';
import {ref, computed} from 'vue';

const props = defineProps({
   users: Array,
   roles: Array,
   clients: Array,
   registrations: Array, 
   trainings: Array,
   payment_methods: Array,
   year_filter: String,
   month_filter: String
 });

 function isMember(client) {
   return client.user_id ? 'Member' : 'Non-member';
}

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

const deleteForm = useForm({
   id: null, 
   first_name: '',
   last_name: '',
   middle_initial: '',
})

const openDeleteModal = (user) => {
   deleteForm.id = user.id;
   deleteForm.first_name = user.first_name;
   deleteForm.last_name = user.last_name;
   deleteForm.middle_initial = user.middle_initial;
}

const deleteUser = () => {
   deleteForm.delete(route('clients.destroy', deleteForm.id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteModal');
         if(modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
               modal.hide();
            }
         }
      }
   });
};

// Generate years starting from 2020 to the current year
const currentYear = new Date().getFullYear();
const years = computed(() => Array.from({ length: currentYear - 2020 + 1 }, (_, i) => 2020 + i));

//Months
const months = [
   { value: '01', label: 'January' },
   { value: '02', label: 'February' },
   { value: '03', label: 'March' },
   { value: '04', label: 'April' },
   { value: '05', label: 'May' },
   { value: '06', label: 'June' },
   { value: '07', label: 'July' },
   { value: '08', label: 'August' },
   { value: '09', label: 'September' },
   { value: '10', label: 'October' },
   { value: '11', label: 'November' },
   { value: '12', label: 'December' }
];

const form = useForm({
   year_filter: props.year_filter || 'all',
   month_filter : props.month_filter  || 'all',
   registration_type : props.registration_type || 'all',
   member_filter : props.member_filter || false
})

const filterClients = () => {
   router.get(route('clients'), { 
      year_filter: form.year_filter,
      month_filter: form.month_filter,
      registration_type: form.registration_type,
      member_filter : form.member_filter
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};


</script>
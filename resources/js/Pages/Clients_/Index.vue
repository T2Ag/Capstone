<template>
   <Layout>
      <div class="p-3">
         <div class="flex justify-between">
            <div class="">
               <p class="text-3xl font-bold text-gray-900 ">Clients List</p>
            </div>
            <div class="">
               <form @submit.prevent="filterClients">
                  <InputField
                     type="search"
                     label=""
                     icon="search"
                     placeholder="Search..."
                     v-model="form.search"
                  />
               </form>
            </div>
         </div>


         <div class="px-2 py-2">

            <div class="flex justify-end my-2">
               <button type="button" class="rounded px-4 py-2 bg-gradient-to-r from-red-500 to-red-700 text-white font-semibold shadow-md " data-bs-toggle="modal" data-bs-target="#createModal">
                  Add Client
               </button>
            </div>

            <CreateClientModal :users="users" :registrations="registrations" :payment_methods="payment_methods" :roles="roles"/>

            <div class="bg-white shadow-md rounded p-3">

               <!-- Filter Section -->
               <div class="flex flex-wrap justify-end pb-4 space-x-4">
                  <!-- Members Filter -->
                  <div class="flex flex-col justify-start items-center pr-2">
                     <label for="member_filter" class="block text-xs text-gray-600 mb-1">Members</label>
                     <div class="p-1">
                        <input 
                        type="checkbox" 
                        id="member_filter" 
                        v-model="form.member_filter" 
                        @change="filterClients" 
                        class="form-checkbox text-indigo-600"
                        >
                     </div>
                  </div>

                  <!-- Year Filter -->
                  <div class="relative">
                     <label for="year_filter" class="block text-xs text-gray-600 mb-1">Year</label>
                     <div class="relative">
                        <select 
                        v-model="form.year_filter" 
                        @change="filterClients" 
                        class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                        <option value="all">All Years</option>
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>

                  <!-- Month Filter -->
                  <div class="relative">
                     <label for="month_filter" class="block text-xs text-gray-600 mb-1">Month</label>
                     <div class="relative">
                        <select 
                        v-model="form.month_filter" 
                        @change="filterClients" 
                        class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                        <option value="all">All Months</option>
                        <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>

                  <!-- Registration Type Filter -->
                  <div class="relative">
                     <label for="registration_type" class="block text-xs text-gray-600 mb-1">Registration Type</label>
                     <div class="relative">
                        <select 
                        v-model="form.registration_type" 
                        @change="filterClients" 
                        class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                        <option value="all">All Types</option>
                        <option v-for="registration in registrations" :key="registration.id" :value="registration.type">
                           {{ registration.type }}
                        </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>
                  
                  <!-- Registration Type Filter -->
                  <div class="relative">
                     <label for="payment_method" class="block text-xs text-gray-600 mb-1">Registration Type</label>
                     <div class="relative">
                        <select 
                        v-model="form.payment_method" 
                        @change="filterClients" 
                        class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                        <option value="all">All Types</option>
                        <option v-for="payment_method in payment_methods" :key="payment_method.id" :value="payment_method.type">
                           {{ payment_method.type }}
                        </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>

                  <!-- Day Filter -->
                  <div class="relative">
                     <label for="day_filter" class="block text-xs text-gray-600 mb-1">Day</label>
                     <input 
                        type="date" 
                        v-model="form.date_filter"
                        @change="filterClients"
                        class="w-full bg-white border border-gray-300 rounded-md pl-3 pr-3 py-2 text-sm text-gray-700"
                     />
                  </div>
               </div>

               
               <div class="overflow-auto">
                  <table class="w-full text-left text-gray-500 bg-white">
                     <thead class="text-l text-700 uppercase bg-gray-100">
                        <tr class="text-center">
                           <th scope="col" class="lg:px-5 px-1 py-3">Client ID</th>
                           <th scope="col" class="lg:px-5 px-1 py-3">Name</th>
                           <th scope="col" class="lg:px-5 px-1 py-3">Registration Type</th>
                           <th scope="col" class="lg:px-5 px-1 py-3">Payment Type</th>
                           <th scope="col" class="lg:px-5 px-1 py-3">Date</th>
                           <th scope="col" class="lg:px-5 px-1 py-3">Membership</th>
                           <th scope="col" class="lg:px-5 px-1 py-3">Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="client in clients.data" :key="client.id" class="text-center ">
                           <td>{{ client.id }}</td>
                           <td>  {{ client.first_name }} {{ client && client.middle_initial ? client.middle_initial + "." : '' }} {{ client.last_name }} </td>
                           <td> {{ client.registration.type }} </td>
                           <td > 
                              <div class="flex lg:flex-row flex-col justify-center">
                                 <div class="mr-2">
                                    {{ client.payment_method.type }}
                                 </div>
                                 <span v-if="client.payment_method.type === 'monthly'">
                                    <!-- Conditionally display 'Active' if first_active_transaction exists, else 'Expired' -->
                                    <span v-if="client.first_active_transaction" class="text-green-500">( Active )</span>
                                    <span v-else class="text-red-500">( Expired )</span>
                                 </span>
                              </div>
                              
                           </td>
                           <td> {{ formatDate(client.date) }}</td>
                           <td> {{ isMember(client) }} </td>

                           <td class="items-center my-auto align-middle py-4">

                              <Link :href="route('clients.view',  { client: client.id })" >
                                 <i class="bi bi-eye text-[1.5rem]"></i>
                              </Link>
               
                              <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(client)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                 </svg>  
                              </button>

                              <button type="button" class="rounded text-green-500 text-[15px]" :data-bs-toggle="'modal'" :data-bs-target="`#editModal-${client.id}`">
                                 <i class="bi bi-pencil text-[1.5rem]"></i>
                              </button>
                              
                              <EditClientModal :client="client" :registrations="registrations" :key="`editModal-${client.id}`"/>
                           </td>
                        </tr>
                     </tbody>
                  </table>

                  <Pagination class="flex mt-4 justify-end" :links="clients.links" />  
               </div>
            

            </div>

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
               Are you sure you want to delete client # - {{ deleteForm.id }} - {{ deleteForm.last_name }}, {{ deleteForm.first_name }} {{ deleteForm.middle_initial }}?
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
import EditClientModal from '../../Components/UserModals/EditClientModal.vue';
import Pagination from '../../Components/Pagination.vue';
import InputField from '../../Components/InputField.vue';
import { useForm, router } from '@inertiajs/vue3';
import {ref, computed} from 'vue';

const props = defineProps({
   users: Array,
   roles: Array,
   clients: (Array, Object),
   registrations: Array, 
   payment_methods: Array,
   year_filter: String,
   payment_method: String,
   registration_type: String,
   month_filter: String,
   search: String
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

const currentYear = new Date().getFullYear();
const years = computed(() => Array.from({ length: currentYear - 2020 + 1 }, (_, i) => 2020 + i));

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
   payment_method : props.payment_method || 'all',
   member_filter : props.member_filter || false,
   date_filter : props.date_filter || '',
   search : props.search || ''
})

const filterClients = () => {
   router.get(route('clients'), { 
      year_filter: form.year_filter,
      month_filter: form.month_filter,
      registration_type: form.registration_type,
      payment_method : form.payment_method,
      member_filter : form.member_filter,
      date_filter: form.date_filter,
      search: form.search
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};


</script>
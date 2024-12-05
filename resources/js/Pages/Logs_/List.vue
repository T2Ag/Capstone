<template>
<Layout>

   <div class="p-3">

      <div class="flex justify-between">
         <div class="">
            <p class="text-3xl font-bold text-gray-900 ">Logs List</p>
         </div>
         <div class="">
            <form @input="filterLogs">
               <InputField
                  type="search"
                  label=""
                  icon="search"
                  placeholder="Search..."
                  v-model="filterForm.search"
               />
            </form>
         </div>
      </div>

   
      <!-- Button for the modal -->
      <div class="flex justify-end my-4 mx-2">
         <button
            type="button"
            class="rounded px-4 py-2 bg-gradient-to-r from-red-500 to-red-700 text-white font-semibold shadow-md "
            data-bs-toggle="modal"
            data-bs-target="#createModal"
         >
            Add Log
         </button>
      </div>
   
      <!-- Create Log Modal -->
      <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header flex justify-between">
            <h5 class="modal-title" id="createModalTitle">Create Log</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form @submit.prevent="submit">
            <div class="modal-body">
               <div class="mb-3">
                  <label for="create-client_id" class="form-label">Select Client</label>
                  <div class="input-group">
                     <input list="clients_options" class="form-control" id="clients" name="clients" autocomplete="off" type="text" v-model="selectedClientName" @input="updateClientId" placeholder="Search and select a client">
                     <datalist id="clients_options">
                        <option v-for="client in clients">
                           {{ client.first_name }} {{ client.last_name }}
                        </option>
                     </datalist>
                     <input type="hidden" name="client_id" v-model="form.client_id"/>
                  </div>
                  <span class="text-red-500">{{ form.errors.client_id }}</span>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Create Log</button>
            </div>
            </form>
         </div>
      </div>
      </div>
   
      <!-- Alert Modal -->
      <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content bg-light shadow-lg rounded-lg">
            <div class="modal-body text-center py-5">
               <div :class="[
              'text-xl font-bold mb-4',
              header === 'Error' || header === 'Please Pay At the Cashier' ? 'text-red-700' : 'text-green-700'
            ]">
               {{ header }}
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
               <p>
                  {{ alertMessage }}
               </p>
            </div>
            </div>
         </div>
      </div>
      </div>
   
      <div class="bg-white shadow-md rounded p-3 m-2">

         <!-- Filter by Date -->
         <div class="flex justify-end pb-4 space-x-4">
            <div class="">
               <label class="block text-xs text-gray-600 mb-1">Year</label>
               <div class="flex items-center">
                  <select 
                     v-model="filterForm.year_filter" 
                     @change="filterLogs" 
                     class="w-full bg-white border border-gray-300 rounded-md pl-3 pr-3 py-2 text-sm text-gray-700"
                  >
                     <option value="all">All Years</option>
                     <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                  </select>
               </div>
            </div>

            <div class="">
               <label class="block text-xs text-gray-600 mb-1">Month</label>
               <div class="flex items-center">
                  <select 
                     v-model="filterForm.month_filter" 
                     @change="filterLogs" 
                     class="w-full bg-white border border-gray-300 rounded-md pl-3 pr-3 py-2 text-sm text-gray-700"
                  >
                     <option value="all">All Months</option>
                     <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
                  </select>
               </div>
            </div>

            <div class="">
               <label class="block text-xs text-gray-600 mb-1">Registration Type</label>
               <div class="flex items-center">
                  <select 
                     v-model="filterForm.registration_type" 
                     @change="filterLogs" 
                     class="w-full bg-white border border-gray-300 rounded-md pl-3 pr-3 py-2 text-sm text-gray-700"
                  >
                     <option value="all">All Types</option>
                     <option v-for="registration in registrations" :key="registration.id" :value="registration.type">
                        {{ registration.type }}
                     </option>
                  </select>
               </div>
            </div>

            <div class="">
               <label class="block text-xs text-gray-600 mb-1">Payment Type</label>
               <div class="flex items-center">
                  <select 
                     v-model="filterForm.payment_method" 
                     @change="filterLogs" 
                     class="w-full bg-white border border-gray-300 rounded-md pl-3 pr-3 py-2 text-sm text-gray-700"
                  >
                     <option value="all">All Types</option>
                     <option v-for="paymentMethod in paymentMethods" :key="paymentMethod.id" :value="paymentMethod.type">
                        {{ paymentMethod.type }}
                     </option>
                  </select>
               </div>
            </div>
         </div>

         
         <table class="w-full text-left text-gray-600 overflow-auto">
            <thead class="text-sm font-semibold uppercase bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700">
               <tr class="text-center">
               <th scope="col" class="lg:px-5 px-3 py-3">CLIENT</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Payment Method</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Status</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Date</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Time</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>
               </tr>
            </thead>
            <tbody>
               <tr
               v-for="log in logs.data"
               :key="log.id"
               class="text-center bg-white hover:bg-gray-50 transition-colors duration-200"
               >
               <td class="py-2 px-3">{{ log.client.first_name }} {{ log.client.middle_initial + '.' }} {{ log.client.last_name }}</td>
               <td class="py-2 px-3">{{ log.payment_method ? log.payment_method.type: '' }}</td>
               <td class="py-2 px-3">{{ log && log.transaction_id ? "Paid" : "Unpaid" }}</td>
               <td class="py-2 px-3">{{ formatDate(log.date) }}</td>
               <td class="py-2 px-3">{{ formatTime(log.date) }}</td>
               <td class="py-2 px-3">
                  <button
                     class="text-red-600 hover:text-red-800 mx-2 transition-colors duration-300"
                     type="button"
                     @click="openDeleteModal(log)"
                     data-bs-toggle="modal"
                     data-bs-target="#deleteModal"
                  >
                     <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-trash3"
                        viewBox="0 0 16 16"
                     >
                     <path
                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"
                     />
                     </svg>
                  </button>
               </td>
               </tr>
            </tbody>
         </table>

         <!-- Pagination -->
         <div class="flex mt-4 justify-end">
            <Pagination class="flex space-x-2" :links="logs.links" />
         </div>
         
   
         <!-- Delete Log Modal -->
         <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="deleteModalTitle">Delete Log</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     Are you sure you want to delete Log # - {{deleteForm.id}} - {{deleteForm.first_name}} {{deleteForm.last_name}}?
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-danger" @click="deleteLog">Delete</button>
                  </div>
               </div>
            </div>
         </div>
      </div>  
   </div>
     
</Layout>
</template>
    
<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import Pagination from '../../Components/Pagination.vue';
import InputField from '../../Components/InputField.vue';


const props = defineProps({
   logs: (Array, Object),
   clients: Array,
   createdLog: Object,
   createdClient: Object,
   success: String,
   year_filter: String,
   month_filter: String,
   registrations: Array,
   paymentMethods: Array,
   search: String
});

const form = useForm({
   client_id: ''
});

const deleteForm = useForm({
   id: null,
   client_id: '',
   first_name: '',
   last_name: '',
});

const errors = ref({});
const header = ref('');
const alertMessage = ref('');

const submit = () => {
   errors.value = {};
   form.post(route('logs.manualStore'), {
      onError: (errors) => {
      errors.value = errors;
      form.reset();
      },
      onSuccess: (response) => {

      form.reset();

      const createModalElement = document.querySelector('#createModal');
      if (createModalElement) {
         const createModal = bootstrap.Modal.getInstance(createModalElement);
         if (createModal) {
            createModal.hide();
         }
      }
      
      // Set header and alert message based on response
      if (props.createdLog.transaction_id === null) {
         header.value = 'Please Pay At the Cashier';
         alertMessage.value = `Log created for ${props.createdClient.first_name} ${props.createdClient.last_name}. Payment required.`;
      } else {
         header.value = 'Payment Successful, Please Proceed';
         alertMessage.value = `Log created for ${props.createdClient.first_name} ${props.createdClient.last_name}. Payment confirmed.`;
      }

      showAlertModal();
      resetSelectedClientName();


      }
   });
};

const showAlertModal = () => {
   const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
   alertModal.show();

   setTimeout(() => {
      alertModal.hide();
   }, 4000);


};

const openDeleteModal = (log) => {
   deleteForm.id = log.id;
   deleteForm.client_id = log.client_id;
   deleteForm.first_name = log.client.first_name;
   deleteForm.last_name = log.client.last_name;
};

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

function formatTime(dateString) {
   const options = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
   return new Date(dateString).toLocaleTimeString(undefined, options);
}

const deleteLog = () => {
   deleteForm.delete(route('logs.destroy', deleteForm.id), {
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

// To hold the displayed client name
const selectedClientName = ref('');

// Update client ID when a name is selected from the datalist
const updateClientId = () => {
   const client = props.clients.find(
      c => `${c.first_name} ${c.last_name}` === selectedClientName.value
   );

      if (client) {
         form.client_id = client.id;
      } else {
         form.client_id = '';
      }
};

//reseting client name
const resetSelectedClientName = () => {
   selectedClientName.value = ''
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

const filterForm = useForm({
   year_filter: props.year_filter || 'all',
   month_filter : props.month_filter  || 'all',
   registration_type : props.registration_type || 'all',
   payment_method : props.payment_method || 'all',
   search : props.search || '',
   // member_filter : props.member_filter || false,
   // date_filter : props.date_filter || ''
})

const filterLogs = () => {
   router.get(route('logs.list'), { 
      year_filter: filterForm.year_filter,
      month_filter: filterForm.month_filter,
      registration_type: filterForm.registration_type,
      payment_method: filterForm.payment_method,
      search: filterForm.search,

      // member_filter : filterForm.member_filter,
      // date_filter: filterForm.date_filter
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};

</script>
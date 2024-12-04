<template>
   <Layout>
      <div class="p-3">
         <div class="flex justify-between">
            <div class="">
               <p class="text-3xl font-bold text-gray-900 my-2 align-middle">Transactions List</p>
            </div>
            <div class="">
               <form @submit.prevent="filterTransactions">
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

         <div class="bg-white shadow-md rounded overflow-auto p-3 m-2">
            
            <div class="flex justify-between">
               <!-- Total Earnings -->
               <div class="flex mb-4">
                  <div class="bg-white p-3 rounded-lg shadow-md">
                     <span class="text-gray-600 font-semibold mr-2">Total Earnings:</span>
                     <span class="text-xl font-bold ml-5 bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-red-400">₱ {{ totalEarnings }}</span>
                  </div>
               </div>

               <!-- Filter Section -->
               <div class="flex justify-end pb-4 space-x-4">
                  <div class="">
                     <label class="block text-xs text-gray-600 mb-1">Year</label>
                     <div class="flex items-center">
                        <select 
                           v-model="filterForm.year_filter" 
                           @change="filterTransactions" 
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
                           @change="filterTransactions" 
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
                           @change="filterTransactions" 
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
                           @change="filterTransactions" 
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
            </div>
            

            <!-- Transactions Table -->
            <table class="w-full text-left text-gray-600">
               <thead class="text-sm font-semibold uppercase bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700">
                  <tr class="text-center">
                     <th scope="col" class="lg:px-5 px-3 py-3">ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Descrption</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Duration</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Total</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <tr 
                     v-for="transaction in transactions.data" 
                     :key="transaction.id" 
                     class="text-center bg-white hover:bg-gray-50 transition-colors duration-200"
                  >
                     <td class="py-2 px-3">{{ transaction.id }}</td>
                     <td class="py-2 px-3">{{ transaction.description }}</td>
                     <td class="py-2 px-3">
                        <span v-if="transaction.start_date && transaction.end_date">
                           {{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}
                        </span>
                        <span v-else>-</span>
                     </td>
                     <td class="py-2 px-3">
                        {{ formatDate(transaction.transaction_date) }} {{ formatTime(transaction.transaction_date) }}
                     </td>
                     <td class="py-2 px-3">₱ {{ transaction.total_amount }}</td>
                     <td>
                        <button class="text-red-600 mx-2" type="button" @click="openTransactionDeleteModal(transaction)" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                           <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                           </svg>
                        </button>
                     </td>
                  </tr>
               </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex mt-4 justify-end">
               <Pagination class="flex space-x-2" :links="transactions.links" />
            </div>
         </div>

         <!-- Delete Transaction Modal -->
         <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="deleteTransactionModalTitle">Delete Transaction</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     Are you sure you want to delete Transaction # - {{deleteTransactionForm.id}} - {{deleteTransactionForm.first_name}} {{deleteTransactionForm.last_name}}?
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-danger" @click="deleteTransaction">Delete</button>
                  </div>
               </div>
            </div>
         </div> 

      </div>
   </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import Pagination from '../../Components/Pagination.vue';
import InputField from '../../Components/InputField.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
   transactions: (Array, Object),
   year_filter: String,
   month_filter: String,
   registrations: Array,
   paymentMethods: Array,
   search: String,
   totalEarnings: Number
});

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }); 
};

const deleteTransactionForm = useForm({
   id: null,
   client_id: '',
   first_name: '',
   last_name: '',
});

const openTransactionDeleteModal = (transaction) => {
   deleteTransactionForm.id = transaction.id;
   deleteTransactionForm.client_id = transaction.client_id;
   deleteTransactionForm.first_name = transaction.client.first_name;
   deleteTransactionForm.last_name = transaction.client.last_name;
}

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
   month_filter: props.month_filter || 'all',
   registration_type: props.registration_type || 'all',
   payment_method: props.payment_method || 'all',
   search: props.search || ''
});

const filterTransactions = () => {
   router.get(route('transactions.index'), { 
      year_filter: filterForm.year_filter,
      month_filter: filterForm.month_filter,
      registration_type: filterForm.registration_type,
      payment_method: filterForm.payment_method,
      search: filterForm.search
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};

const deleteTransaction = () => {
   deleteTransactionForm.delete(route('transactions.destroy', deleteTransactionForm.id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteTransactionModal');
         if(modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
               modal.hide();
            }
         }
      }
   });
};
</script>